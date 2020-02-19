<?php 
require_once 'databaseConnection.php';

function addPost($post){
    $sql = "INSERT INTO Post(titrePost, descriptionPost, dateCreationPost, dateModificationPost) VALUES(:titre, :descr, :dateCrea, :dateModif)";
    $req = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
    $req->execute(
      array(
         'titrePost' => $titre,
         'descriptionArticle' => $description,
         'dateCrea' => date("Y-m-d H:i:s"),
         'dateModif' => date("Y-m-d H:i:s")
         )
     );
    $id = UserDbConnection()->lastInsertId();


    $sql = "INSERT INTO Media(typeMedia, nomFichierMedia, dateCreationMedia, dateModificationMedia, idPost) VALUES(:typeMedia, :nomFichier, :dateCrea, :dateModif, :post)";
    $req = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
    $req->execute(
      array(
         'typeMedia' => $typeMedia,
         'nomFichier' => $nomFichier,
         'dateCrea' => date("Y-m-d H:i:s"),
         'dateModif' => date("Y-m-d H:i:s"),
         'post' => $id
         )
     );   
}

?>