<?php

namespace App;

interface ResourceControllerInterface
{
    public function all();
    public function show($id);
    public function create();
}