<?php

/**
 * La classe cMedia contient les informations complémentaire à une photo
 *  Ex: Nom d'article, prix, description, etc.
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
    /** @var [int] Id unique de la photo */
    public $idMedia;

    /** @var [string] Emplacement du fichier (photo) */
    public $nomFichierMedia;

    /** @var [string] Latitude de la photo */
    public $typeMedia;

}

?>