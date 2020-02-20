<?php
require_once("inc.all.php");

$SIZEMAX = 3000000;
$arrMedias;
$btnPost = filter_input(INPUT_POST, "btnPost");
if ($btnPost) {

    $checkSizeMedia = false;
    $checkFormatMedia = false;


    $target_dir = "./img/photoUploads/";
    $uploadOk = true;
    $nbFiles = count($_FILES['fileToUpload']['name']);
    $commentaire = filter_input(INPUT_POST, "tbxdescription", FILTER_SANITIZE_STRING);

    if ($commentaire == "") {
        $uploadOk = false;
        # code...
    }

    if ($nbFiles == 0) {
        $uploadOk = false;
        # code...
    }

    if ($uploadOk) {

        for ($i = 0; $i <= $nbFiles; $i++) {

            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $target_file = $target_dir . changeMediaName($i) . "." . $imageFileType;

            if (checkMediaSize($SIZEMAX, $i)) {
                $uploadOk = false;
            }

            if (checkMediaFormat($imageFileType)) {
                $uploadOk = false;
            }

            if (checkMediaFake($i)) {
                $uploadOk = false;
            }
            if ($uploadOk) {
                if (moveMediaToFolder($target_file, $i)) {
                    $monMedia = new cMedia(-1, $_FILES["fileToUpload"]["name"][$i], $imageFileType, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                    $arrMedias[] = $monMedia;
                    if ($i == $nbFiles) {
                        $monPost = new cPost(-1, $arrMedias, $commentaire, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                        addPost($monPost);
                    }
                }
            }
            $uploadOk = true;
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
</head>

<body>

    <body>
        <?php include_once("nav.php") ?>

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
                        <input type="file" name="fileToUpload" id="fileToUpload" multiple accept="image/x-png, image/gif, image/jpeg">
                        <span class="uk-margin-small-right" uk-icon="video-camera"></span>
                    </div>
                    <div uk-form-custom>
                        <input type="file" name="fileToUpload" id="fileToUpload" multiple accept="image/x-png, image/gif, image/jpeg">
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