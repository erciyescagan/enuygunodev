<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StartpageController extends AbstractController
{
    public function index(ProjectRepository $projects): Response
    {
        $prjList = $projects->findBy(['CompanyId' => 1], ['id' => 'DESC']);
        return $this->render('startpage/index.html.twig',['projects' => $prjList]);
    }

    public function assignDevs(){

    }

    public function gotoAssignmets(){

    }
}
