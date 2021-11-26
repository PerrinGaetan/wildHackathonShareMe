<?php

namespace App\Controller;

class SignUpController extends AbstractController
{

    public function index(): string
    {

        return $this->twig->render('Signup/index.html.twig');
    }
}
