<?php

namespace App\Controllers;

use App\User;
use App\UserRepository;

class HomeController
{
    public function index()
    {
        $repo = new UserRepository();
        $users = $repo->all();

        include(__DIR__ . ('/../Views/home.php'));
    }
}
