<?php
require_once("inc.all.php");

$arrPost = getAllPost();
//ACTIVE THIS extension=fileinfo in PHP INI
//mime_content_type



 
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
            <?php
                echo getHtmlForAllPost($arrPost);
            ?>
</body>


</html>