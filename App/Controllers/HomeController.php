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
        /*
        $editUserID = ($request->input('id')) ? $request->input('id') : null;
        $data = ['users' => $users, 'editUserID' => $editUserID];
        if ($editUserID) {
            $user = new User($editUserID);
            $user->load();
            $data['editUser'] = $user->toArray();
        } */

        return view('index', ['users' => $users]);
    }
}