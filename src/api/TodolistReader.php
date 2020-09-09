<?php


namespace App\api;


use App\Entity\Provider;

interface TodolistReader
{
    public function read(Provider $provider);

}