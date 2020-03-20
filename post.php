<?php
require_once("inc.all.php");

            
$arrTrueMedia = [];            
$arrMedias = [];
$arrMessagesCool = [];
$arrMessagesError = [];

$showMessageError = false;
$showMessageCool = false;
$postOnlyComment = false;

$commentaireOk = true;
$uploadOk = true;

$btnPost = filter_input(INPUT_POST, "btnPost");
if ($btnPost) {
    
    $commentaire = filter_input(INPUT_POST, "tbxdescription", FILTER_SANITIZE_STRING);

    //Je check combien d'image on été upload. Je gere l'erreur en cas de problème de poid de media ou poid total
    for ($i = 0; $i < count($_FILES['fileToUpload']['name']); $i++) {
        if ($_FILES['fileToUpload']['name'][$i] != "" && $_FILES['fileToUpload']['error'][$i] == 0) {
            $arrTrueMedia[] = [$i];
        } else {
            if ($_FILES['fileToUpload']['error'][$i] == 1) {
                $uploadOk = false;
                $commentaireOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de votre media est trop élévé";
            }
            else if ($_FILES['fileToUpload']['error'][$i] == 2  ) {
                $uploadOk = false;
                $commentaireOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de l'ensemble de vos media est trop élévé";
            }
            
        }
    }


    if (count($arrTrueMedia) == 0 && $commentaire != "") {
        $uploadOk = false;
        $postOnlyComment = true;
    }

    if ($uploadOk) {

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
                    $arrMessagesCool[] = "Le media " . $name . " a été Upload";
                    $showMessageCool = true;
                    
                } else {
                    $showMessageError = true;
                    $arrMessagesError[] = "Une erreur est survenu lors du chargement du Media";
                }
            }
            $uploadOk = true;
        }
        if (count($arrMedias) != 0) {
            $monPost = new cPost(-1, $arrMedias, $commentaire, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
            if (addPost($monPost)) {
                $showMessageCool = true;
                $arrMessagesCool[] = "Votre post a été ajouté";
            } else {
                deletefilesServer($arrMedias);
                $showMessageError = true;
                $arrMessagesError[] = "Une erreur est survenu lors de l'ajout de votre post";
            }
        }
    } elseif ($commentaireOk && $postOnlyComment) {
        $showMessageError = false;
        $monPost = new cPost(-1, $arrMedias, $commentaire, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
        if (addPost($monPost)) {
            $showMessageCool = true;
            $arrMessagesCool[] = "Votre post a été ajouté";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POST</title>
    <link rel="stylesheet" href="css/uikit.css">
    <link rel="stylesheet" href="css/sticky.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
    <script src="https://kit.fontawesome.com/03529e1b19.js" crossorigin="anonymous"></script>
</head>

<body>

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

        <form action="" method="POST" enctype="multipart/form-data">
            <fieldset class="uk-fieldset">

                <legend class="uk-legend">Ajouter votre media</legend>

                <div class="uk-margin">
                    <textarea name="tbxdescription" class="uk-textarea" style="resize: none;" rows="5" placeholder="Description"></textarea>
                </div>


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
                        <input type="file" name="fileToUpload[]"  multiple accept="audio/*">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <button name="btnPost" value="Envoyez" class="uk-button uk-button-default">Publier</button>
                </div>
            </fieldset>

        </form>
    </body>
</body>




</html>