<?php

/**
 * La classe Post contient les informations complémentaire à un post
 *  Ex: idPost, commentaire, creationDate, modificationDate.
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
    /** @var [int] Id unique de la post */
    public $idPost;

    /** @var [string] Commentaire du post */
    public $commentaire;

    /** @var [string] Date de création du post */
    public $creationDate;
    
    /** @var [string] Date de modification du post */
    public $modificationDate;
}

?>