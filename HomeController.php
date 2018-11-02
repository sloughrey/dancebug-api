<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class HomeController extends Controller
{
    public function index(Request $request)
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