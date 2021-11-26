<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class ApiController extends AbstractController
{
    /**
     * Display home page
     *
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(array $list): array
    {
        $client = HttpClient::create();
        $album = $client->request(
            'GET',
            'https://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=704b3bb51d87a99023fabedf451720ca&artist='
            . $list['artist']
            . '&album='
            . $list['album']
            . '&format=json'
        );
        $result = $album->toArray();

        return $result;
    }
}