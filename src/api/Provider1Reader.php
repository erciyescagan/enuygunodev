<?php


namespace App\api;


use App\Entity\Provider;
use Symfony\Component\HttpClient\HttpClient;

class Provider1Reader implements TodolistReader
{
    public function read(Provider $provider)
    {
        $tasks = array();

        //data çek
        $client = HttpClient::create();
        $response = $client->request('GET', $provider->getRequestURL());

        //görevlerin süresini zorluk ile çarparak gereken işgücünü buluyoruz
        //formatla
        foreach ($response->toArray() as $task => $taskdetail){

            $tasks[$task]['name'] = $taskdetail['id'];
            $tasks[$task]['difficulty'] = $taskdetail['zorluk'];
            $tasks[$task]['time'] = $taskdetail['sure'];
            $tasks[$task]['workhour'] = $taskdetail['sure'] * $taskdetail['zorluk'];
        }

        return $tasks;
    }
}