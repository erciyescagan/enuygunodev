<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Entity\Params;
use App\Entity\Project;
use App\Repository\AssignmentsRepository;
use App\Repository\ParamsRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\Expr\Join;

class ResultpageController extends AbstractController
{
    public function index(Request $request, AssignmentsRepository $assignments, ProjectRepository $projects, ParamsRepository $param): Response
    {
        $projectID = $request->attributes->get('pid');
        $project = $projects->find($projectID);
        $projectName = $project->getName();

        //Projenin süre hesabı
        $wlength = $param->findOneBy(['Key' => 'WeekLength'])->getVal();
        $time = $project->getCalculatedTime();
        $week = floor($time / $wlength) ;
        $day = $time%$wlength;

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
        return $this->render('resultpage/index.html.twig', ['posts' => $asgnList, 'projectname'=>$projectName, "projectweek"=>$week, "projectday"=>$day]);
    }
}
