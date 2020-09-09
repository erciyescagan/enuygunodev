<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ResultpageController extends AbstractController
{
    public function index(TasksRepository $posts): Response
    {
        $authorPosts = $posts->findBy(['ProjectId' => 3], ['id' => 'DESC']);
        return $this->render('resultpage/index.html.twig', ['posts' => $authorPosts]);
    }
}
