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
 * @param [Photo] $u L'objet photo
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

            $photo = new Photo($row[$i]['idPhoto'], $row[$i]['location'], $row[$i]['latitude'], $row[$i]['longitude'], $row[$i]['altitude'], $row[$i]['tempsExposition'], $row[$i]['sensibiliteISO'], $row[$i]['distanceFocale'], $row[$i]['focale'], $row[$i]['dimension']);
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
function checkMediaSize()
{
    // Check size of one image
    if ($_FILES["fileToUpload"]["size"][0] <= 3000000) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        return false;
    }
    return true;
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function checkMediaName($target_file)
{

    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        return false;
    }
    return true;
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function checkMediaFormat($imageFileType)
{

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") 
    {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        return false;
    }
    return true;
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function checkImageFake($imageFileType)
{

   
   if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][0]);
    if ($check !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * 
 * @return Bool Retourne True si le nom du media a été validé sinon false
 */
function moveMedia($imageFileType)
{

   
   if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][0]);
    if ($check !== false) {
        return true;
    } else {
        return false;
    }
    }
}