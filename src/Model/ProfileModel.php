<?php

namespace App\Model;

use PDO;

class ProfileModel extends AbstractManager
{
    public function addAlbum($album)
    {
        $musicModel = new MusicModel();
        $newAlbum = array_map('trim', $album);
        $newAlbum = array_map('htmlentities', $newAlbum);
        if (!$musicModel->selectMusicId($album['album'])) {
            $add = $musicModel->addMusic($newAlbum);
        }
        $musicId = intval($musicModel->selectMusicId($album['album']));

        $queryFK = "INSERT INTO `userList` (`userId`, `musicId`) VALUE ( :userId, :musicId);";
        $statement2 = $this->pdo->prepare($queryFK);
        $statement2->bindValue(":userId", $musicId, PDO::PARAM_INT);
        $statement2->bindValue(":musicId", $musicId, PDO::PARAM_INT);
        $statement2->execute();
    }

    
}
