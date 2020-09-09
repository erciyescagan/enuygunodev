<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Repository\AssignmentsRepository;
use App\Repository\ParamsRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\Expr\Join;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ComboChart;

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
            ->Select("d.id devid, d.name dname, t.name task, t.Time ttime, t.Difficulty tdifc, t.WorkHours wh, d.powerlevel pl")
            ->leftJoin("App\Entity\Developers","d",Join::WITH,"a.DevId = d.id")
            ->leftJoin("App\Entity\Tasks","t",Join::WITH,"a.taskId = t.id")
            ->where('a.ProjectId = :projectId')
            ->orderBy("d.id,t.id")
            ->setParameter('projectId', $projectID)
            ->getQuery();
        $asgnList = $asgnQR->getResult();

        //var_dump($asgnList);exit;

        $data = array();
        $data[0] = ['Developers', 'Work Hours', 'Tasks'];
        foreach ($asgnList as $asgn){
            if(!isset($data[$asgn['devid']])){
                $data[$asgn['devid']][] = $asgn['dname'];
                $data[$asgn['devid']][] += ($asgn['wh']/$asgn['pl']);
                $data[$asgn['devid']][] += 1;
            }
            else{
                $data[$asgn['devid']][1] += ($asgn['wh']/$asgn['pl']);
                $data[$asgn['devid']][2] += 1;
            }

        }

        //var_dump($data);exit;

        $combo = new ComboChart();
        $combo->getData()->setArrayToDataTable($data);
        $combo->getOptions()->setTitle('Distribution of Work');
        $combo->getOptions()->getVAxis()->setTitle('Work Hours');
        $combo->getOptions()->getVAxis()->setMinValue(0);
        $combo->getOptions()->getHAxis()->setTitle('Developers');
        $combo->getOptions()->setSeriesType('bars');


        $series = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $series->setType('line');
        $combo->getOptions()->setSeries([1 => $series]);

        $combo->getOptions()->setWidth(900);
        $combo->getOptions()->setHeight(500);

        //$asgnList = $assignments->findBy(['ProjectId' => $projectID], ['id' => 'DESC']);
        return $this->render('resultpage/index.html.twig', ['posts' => $asgnList, 'projectname'=>$projectName, "projectweek"=>$week, "projectday"=>$day, 'combochart'=>$combo]);
    }
}
