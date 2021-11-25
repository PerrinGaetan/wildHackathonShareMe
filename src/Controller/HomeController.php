<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\SearchManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_GET)) {
            $match = new SearchManager();
            $result = $match->readUser($_GET);
            var_dump($result);
            return $this->twig->render('Home/homepage.html.twig', ['matches' => $result]);
        }
        return $this->twig->render('Home/homepage.html.twig');
    }
    public function show()
    {
        $match = new SearchManager();
        $match->selectAllMatches($_GET);
        return $this->twig->render('Home/profile.html.twig');
    }
}
