<?php
    /**
     * File: database.php
     * Author: Théo Hurlimann
     * Date: 17.12.2019
     * Description: manage Database.
     * Version: 1.0
     */

    require_once 'databaseInformations.php';

function UserDbConnection()
{
    static $dbb = null;

    if ($dbb === null) {
        try {
            $dbb = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE_NAME, PSEUDO, PWD, array('charset' => 'utf8'));
            $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    return $dbb;
}

?>