<?php

namespace App\Interfaces;

interface Actors
{
    public function getAll();

    public function getById($id);
}
