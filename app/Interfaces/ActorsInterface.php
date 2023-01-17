<?php

namespace App\Interfaces;

interface ActorsInterface
{
    public function getAll();

    public function getById($id);
}
