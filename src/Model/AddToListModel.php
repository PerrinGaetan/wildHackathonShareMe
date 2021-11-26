<?php

namespace App\Model;

use PDO;

class AddToListModel extends AbstractManager
{
    public function addToMyList($data)
    {
        $data = array_map('trim', $data);
        $data = array_map('htmlentities', $data);
        if (empty($data['artist']) || empty($data['album'])) {
            $errors = 'informations manquantes';
            return $errors;
        }
        $album = $data['album'];
        $query = "SELECT `id` FROM `music` WHERE `album` = $album;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':album', $album, PDO::PARAM_STR);
        $statement->execute();
        $musicId = intval($statement->fetch());

        $query = "INSERT INTO `music` (`artist`, `album`) VALUE (:artist, :album);";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':artist', $data['artist'], PDO::PARAM_STR);
        $statement->bindValue(':album', $data['album'], PDO::PARAM_STR);
        $statement->execute();
        $query = "INSERT INTO `userList` (`userId`, `musicId`) VALUE (:userId, :musicId);";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $data['userId'], PDO::PARAM_STR);
        $statement->bindValue(':musicId', $musicId, PDO::PARAM_STR);
        $statement->execute();
    }
}
