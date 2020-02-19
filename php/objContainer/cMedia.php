<?php

/**
 * La classe Media contient les informations complémentaire à une photo
 *  Ex: idMedia, nomFichierMedia, typeMedia
 */
class cMedia{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InidMedia = -1, $InNomFichierMediaionPhoto = "", $InTypeMedia = "", $InCreationDate = "", $InModificationDate = ""){
        $this->idMedia = $InidMedia;
        $this->nomFichierMedia = $InNomFichierMediaionPhoto;
        $this->typeMedia = $InTypeMedia;
        $this->creationDate = $InCreationDate;
        $this->modificationDate = $InModificationDate;
    }
    /** @var [int] Id unique du media */
    public $idMedia;

    /** @var [string] nom du media */
    public $nomFichierMedia;

    /** @var [string] type de media */
    public $typeMedia;

    /** @var [string] Date de création du media */
    public $creationDate;
    
    /** @var [string] Date de modification du media */
    public $modificationDate;
}

?>