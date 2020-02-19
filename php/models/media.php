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
function Addmedia($p)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO photos (location, latitude, longitude, altitude, tempsExposition, sensibiliteISO, distanceFocale, focale, dimension) VALUES (:location, :latitude, :longitude, :altitude, :tempsExposition, :sensibiliteISO, :distanceFocale, :focale, :dimension);");
    if ($query->execute(array(
        ':location' => $p->location,
        ':latitude' => $p->latitude,
        ':longitude' => $p->longitude,
        ':altitude' => $p->altitude,
        ':tempsExposition' => $p->tempsExposition,
        ':sensibiliteISO' => $p->sensibiliteISO,
        ':distanceFocale' => $p->distanceFocale,
        ':focale' => $p->focale,
        ':dimension' => $p->dimension,
    ))) {
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
function getMediaById($id)
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

/**
 * 
 * @return Bool Retourne True si le media a été validé sinon false
 */
function checkMediaSize($max,$i)
{
    // Check size of one image
    if ($_FILES["fileToUpload"]["size"][$i] <= $max) {

        return false;
    }
    return true;
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function changeMediaName($i)
{

    return date('YmdHis') . $i;
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
