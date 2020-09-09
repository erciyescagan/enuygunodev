<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Repository\AssignmentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\Expr\Join;

class ResultpageController extends AbstractController
{
    public function index(Request $request, AssignmentsRepository $assignments): Response
    {
        $projectID = $request->attributes->get('pid');

        $asgnQR = $assignments->createQueryBuilder("a")
            ->Select("d.name dname, t.name task, t.Time ttime, t.Difficulty tdifc")
            ->leftJoin("App\Entity\Developers","d",Join::WITH,"a.DevId = d.id")
            ->leftJoin("App\Entity\Tasks","t",Join::WITH,"a.taskId = t.id")
            ->where('a.ProjectId = :projectId')
            ->orderBy("t.id")
            ->setParameter('projectId', $projectID)
            ->getQuery();
        $asgnList = $asgnQR->getResult();

        //$asgnList = $assignments->findBy(['ProjectId' => $projectID], ['id' => 'DESC']);
        return $this->render('resultpage/index.html.twig', ['posts' => $asgnList]);
    }
}
