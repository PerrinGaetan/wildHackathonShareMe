<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\HomepageManager;
use Symfony\Component\HttpClient\HttpClient;

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
        $client = HttpClient::create();
        $nirvana = $client->request('GET', 'https://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=704b3bb51d87a99023fabedf451720ca&artist=skalpel&album=skalpel&format=json');
        $result = $nirvana->toArray();
        $homeModel = new HomepageManager;
        $allUsers = $homeModel->selectAllByUsers();
        return $this->twig->render('Home/homepage.html.twig', ['result' => $result, 'users' => $allUsers]);
    }


    public function show()
    {
        return $this->twig->render('Home/profile.html.twig');
    }
}
