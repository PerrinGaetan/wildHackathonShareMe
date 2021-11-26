<?php

namespace App\Controller;

use App\Model\SearchManager;

class ProfileController extends AbstractController
{
    public function displayListById()
    {
        if (!empty($_GET['id'])) {
            $_GET['id'] = intval(trim(htmlentities($_GET['id'])));
            $api = new ApiController();
            $listUserModel = new SearchManager();
            $lists = $listUserModel->getListUser($_GET['id']);
            $informations = [];
            foreach ($lists as $list) {
                $informations[] = $api->index($list);
            }

            // var_dump($lists, $informations);
            // die();
            return $this->twig->render('Home/profile.html.twig', ['results' => $informations]);
        }
        return $this->twig->render('Home/profile.html.twig');
    }
}
