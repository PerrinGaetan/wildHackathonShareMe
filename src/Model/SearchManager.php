<?php

namespace App\Model;

use PDO;

class SearchManager extends AbstractManager
{
    public function selectAllMatches(array $data)
    {
        if ($data['album']) {
            $query = "SELECT * FROM `music` WHERE `album` LIKE :album AND `artist` LIKE :artist;";
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(":artist", $data['artist'], PDO::PARAM_STR);
            $statement->bindValue(":album", $data['album'], PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetchAll();
        }

        $query = "SELECT * FROM `music` WHERE `album` LIKE :album OR `artist` LIKE :artist;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":artist", $data['artist'], PDO::PARAM_STR);
        $statement->bindValue(":album", $data['album'], PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function readUser(array $data)
    {
        $musicId = $this->selectAllMatches($data);

        $matchesAll = [];
        foreach ($musicId as $id) {
            $id = intval($id['id']);
            $query = "SELECT * FROM `user` JOIN `userList` ON userList.musicId = $id AND userList.userId = user.id;";
            $statement = $this->pdo->query($query);
            $matchesAll[] = $statement->fetchAll();
        }

        $matchesFinal = [];
        foreach ($matchesAll as $matches) {
            foreach ($matches as $match) {
                if (!in_array($match['id'], $matchesFinal)) {
                    $matchesFinal[$match['id']] = $match;
                }
            }
        }

        return $matchesFinal;
    }

    public function selectMorePopular()
    {
        $query = "SELECT musicId, count(musicId) as total FROM userList  group by musicId ORDER BY total desc LIMIT 6;";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll();
    }

    public function selectTopMusic()
    {
        $top = $this->selectMorePopular();
        $topPopular = [];
        foreach ($top as $musicId) {
            $musicId = intval($musicId['musicId']);
            $query = "SELECT * FROM music WHERE id=$musicId";
            $statement = $this->pdo->query($query);
            $topPopular[] = $statement->fetchAll();
        }
        return $topPopular;
    }

    public function getListUser(int $id)
    {
        $query = "SELECT * FROM music JOIN userList ON userId = :id AND userList.musicId = music.id; ";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getAllUserId()
    {
        $query = "SELECT * FROM `user`;";
        return $this->pdo->query($query)->fetchAll();
    }
}
