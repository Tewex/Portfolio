<?php

/**
 * La classe cMedia contient les informations complémentaire à une photo
 *  Ex: Nom d'article, prix, description, etc.
 */
class Post{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InidPost = -1, $InCommentaire = "", $InCreationDate = "", $InModificationDate = ""){
        $this->idPost = $InidPost;
        $this->commentaire = $InCommentaire;
        $this->creationDate = $InCreationDate;
        $this->modificationDate = $InModificationDate;
    }
    /** @var [int] Id unique de la photo */
    public $idPost;

    /** @var [string] Emplacement du fichier (photo) */
    public $commentaire;

    /** @var [string] Latitude de la photo */
    public $creationDate;
    
    /** @var [string] Latitude de la photo */
    public $modificationDate;
}

?>