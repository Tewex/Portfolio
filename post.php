<?php
require_once("inc.all.php");

$SIZEMAX = 3000000;

$showMessageError = false;
$showMessageCool = false;
$arrMedias = [];
$arrMessagesCool = [];
$arrMessagesError = [];

$btnPost = filter_input(INPUT_POST, "btnPost");
if ($btnPost) {
    $commentaireOk = true;
    $target_dir = "./img/photoUploads/";
    $uploadOk = true;
    $nbFiles = count($_FILES['fileToUpload']['name']);
    $fileError = $_FILES['fileToUpload']['error'][0];
    $commentaire = filter_input(INPUT_POST, "tbxdescription", FILTER_SANITIZE_STRING);

    if ($commentaire == "") {
        $uploadOk = false;
        $commentaireOk = false;
        $showMessageError = true;
        $arrMessagesError[] = "Veuillez ajouter un message";
    }

    if ($nbFiles == 0) {
        $uploadOk = false;
        $showMessageError = true;
        $arrMessagesError[] = "Veuillez ajouter une image";
    }

    if ($uploadOk && $fileError == 0) {

        for ($i = 0; $i < $nbFiles; $i++) {
            $name = basename($_FILES["fileToUpload"]["name"][$i]);
            $newName = changeMediaName();
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);

            $sizeFile = $_FILES["fileToUpload"]["size"][$i];

            $fileExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $target_file = $target_dir . $newName . "." . $fileExtension;

            if (checkMediaSize($sizeFile, $SIZEMAX, $i)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "La taille de votre image est trop élévé";
            }

            if (checkMediaFormat($fileExtension)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "Le format de votre image est incompatible";
            }

            if ( $sizeFile != 0 && checkMediaFake($i)) {
                $uploadOk = false;
                $showMessageError = true;
                $arrMessagesError[] = "Votre image est fausse";
            }
            if ($uploadOk) {
                if (moveMediaToFolder($target_file, $i)) {
                    $arrMedias[] = new cMedia(-1, $newName . "." . $fileExtension, $fileExtension, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                    $arrMessagesCool[] = "L'image " . $name . " a été Upload";
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
    }
    elseif ($commentaireOk) {
        $monPost = new cPost(-1, $arrMedias, $commentaire, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
        addPost($monPost);
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
                   echo "<p>".$message."</p>";
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
                   echo "<p>".$message."</p>";
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
                        <input type="file" name="fileToUpload[]" multiple accept="image/x-png, image/gif, image/jpeg">
                        <i class="far fa-images"></i>
                    </div>
                    <!--<div uk-form-custom>
                        <input type="file" name="fileToUpload" id="fileToUpload[]" multiple accept="image/x-png, image/gif, image/jpeg">
                        <span class="uk-margin-small-right" uk-icon="video-camera"></span>
                    </div>
                    <div uk-form-custom>
                        <input type="file" name="fileToUpload" id="fileToUpload[]" multiple accept="image/x-png, image/gif, image/jpeg">
                        <i class="fas fa-microphone"></i>
                    </div>-->
                    <button name="btnPost" value="Envoyez" class="uk-button uk-button-default">Publier</button>
                </div>
            </fieldset>

        </form>
    </body>
</body>

<script src="https://kit.fontawesome.com/03529e1b19.js" crossorigin="anonymous"></script>


</html>