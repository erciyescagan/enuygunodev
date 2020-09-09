<?php


namespace App\api;


use App\Entity\Provider;
use Symfony\Component\HttpClient\HttpClient;

class Provider2Reader implements TodolistReader
{
    public function read( Provider $provider)
    {
        $tasks = array();

        //data çek
        $client = HttpClient::create();
        $response = $client->request('GET', $provider->getRequestURL());

        //görevlerin süresini zorluk ile çarparak gereken işgücünü buluyoruz
        //formatla
        foreach ($response->toArray() as $task => $taskdetail){

            $name = 'Business Task '.$task;

            $tasks[$task]['name'] = $name;
            $tasks[$task]['difficulty'] = $taskdetail[$name]['level'];
            $tasks[$task]['time'] = $taskdetail[$name]['estimated_duration'];
            $tasks[$task]['workhour'] = $taskdetail[$name]['level'] * $taskdetail[$name]['estimated_duration'];;

        }

        return $tasks;
    }
}