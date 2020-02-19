<?php 
require_once 'databaseConnection.php';

function addPost($post){
    $sql = "INSERT INTO post(commentaire, creationDate, modificationDate) VALUES(:commentaire, :creationDate, :modificationDate)";
    $req = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
    $req->execute(
      array(
         'commentaire' => $post->commentaire,
         'creationDate' => $post->creationDate,
         'modificationDate' => $post->modificationDate
         )
     );
    $idPost = UserDbConnection()->lastInsertId();
     foreach ($post->media as $monMedia) {
       Addmedia($monMedia, $idPost);
     }  
}

?>