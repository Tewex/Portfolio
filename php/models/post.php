<?php
require_once 'databaseConnection.php';
//require_once "../../inc.all.php";
function addPost($post)
{
  $sql = "INSERT INTO post(commentaire, creationDate, modificationDate) VALUES(:commentaire, :creationDate, :modificationDate)";
  $req = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
  $req->bindParam(":commentaire",$post->commentaire, PDO::PARAM_STR);
  $req->bindParam(":creationDate",$post->creationDate, PDO::PARAM_STR);
  $req->bindParam(":modificationDate",$post->modificationDate, PDO::PARAM_STR);
  if ($req->execute()) {
    $idPost = UserDbConnection()->lastInsertId();
    foreach ($post->media as $monMedia) {
      if (Addmedia($monMedia, $idPost)) {
      }
    }
    return true;
  } else
    return false;
}

function getAllPost()
{
  $arrPostWith = getAllPostWithMedia();
  $arrPostWhitout = getAllPostWhitoutMedia();
  return array_merge($arrPostWhitout, $arrPostWith);
}

function getAllPostWhitoutMedia()
{
  $arrMedias = [];
  $arrPost = [];
  $sql = "SELECT p.idPost,p.commentaire,p.creationDate,p.modificationDate FROM POST as p
  LEFT JOIN MEDIA as m
  ON p.idPost=m.idPost_media
  WHERE m.idPost_media is null;";

  $query = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
  if ($query->execute()) {
    $row = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($row); $i++) {
      $post = new cPost($row[$i]['idPost'], $arrMedias, $row[$i]['commentaire'], $row[$i]['creationDate'], $row[$i]['modificationDate']);
      array_push($arrPost, $post);
    }
    return $arrPost;
  } else {
    return NULL;
  }
}

function getAllPostWithMedia()
{
  $count = 0;

  $arrPost = [];
  $sql = "SELECT p.idPost,p.commentaire,p.creationDate,p.modificationDate,m.idMedia,m.nomFichierMedia,m.typeMedia,m.creationDate,m.modificationDate,m.idPost_Media
  FROM post as p
  INNER JOIN media as m
  ON p.idPost = m.idPost_media";

  $query = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
  if ($query->execute()) {
    $row = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($row); $i++) {
      $dontstop = true;
      $arrMedias = [];
      $post = new cPost($row[$i]['idPost'], $arrMedias, $row[$i]['commentaire'], $row[$i]['creationDate'], $row[$i]['modificationDate']);

      $arrMedias[] = new cMedia($row[$i]['idMedia'], $row[$i]['nomFichierMedia'], $row[$i]['typeMedia'], $row[$i]['creationDate'], $row[$i]['modificationDate'], $row[$i]['idPost']);

      $f = $i + 1;
      if ($f < count($row)) {
        while ($row[$i]['idPost_Media'] == $row[$f]['idPost_Media']  && $dontstop) {
          $i++;
          $f++;

          $arrMedias[] = new cMedia($row[$i]['idMedia'], $row[$i]['nomFichierMedia'], $row[$i]['typeMedia'], $row[$i]['creationDate'], $row[$i]['modificationDate'], $row[$i]['idPost']);

          if ($f >= count($row)) {
            $f = 0;
            $dontstop = false;
          }
        }
      }

      $post->media = $arrMedias;
      unset($arrMedias);
      array_push($arrPost, $post);
    }
    return $arrPost;
  } else {
    return NULL;
  }
}

/*SELECT post.idPost, post.commentaire, post.creationDate, post.modificationDate, GROUP_CONCAT(media.typeMedia) 
as mediaTypes, GROUP_CONCAT(media.nomFichierMedia) as mediaNames FROM post
            LEFT JOIN media ON media.idPost_media = post.idPost
            GROUP BY post.idPost
            ORDER BY post.creationDate DESC*/
