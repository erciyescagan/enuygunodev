<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;

class StartpageController
{
    public function index(): Response
    {
        $totalwork = 0;
        $tasks = array();
        $devs = array();
        $assignments = array();

        //data çek
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa');


        //görevlerin süresini zorluk ile çarparak gereken işgücünü buluyoruz
        //Toplam yapılacak iş miktarını hesapla
        foreach ($response->toArray() as $task => $taskdetail){

            $workhour = $taskdetail['sure']*$taskdetail['zorluk'];
            $totalwork += $workhour;
            $tasks[$task] = $workhour;
        }
        rsort($tasks);

        //todo : DB üzerinden gelecek şekilde değişecek

        //developerlar saatte 15 işgücü tüketebiliyor,
        //buradan minimum süreyi hesaplayıp, yukarıya yuvarlıyoruz.
        $mindays = ceil($totalwork/(9*15));

        //Hesapladığımız süre içinde devlerin tüketebilecekleri iş miktarları
        $devs[1] = 1*9*$mindays;
        $devs[2] = 2*9*$mindays;
        $devs[3] = 3*9*$mindays;
        $devs[4] = 4*9*$mindays;
        $devs[5] = 5*9*$mindays;


        //Algoritma en büyük işi en çok boş zamanı olan kişiye atıyor
        foreach ($tasks as $key=> $task){
            //En çok boş zamanı olan kişiyi bul
            $max = array_keys($devs, max($devs))[0];

            //iş ataması
            $devs[$max] -= $task;
            $assignments[$max][] = 'task'. $key . '_' . $task;

        }

        var_dump($assignments);
        var_dump($devs);

        return new Response(
            '<html><body>Total WorkHours: '.$totalwork.'<br>
                    Total Tasks: '.count($response->toArray()).'<br>
                    Theoritical Min Days: '.ceil($totalwork/(9*15)).
            '</body></html>'
        );
    }
}
