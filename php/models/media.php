<?php

/**
 * File: photos.php
 * Author: Théo Hurlimann
 * Date: 17.12.2019
 * Description: manage photos.
 * Version: 1.0
 */

require_once 'databaseConnection.php';

/**
 * Fonction permettant d'ajouter une photo à la base de donnée (PRIVATEMAIL, TEL)
 *
 * @param [Media] $u L'objet photo
 * @return bool true si ok, false si problème
 */
function Addmedia($media,$idPost)
{
    //Exemple go https://www.php.net/manual/fr/pdo.commit.php
    $database = UserDbConnection();
    $req = $database->prepare("INSERT INTO media(nomFichierMedia, typeMedia, creationDate, modificationDate, idPost_media) VALUES (:nomFichierMedia, :typeMedia, :creationDate,:modificationDate, :idPost_media);");
    $req->bindParam(":nomFichierMedia",$media->nomFichierMedia, PDO::PARAM_STR);
    $req->bindParam(":typeMedia",$media->typeMedia, PDO::PARAM_STR);
    $req->bindParam(":creationDate",$media->creationDate, PDO::PARAM_STR);
    $req->bindParam(":modificationDate",$media->modificationDate, PDO::PARAM_STR);
    $req->bindParam(":idPost_media",$idPost, PDO::PARAM_STR);
    if ($req->execute()) {
        return true;
    } else {
        return false;
    }
}


/**
 * Fonction permettant de récupérer une photo avec son Id
 *  
 * @param [int] $id id de la photo
 * @return Photo Rend une liste de Photo, NULL si problème
 */
/*function getMediaByIdPost($id)
{
    $arrPhotos = array();

    $database = UserDbConnection();
    $query = $database->prepare("SELECT idPhoto,location, latitude, longitude, altitude, tempsExposition, sensibiliteISO, distanceFocale, focale, dimension FROM photos WHERE idPhoto = :id LIMIT 1");
    if ($query->execute(
        array(
            ':id' => $id,
        )
    )) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($row); $i++) {

            $photo = new Media($row[$i]['idPhoto'], $row[$i]['location'], $row[$i]['latitude'], $row[$i]['longitude'], $row[$i]['altitude'], $row[$i]['tempsExposition'], $row[$i]['sensibiliteISO'], $row[$i]['distanceFocale'], $row[$i]['focale'], $row[$i]['dimension']);
            //$photo->id = $row['idPhoto'];
            array_push($arrPhotos, $photo);
        }
        return $arrPhotos;
    } else {
        return NULL;
    }
}
*/
/**
 * 
 * @return Bool Retourne True si le media a été validé sinon false
 */
function checkMediaSize($size, $max,$i)
{
    // Check size of one image
    if ($size <= $max && $size != 0) {

        return false;
    }
    return true;
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function changeMediaName()
{

    return uniqid() ."-". date('YmdHis') ;
    //return $i; 
}
/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function checkMediaFormat($imageFileType)
{
    $extensions = array('.png', '.gif', '.jpg', '.jpeg'); 
    if(!in_array($imageFileType, $extensions)) 
    {
        
        return false;
    }
    /*
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {

        return false;
    }*/
    return true;
}



/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function moveMediaToFolder($target_file,$i)
{
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
        return true;
    } else {
        return false;
    }
}

function checkMediaFake($i)
{
    
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);
    if ($check !== false) {
        return false;
    } else {
        return true;
    }
}
