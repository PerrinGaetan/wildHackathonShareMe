<?php

namespace App\Model;

use PDO;

class HomepageManager extends AbstractManager
{
    public function selectAllByUsers()
    {
        $statement = $this->pdo->query(
            "SELECT `name`, codepostal, ville, artist, album, cover 
            FROM `user` 
            JOIN music on `user`.id=user_id"
        );
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllNames()
    {
        $statement = $this->pdo->query(
            "SELECT `name` 
            FROM `user`"
        );
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
