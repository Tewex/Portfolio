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
function Addmedia($media, $idPost)
{
    $database = UserDbConnection();

    try {
        $req = $database->prepare("INSERT INTO media(nomFichierMedia, typeMedia, creationDate, modificationDate, idPost_media) VALUES (:nomFichierMedia, :typeMedia, :creationDate,:modificationDate, :idPost_media);");
        $req->bindParam(":nomFichierMedia", $media->nomFichierMedia, PDO::PARAM_STR);
        $req->bindParam(":typeMedia", $media->typeMedia, PDO::PARAM_STR);
        $req->bindParam(":creationDate", $media->creationDate, PDO::PARAM_STR);
        $req->bindParam(":modificationDate", $media->modificationDate, PDO::PARAM_STR);
        $req->bindParam(":idPost_media", $idPost, PDO::PARAM_STR);
        $req->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function deleteMediaById($id)
{
    $sql = "DELETE FROM portfolio.media WHERE idMedia = :id";
    try {
        $dbh = UserDbConnection();
        $stmt = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL));
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Fonction permettant de récupérer une photo avec son Id
 *  
 * @param [int] $id id de la photo
 * @return Photo Rend une liste de Photo, NULL si problème
 */
function getMediaByIdPost($id)
{
    $arrPhotos = array();

    $database = UserDbConnection();
    $query = $database->prepare("SELECT idMedia, nomFichierMedia, typeMedia, creationDate, ModificationDate, idPost_Media FROM media WHERE idPost_Media = :id");
    $query->bindParam(":id", $id, PDO::PARAM_STR);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($row); $i++) {

            $photo = new cMedia($row[$i]['idMedia'], $row[$i]['nomFichierMedia'], $row[$i]['typeMedia'], $row[$i]['creationDate'], $row[$i]['ModificationDate'], $row[$i]['idPost_Media']);
            //$photo->id = $row['idPhoto'];
            array_push($arrPhotos, $photo);
        }
        return $arrPhotos;
    } else {
        return NULL;
    }
}

/**
 * 
 * @return Bool Retourne True si le media a été validé sinon false
 */
function checkMediaSize($size, $max, $i)
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

    return uniqid() . "-" . date('YmdHis');
    //return $i; 
}


function getFormatMedia($type)
{
    $arrType = explode("/", $type);

    if ($arrType[0] != "video" && $arrType[0] != "image" && $arrType[0] != "audio") {
        return null;
    }
    return $arrType[0];
}
function getRealExtensionMedia($type)
{
    $arrType = explode("/", $type);
    return $arrType[1];
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function moveMediaToFolder($target_file, $i)
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

function getMediaType($monMedia)
{
    return "image";
    $echo  = filetype("photoUploads10122019-DJI_0986");
    $f = finfo_open("." . CHEMINMEDIA . $monMedia->nomFichierMedia);
    $finfo_open = "." . CHEMINMEDIA . $monMedia->nomFichierMedia;
    //$finfo = finfo_open(CHEMINPHOTO . $monMedia->nomFichierMedia); // Retourne le type mime à l'extension mimetype
    $fileType = mime_content_type($finfo_open);

    //$fileType = FILEINFO_MIME_TYPE(CHEMINPHOTO . $monMedia->nomFichierMedia);

    $extensionsImage = array('.png', '.gif', '.jpg', '.jpeg');
    if (strpos($fileType, 'image/') === true) {
        return "image";
    } elseif (strpos($fileType, 'video/') === true) {
        return "video";
    } elseif (strpos($fileType, 'audio/') === true) {
        return "audio";
    }
}

function deletefilesServer($arrMedia)
{

    foreach ($arrMedia as $media) {
        deleteFileServerByName($media->nomFichierMedia);
    }
}
function deleteFileServerByName($name)
{
    if (unlink(CHEMINMEDIA . $name)) {
        return true;
    } else {
        return false;
    }
}
