<?php
require_once("inc.all.php");

$SIZEMAX = 3000000;

$btnPost = filter_input(INPUT_POST, "btnPost");
if ($btnPost) {

    $checkSizeMedia = false;
    $checkFormatMedia = false;
    

    $target_dir = "./img/photoUploads/";
    $uploadOk = true;
    $nbFiles = count($_FILES['fileToUpload']['name']);
    $description = filter_input(INPUT_POST, "tbxdescription", FILTER_SANITIZE_STRING);

    if ($description == "") {
        $uploadOk = false;
        # code...
    }

    if ($nbFiles == 0) {
        $uploadOk = false;
        # code...
    }

    if ($uploadOk) {
      
        for ($i=0; $i < $nbFiles; $i++) { 

            //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);   

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
                if (moveMediaToFolder($target_file,$i)) {
                    echo "yes";
                }
            }
        }
    }
}
/*
if (isset($_FILES["fileToUpload"]["name"])) {
    if (isset($_POST["submit"])) {
    }


    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][0]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
    }


    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][0], $target_file)) {
            AddMedia($maPhoto);
            echo "The file " . basename($_FILES["fileToUpload"]["name"][0]) . " has been uploaded.";
        } else {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][0], $target_file);
            echo "Sorry, there was an error uploading your file.";
        }
    }
    $_FILES["fileToUpload"] = [];
}*/
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