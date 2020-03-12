<?php
require_once("inc.all.php");
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$post = getPostById($id);
//ACTIVE THIS extension=fileinfo in PHP INI
//mime_content_type

$btnDelete = filter_input(INPUT_POST, "btnDelete");
if ($btnDelete) {
  if(deletePostById($post))
  {
      deletefilesServer($post->media);
      header('Location: index.php');
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
    <div class="content">
        <h2 class="uk-alert-danger" uk-alert>
            Êtes-vous sûr de vouloir supprimer ce post ?
        </h2>
        <p clas="uk-article-title">Commentaire :</p>
        <p class="uk-text-lead"><?php echo $post->commentaire ?></p>
        <?php
        foreach ($post->media as $monMedia) {
            switch ($monMedia->typeMedia) {
                case "image":
                    echo "<li>";
                    echo "<img class=\"\" src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " uk-img alt=\"\">";
                    echo "</li>";
                    break;
                case "video":
                    echo "<li>";
                    echo "<video src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " autoplay loop playsinline ></video>";
                    echo "</li>";
                    break;
                case "audio":
                    echo "<li>";
                    echo "<audio controls src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " ></audio>";
                    echo "</li>";
                    break;
            }
        }

        ?>
        <form action="" method="POST">
            <button name="btnDelete" value="delete"class="uk-button uk-button-danger">Supprimer</button>
        </form>
</body>


</html>