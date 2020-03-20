<?php
require_once("inc.all.php");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$idPostDelete = [];
$newPost = getPostById($id);
$oldpost = new cPost($newPost->idPost, $newPost->media, $newPost->commentaire, $newPost->creationDate, $newPost->modificationDate);
$showMessageError = false;
$showMessageCool = false;
$arrMessagesCool = [];
$arrMessagesError = [];

$btnPost = filter_input(INPUT_POST, "btnUpdatePost");

$arrMedias = [];
$arrTrueMedia = [];
if ($btnPost) {
    $newCommentaire = filter_input(INPUT_POST, "tbxdescription", FILTER_SANITIZE_STRING);
    $goUpdate = true;
    $uploadOk = true;
    $newUpdate = false;
    //Update du commentaire
    if ($newCommentaire != $newPost->commentaire) {
        $newPost->commentaire = $newCommentaire;
        $newUpdate = true;
    }


    //Regarde chaque checkboxidMedia si coché -> ajoute dans un tableau
    foreach ($newPost->media as  $monMedia) {
        if (!empty($_POST["media" . $monMedia->idMedia])) {
            $idPostDelete[] = filter_input(INPUT_POST, "media" . $monMedia->idMedia, FILTER_SANITIZE_STRING);
        }
    }

    //Pour chaque idPostDelete compare avec idMedia dans post pour suppr si ==
    foreach ($idPostDelete as $index => $monIdDelete) {
        foreach ($newPost->media as $indexMedia => $monMedia) {
            $idMediaTest = $monMedia->idMedia;
            if ($monIdDelete == $idMediaTest) {
                if (deleteMediaById($monMedia->idMedia)) {
                    deleteFileServerByName($monMedia->nomFichierMedia);
                    unset($newPost->media[$indexMedia]);
                    $newUpdate = true;
                    $arrMessagesCool[] = "Votre media à bien été supprimé";
                    $showMessageCool = true;
                } else {
                    $goUpdate = false;
                    $arrMessagesError[] = "La taille de l'ensemble de vos media est trop élévé";
                    $showMessageError = true;
                }
            }
        }
    }

    for ($i = 0; $i < count($_FILES['fileToUpload']['name']); $i++) {
        if ($_FILES['fileToUpload']['name'][$i] != "" && $_FILES['fileToUpload']['error'][$i] == 0) {
            $arrTrueMedia[] = [$i];
        } else {
            if ($_FILES['fileToUpload']['error'][$i] == 1) {
                $uploadOk = false;
                $commentaireOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de votre media est trop élévé";
            } else if ($_FILES['fileToUpload']['error'][$i] == 2) {
                $uploadOk = false;
                $commentaireOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de l'ensemble de vos media est trop élévé";
            }
        }
    }
    if (count($arrTrueMedia) != 0) {
        foreach ($arrTrueMedia as $i) {
            //Je prends les informations utiles de $_FILES
            $name = $_FILES["fileToUpload"]["name"][$i[0]];
            $tmpName = $_FILES["fileToUpload"]["tmp_name"][$i[0]];
            $target_file = $_FILES["fileToUpload"]["name"][$i[0]];
            $sizeFile = $_FILES["fileToUpload"]["size"][$i[0]];


            //J'identifie le type du media ainsi que sa réelle extension
            $type_extension = mime_content_type($tmpName);
            $type = getFormatMedia($type_extension);
            $fileExtension = getRealExtensionMedia($type_extension);


            $newName = changeMediaName();
            $target_file = CHEMINMEDIA . $newName . "." . $fileExtension;

            if (checkMediaSize($sizeFile, SIZEMEDIAMAX, $i)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de votre media est trop élévé";
            }

            if ($type == null) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "Votre media n'est pas une image/video/audio.";
            }
            if ($uploadOk) {
                if (moveMediaToFolder($target_file, $i[0])) {
                    $arrMedias[] = new cMedia(-1, $newName . "." . $fileExtension, $type, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                } else {
                    $uploadOk = false;
                }
            }
        }
    }
    if (count($arrMedias) != 0) {
        foreach ($arrMedias as $monMedia) {
            if (Addmedia($monMedia, $newPost->idPost)) {
                $newUpdate = true;
                $arrMessagesCool[] = "Votre media à bien été ajouté.";
                $showMessageCool = true;
            } else {
                deleteFileServerByName($monMedia->nomFichierMedia);
                $showMessageError = true;
                $arrMessagesError[] = "Un problème est survenu lors de l'ajout de votre media.";
            }
        }
    }
    if ($newUpdate) {
        $newPost->modificationDate = date("Y-m-d H:i:s");
        updatePostById($newPost);
        $arrMessagesCool[] = "Votre post à bien été mis à jour.";
        $showMessageCool = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HOME</title>
    <link rel="stylesheet" href="css/uikit.css">
    <link rel="stylesheet" href="css/sticky.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
    <script src="https://kit.fontawesome.com/03529e1b19.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include_once("nav.php") ?>
    <?php
    if ($showMessageError) { ?>
        <div class="uk-alert-danger" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <?php
            foreach ($arrMessagesError as $message) {
                echo "<p>" . $message . "</p>";
            }

            ?>
        </div>
    <?php   }
    ?>
    <?php
    if ($showMessageCool) { ?>
        <div class="uk-alert-success" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <?php
            foreach ($arrMessagesCool as $message) {
                echo "<p>" . $message . "</p>";
            }

            ?>
        </div>
    <?php
    }
    ?>
    <div class="content">
        <form action="" method="POST" enctype="multipart/form-data">
            <fieldset class="uk-fieldset">

                <legend class="uk-legend  ">Editer votre post</legend>
                <p clas="uk-text-lead">Commentaire :</p>
                <div class="uk-margin">
                    <textarea name="tbxdescription" class="uk-textarea" style="resize: none;" rows="5" placeholder="Description"><?php echo $newPost->commentaire ?></textarea>
                </div>
                <p clas="uk-text-lead">Medias : (Cocher pour supprimer)</p>
                <?php
                foreach ($newPost->media as $monMedia) {
                    switch ($monMedia->typeMedia) {
                        case "image":
                            echo "<div class=\"uk-flex uk-margin uk-margin-left\">";
                            echo "<label><input name=media" . $monMedia->idMedia . " value=" . $monMedia->idMedia . "  class=\" uk-flex-first uk-checkbox\" type=\"checkbox\" > </label>";
                            echo "<div class=\"uk-margin-left uk-flex-last uk-cover-container uk-width-small uk-height-small\">";
                            echo "<img src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " uk-cover uk-img alt=\"\">";
                            echo "</div>";
                            echo "</div>";
                            break;
                        case "video":
                            echo "<div class=\"uk-flex uk-margin uk-margin-left\">";
                            echo "<label><input class=\" uk-flex-first uk-checkbox\" type=\"checkbox\" > </label>";
                            echo "<div class=\"uk-margin-left uk-flex-last uk-cover-container uk-width-small uk-height-small\">";
                            echo "<video src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " uk-cover autoplay loop playsinline ></video>";
                            echo "</div>";
                            echo "</div>";
                            break;
                        case "audio":
                            echo "<div class=\"uk-flex uk-margin uk-margin-left\">";
                            echo "<label><input class=\" uk-flex-first uk-checkbox\" type=\"checkbox\" > </label>";
                            echo "<div class=\"uk-margin-left uk-flex-last uk-cover-container uk-width-small uk-height-small\">";
                            echo "<audio controls src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " > </audio>";
                            echo "</div>";
                            echo "</div>";
                            break;
                    }
                }
                ?>
                <p clas="uk-text-lead">Ajouter un/des medias :</p>
                <div class="uk-margin" uk-margin>

                    <div uk-form-custom>
                        <input type="file" name="fileToUpload[]" multiple accept="image/*">
                        <i class="far fa-images"></i>
                    </div>
                    <div uk-form-custom>
                        <input type="file" name="fileToUpload[]" multiple accept="video/*">
                        <span class="uk-margin-small-right" uk-icon="video-camera"></span>
                    </div>
                    <div uk-form-custom>
                        <input type="file" name="fileToUpload[]" multiple accept="audio/*">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div>
                        <button name="btnUpdatePost" value="Envoyez" class="uk-button uk-button uk-button-primary">Mettre à jour le post</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</body>


</html>