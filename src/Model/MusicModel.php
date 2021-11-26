<?php

namespace App\Model;

use PDO;

class MusicModel extends AbstractManager
{
    public function addMusic($newAlbum)
    {
        $album = array_map('trim', $newAlbum);
        $album = array_map('htmlentities', $newAlbum);
        $query = "INSERT INTO `music`(`album`, `artist`) VALUE (:album, :artist);";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":artist" , $album['artist'], PDO::PARAM_STR);
        $statement->bindValue(":album" , $album['album'], PDO::PARAM_STR);
        $statement->execute();
        return $album;
    }

    public function selectMusicId($newAlbum)
    {
        $queryMusicId = "SELECT `id` FROM `music` WHERE `album` = '$newAlbum' ;";
        $statement1 = $this->pdo->query($queryMusicId);
        return intval($statement1->fetch());
    }
}