<?php
/**
 * File: photos.php
 * Author: Théo Hurlimann
 * Date: 17.12.2019
 * Description: manage Post.
 * Version: 1.0
 */
require_once 'databaseConnection.php';
//require_once "../../inc.all.php";
function addPost($post)
{
  $sql = "INSERT INTO post(commentaire, creationDate, modificationDate) VALUES(:commentaire, :creationDate, :modificationDate)";
  try {
    //$dbh = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
    $dbh = UserDbConnection();
    $dbh->beginTransaction();
    $stmt = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));

    $stmt->bindParam(":commentaire", $post->commentaire, PDO::PARAM_STR);
    $stmt->bindParam(":creationDate", $post->creationDate, PDO::PARAM_STR);
    $stmt->bindParam(":modificationDate", $post->modificationDate, PDO::PARAM_STR);
    $stmt->execute();

    $idPost = $dbh->lastInsertId();
    foreach ($post->media as $monMedia) {
      Addmedia($monMedia, $idPost);
    }

    $dbh->commit();
    return true;
  } catch (Exception $e) {
    $dbh->rollBack();
    return false;
  }
}

function getPostById($id)
{
  $arrMedias = [];
  $post = "";
  $sql = "SELECT idPost, commentaire, creationDate, modificationDate FROM portfolio.post WHERE idPost=:id LIMIT 1";

  $dbh = UserDbConnection();
  $stmt = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
  $stmt->bindParam(":id", $id, PDO::PARAM_STR);
  if ($stmt->execute()) {
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $post = new cPost($row[0]['idPost'], $arrMedias, $row[0]['commentaire'], $row[0]['creationDate'], $row[0]['modificationDate']);

    $post->media = getMediaByIdPost($id);
    return $post;
  }
}
function getAllPost()
{

  $arrPost = [];
  $sql = "SELECT p.idPost,p.commentaire,p.creationDate as createDatePost,p.modificationDate as modifDatePost,m.idMedia,m.nomFichierMedia,m.typeMedia,m.creationDate,m.modificationDate,m.idPost_Media
  FROM post as p
  LEFT OUTER JOIN media as m
  ON p.idPost = m.idPost_media
  ORDER BY p.creationDate DESC";

  $query = UserDbConnection()->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
  if ($query->execute()) {
    $row = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($row); $i++) {
      $dontstop = true;
      $arrMedias = [];
      $post = new cPost($row[$i]['idPost'], $arrMedias, $row[$i]['commentaire'], $row[$i]['createDatePost'], $row[$i]['modifDatePost']);
      if ($row[$i]['idMedia'] != null) {
        $arrMedias[] = new cMedia($row[$i]['idMedia'], $row[$i]['nomFichierMedia'], $row[$i]['typeMedia'], $row[$i]['creationDate'], $row[$i]['modificationDate'], $row[$i]['idPost']);
      }
      $f = $i + 1;
      if ($f < count($row)) {
        while ($row[$i]['idPost_Media'] == $row[$f]['idPost_Media']  && $dontstop && $row[$i]['idMedia'] != null) {
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



function deletePostById($post)
{
  $sql = "DELETE FROM `portfolio`.`post` WHERE idPost = :idPost";
  try {
    $dbh = UserDbConnection();
    $dbh->beginTransaction();
    $stmt = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));

    $stmt->bindParam(":idPost", $post->idPost, PDO::PARAM_STR);

    if ($post->media != "") {
      foreach ($post->media as $media) {
        deleteMediaById($media->idMedia);
      }
    }
    $stmt->execute();
    $dbh->commit();
    return true;
  } catch (Exception $e) {
    $dbh->rollBack();
    return false;
  }
}


function getHtmlForAllPost($arrPost)
{
  $html = "<div uk-filter=\"target: .js-filter\">";
  $html .= "<div class=\"uk-grid-small uk-flex-middle\" uk-grid>";
  $html .= "<div class=\"uk-width-expand\">";
  $html .= "</div>";
  $html .= "<div class=\"uk-width-auto uk-text-nowrap\">";
  $html .= "<span uk-filter-control=\"sort: datatime-date\"><a class=\"uk-icon-link\" href=\"#\" uk-icon=\"icon: arrow-down\"></a></span>";
  $html .= "<span class=\"uk-active\" uk-filter-control=\"sort: datatime-date; order: desc\"><a class=\"uk-icon-link\" href=\"#\" uk-icon=\"icon: arrow-up\"></a></span>";
  $html .= "</div>";
  $html .= "</div>";
  $html .= "<ul class=\"js-filter uk-child-width-1-1 uk-child-width-1-2@m uk-child-width-1-3@l uk-position-relative uk-visible-toggle\" uk-grid=\"masonry: true\">";



  foreach ($arrPost as $post) {
    $html .= " <li datatime-date=" .  $post->creationDate . ">";
    $html .= "<div class=\" uk-card uk-card-default  uk-card-body uk-margin-left\">";
    if (count($post->media) !== 0) {
      $html .= "<div class=\" uk-position-relative uk-light uk-visible-toggle uk-text-center \"  tabindex=\"-1\" uk-slideshow=\"ratio: 7:3;\">";
      $html .= "<ul class=\" uk-slideshow-items\" tabindex=\"-1\" >"; //uk-height-viewport=\"offset-top: true; \"

      foreach ($post->media as $monMedia) {

        switch ($monMedia->typeMedia) {
          case "image":
            $html .= "<li>";
            $html .= "<img class=\"\" src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . "  uk-cover uk-img alt=\"\" >";
            $html .= "</li>";
            break;
          case "video":
            $html .= "<li>";
            $html .= "<video src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " autoplay loop playsinline uk-cover></video>";
            $html .= "</li>";
            break;
          case "audio":
            $html .= "<li>";
            $html .= "<audio controls src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " ></audio>";
            $html .= "</li>";
            break;
        }
      }
      $html .= "</ul>";
      $html .= "<a class=\"uk-position-center-left uk-position-small uk-hidden-hover\" href=\"#\" uk-slidenav-previous uk-slideshow-item=\"previous\"></a>";
      $html .= "<a class=\"uk-position-center-right uk-position-small uk-hidden-hover\" href=\"#\" uk-slidenav-next uk-slideshow-item=\"next\"></a>";
      $html .= "</div>";
    }
    $html .= "<hr class=\"uk-divider-icon\">";
    $html .= "<article class=\"uk-article\">";
    $html .= "<div class=\"uk-panel\">";
    $html .= "<p class=\" uk-text-meta\">" . $post->creationDate . "</p>";
    if ($post->creationDate != $post->modificationDate) {
      $html .= "<p class=\" uk-text-meta\">Modifié le " . $post->modificationDate . "</p>";
    }
    
    $html .= "</div>";

    $html .= "<p class=\"uk-text-lead\">" .  $post->commentaire . "</p>";
    $html .= "<a href = editPost.php?id=" . $post->idPost . "><i class=\"fas fa-pen\"></i>&nbsp;&nbsp;</a>";
    $html .= "<a href = deletePost.php?id=" . $post->idPost . "><i class=\"fas fa-trash-alt\"></i></a>";
    $html .= "</article>";
    $html .= "</div>";
    $html .= "</li>";
  }

  $html .= "</ul>";
  $html .= "</div>";
  $html .= "</div>";
  return $html;
}

function updatePostById($post)
{
  $dbh = UserDbConnection();
  $sql = "UPDATE `portfolio`.`post` SET `commentaire` = :commentaire, `modificationDate` = :modificationDate WHERE (`idPost` = :idPost);";
  try {
    $stmt = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
    $stmt->bindParam(":modificationDate", $post->modificationDate, PDO::PARAM_STR);
    $stmt->bindParam(":commentaire", $post->commentaire, PDO::PARAM_STR);
    $stmt->bindParam(":idPost", $post->idPost, PDO::PARAM_STR);
    $stmt->execute();
    return true;
  } catch (Exception $e) {
    return false;
  }
}
