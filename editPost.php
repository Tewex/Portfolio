<?php
require_once("inc.all.php");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);

$post = getPostById($id);
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
    <div class="content">
        <form action="" method="POST" enctype="multipart/form-data">
        <fieldset class="uk-fieldset">

                <legend class="uk-legend">Editer votre media</legend>
                <p clas="uk-text-lead">Commentaire :</p>
                <div class="uk-margin">
                    <textarea  name="tbxdescription" class="uk-textarea" style="resize: none;" rows="5" placeholder="Description"><?php echo $post->commentaire?></textarea>
                </div>
                <p clas="uk-text-lead">Medias :</p>
                <?php 
                    foreach ($post->media as $media) {
                        
                    }
                ?>

                <div class="uk-margin" uk-margin>

                    <div uk-form-custom>
                        <input type="file" name="fileToUpload[]" multiple accept="image/x-png, image/gif, image/jpeg">
                        <i class="far fa-images"></i>
                    </div>
                    <div uk-form-custom>
                        <input type="file" name="fileToUpload[]" multiple accept="video/*">
                        <span class="uk-margin-small-right" uk-icon="video-camera"></span>
                    </div>
                    <!--<div uk-form-custom>
                        <input type="file" name="fileToUpload" id="fileToUpload[]" multiple accept="image/x-png, image/gif, image/jpeg">
                        <i class="fas fa-microphone"></i>
                    </div>-->
                    <button name="btnPost" value="Envoyez" class="uk-button uk-button-default">Publier</button>
                </div>
            </fieldset>
        </form>
    </div>
</body>


</html>