<?php
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

function getAllPost()
{

  $arrPostWith = getAllPostWithMedia();
  $arrPostWhitout = getAllPostWhitoutMedia();
  $arrPost = array_merge($arrPostWhitout, $arrPostWith);

  usort($arrPost, "orderByDate");

  return $arrPost;
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

function  orderByDate($a, $b)
{
  //retourner 0 en cas d'égalité
  if ($a->creationDate == $b->creationDate) {
    return 0;
  } else if ($a->creationDate < $b->creationDate) { //retourner -1 en cas d’infériorité
    return 1;
  } else { //retourner 1 en cas de supériorité
    return -1;
  }
}

function getHtmlForAllPost($arrPost)
{
  $html = "<div uk-filter=\"target: .js-filter\">";
  $html .= "<div class=\"uk-grid-small uk-flex-middle\" uk-grid>";
  $html .= "<div class=\"uk-width-expand\">";
  $html .= "</div>";
  $html .= "<div class=\"uk-width-auto uk-text-nowrap\">";
  $html .= "<span uk-filter-control=\"sort: data-dateCreation\"><a class=\"uk-icon-link\" href=\"#\" uk-icon=\"icon: arrow-down\"></a></span>";
  $html .= "<span class=\"uk-active\" uk-filter-control=\"sort: data-dateCreation; order: desc\"><a class=\"uk-icon-link\" href=\"#\" uk-icon=\"icon: arrow-up\"></a></span>";
  $html .= "</div>";
  $html .= "</div>";
  $html .= "<ul class=\"js-filter uk-child-width-1-1 uk-child-width-1-3@m uk-position-relative uk-visible-toggle\" uk-grid=\"masonry: true\">";



  foreach ($arrPost as $post) {
    $html .= " <li data-dateCreation=" .  $post->creationDate . ">";
    $html .= "<div class=\" uk-card uk-card-default  uk-card-body uk-margin-left\">";
    if (count($post->media) !== 0) {
      $html .= "<div class=\"uk-text-center \" tabindex=\"-1\" uk-slideshow=\"ratio: 7:3;\">";
      $html .= "<ul class=\"uk-slideshow-items\" tabindex=\"-1\">"; //uk-height-viewport=\"offset-top: true; \"

      foreach ($post->media as $monMedia) {
        
        switch ($monMedia->typeMedia) {
          case "image":
            $html .= "<li>";
            $html .= "<img class=\"\" src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . "  uk-cover uk-img alt=\"\">";
            $html .= "</li>";
          break;
          case "video":
            $html .= "<li>";
            $html .= "<video src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " autoplay loop playsinline uk-cover></video>";
            $html .= "</li>";
          break;
          case "audio":
            $html .= "<li>";
            $html .= "<audio src=" . CHEMINMEDIA . $monMedia->nomFichierMedia . " ></audio>";
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
      $html .= "<p class=\"uk-text-left uk-text-meta\">" . $post->creationDate . "</p>";
      $html .= "<p class=\"uk-text-lead\">" .  $post->commentaire . "</p>";
      $html .= "</article>";
      $html .= "</div>";
      $html .= "</li>";
  }
     
      $html .= "</ul>";
      $html .= "</div>";
      $html .= "</div>";
  return $html;
}
