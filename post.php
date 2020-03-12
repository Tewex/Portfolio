<?php
require_once("inc.all.php");

$SIZEMAX = 3000000;
$arrTrueMedia = [];            

$showMessageError = false;
$showMessageCool = false;
$arrMedias = [];
$arrMessagesCool = [];
$arrMessagesError = [];
$btnPost = filter_input(INPUT_POST, "btnPost");
if ($btnPost) {
    $postOnlyComment = false;
    $commentaireOk = true;
    $target_dir = "./img/photoUploads/";
    $uploadOk = true;
    $nbFiles = 0;
    $fileError = $_FILES['fileToUpload']['error'][0];
    $commentaire = filter_input(INPUT_POST, "tbxdescription", FILTER_SANITIZE_STRING);

    for ($i = 0; $i < count($_FILES['fileToUpload']['name']); $i++) {
        if ($_FILES['fileToUpload']['name'][$i] != "" && $_FILES['fileToUpload']['error'][$i] == 0) {
            $arrTrueMedia[] = [$i]; //ne fonctionne pas
            //$arrTrueMedia = [$i]; //Fonctionne seulement pour un media
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

    if ($commentaire == "") {
        $uploadOk = false;
        $commentaireOk = false;
        $showMessageError = true;
        $arrMessagesError[] = "Veuillez ajouter un message";
    }

    if (count($arrTrueMedia) == 0) {
        $uploadOk = false;
        $postOnlyComment = true;

        //$arrMessagesError[] = "Veuillez ajouter un message";
    }

    if ($uploadOk) {

        foreach ($arrTrueMedia as $i) {
            $name = basename($_FILES["fileToUpload"]["name"][$i[0]]);
            $tmpName = $_FILES["fileToUpload"]["tmp_name"][$i[0]];
            $newName = changeMediaName();
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i[0]]);

            $sizeFile = $_FILES["fileToUpload"]["size"][$i[0]];

            $type = getFormatMedia($_FILES["fileToUpload"]["type"][$i[0]]);
            $content_type = mime_content_type($_FILES["fileToUpload"]["tmp_name"][$i[0]]);
            $fileExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $target_file = $target_dir . $newName . "." . $fileExtension;

           /* if (checkMediaSize($sizeFile, $SIZEMAX, $i)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de votre media est trop élévé";
            }/*
            /*
            if (checkMediaFormat($fileExtension)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "Le format de votre media est incompatible";
            }*/
            //VOIR TAILLE PAS OUF <!DOCTYPE html>
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //
            /*if (checkMediaFake($tmpName, $content_type)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "Votre media est faux";
            }*/
            if ($uploadOk) {
                if (moveMediaToFolder($target_file, $i[0])) {
                    $arrMedias[] = new cMedia(-1, $newName . "." . $fileExtension, $type, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                    $arrMessagesCool[] = "Le'media " . $name . " a été Upload";
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
        else{
            deletefilesServer($arrMedias);
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