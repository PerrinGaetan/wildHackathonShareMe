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
        $searchManager = new SearchManager();
        $mostPopular = $searchManager->selectTopMusic();

        if (!empty($_GET)) {
            $result = $searchManager->readUser($_GET);
            return $this->twig->render(
                'Home/homepage.html.twig',
                ['matches' => $result,
                'mostPopular' => $mostPopular]
            );
        }

        return $this->twig->render('Home/homepage.html.twig', ['mostPopular' => $mostPopular]);
    }
    public function show()
    {
        return $this->twig->render('Home/profile.html.twig');
    }
}
