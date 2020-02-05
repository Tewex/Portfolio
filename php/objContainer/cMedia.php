<?php

/**
 * La classe Media contient les informations complémentaire à une photo
 *  Ex: idMedia, nomFichierMedia, typeMedia
 */
class Media{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InidMedia = -1, $InNomFichierMediaionPhoto = "", $InTypeMedia = ""){
        $this->idMedia = $InidMedia;
        $this->nomFichierMedia = $InNomFichierMediaionPhoto;
        $this->typeMedia = $InTypeMedia;

    }
    /** @var [int] Id unique du media */
    public $idMedia;

    /** @var [string] nom du media */
    public $nomFichierMedia;

    /** @var [string] type de media */
    public $typeMedia;

}

?>