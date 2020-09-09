<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Repository\AssignmentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultpageController extends AbstractController
{
    public function index(Request $request, AssignmentsRepository $assignments): Response
    {
        $asgnList = $assignments->findBy(['ProjectId' => 3], ['id' => 'DESC']);
        return $this->render('resultpage/index.html.twig', ['posts' => $asgnList]);
    }
}
