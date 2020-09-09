<?php

/*
Enuygun Ödevi için giriş sayfası
 */

namespace App\Controller;
use App\Entity\Assignments;

use App\Repository\CompanyRepository;
use App\Repository\DevelopersRepository;
use App\Repository\ProjectRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StartpageController extends AbstractController
{
    public function index(ProjectRepository $projects, CompanyRepository $companies): Response
    {
        $cmpList = $companies->findBy(['Status' => 'A']);
        $prjList = $projects->findBy(['CompanyId' => $cmpList[0]->getId()], ['id' => 'DESC']);
        return $this->render('startpage/index.html.twig',['projects' => $prjList, 'companies'=>$cmpList]);


    }

    public function assignDevs(Request $request, TasksRepository $tasks, DevelopersRepository $devs, EntityManagerInterface $entityManager, ProjectRepository $projects){


        $companyID = $request->attributes->get('cid');
        $projectID = $request->attributes->get('pid');

        //proje kontrol
        $project = $projects->find($projectID);
        if($project->getAssigned() == 'A'){
            return $this->redirectToRoute('resultpage', array('pid'=>$projectID));
        }

        //developerları getir
        $devQR = $devs->createQueryBuilder("d")
            ->Select()
            ->leftJoin("App\Entity\Staff","s",Join::WITH,"s.DevId = d.id")
            ->where('s.companyId = :companyID')
            ->andWhere( 'd.Status = :status')
            ->setParameter('status', 'A')
            ->setParameter('companyID', $companyID)
            ->getQuery();
        $devList = $devQR->getResult();

        //developerların günlük ürettiği toplam iş
        $sumDevPL = $devs->createQueryBuilder("d")
            ->Select("sum(d.powerlevel*d.dailyhours)")
            ->leftJoin("App\Entity\Staff","s",Join::WITH,"s.DevId = d.id")
            ->where('s.companyId = :companyID')
            ->andWhere( 'd.Status = :status')
            ->setParameter('status', 'A')
            ->setParameter('companyID', $companyID)
            ->getQuery();

        $dailywork = $sumDevPL->getResult()[0][1];

        //Projedeki toplam iş miktarı
        $sumTaskWH = $tasks->createQueryBuilder("t")
            ->Select("sum(t.WorkHours)")
            ->where('t.ProjectId = :projectID')
            ->setParameter('projectID', $projectID)
            ->getQuery();

        $totalWork = $sumTaskWH->getResult()[0][1];

        //Minimum yapılabilecek süre, yukarı yuvarlanıyor
        $mindays = ceil($totalWork/$dailywork);

        //developerların sayaçları
        $devs = array(); //iş saati
        $devspl = array(); //yetenek seviyesi
        foreach ( $devList as $dev) {
            $devs[$dev->getId()] = $dev->getDailyhours() * $mindays;
            $devspl[$dev->getId()] = $dev->getPowerlevel();
        }
        $max = array_keys($devspl, max($devspl))[0];//En büyük işin en iyi deve gitmesini garanti altına alıyoruz

        //işler, miktara göre ters sıra ile
        $taskList = $tasks->findBy(['ProjectId' => $projectID], ['WorkHours' => 'DESC']);

        //işlerin atanması
        foreach ($taskList as $key=> $task){

            //iş ataması
            $devs[$max] -= ($task->getWorkHours()/$devspl[$max]);
            $asEntity = new Assignments();
            $asEntity->setTaskId($task->getId());
            $asEntity->setDevId($max);
            $asEntity->setProjectId($projectID);

            //En çok iş gücü boşluğu olan kişiyi bul
            $max = array_keys($devs, max($devs))[0];

            $entityManager->persist($asEntity);
        }

        //project update
        $project->setAssigned('A');
        $project->setCalculatedTime($mindays);
        $entityManager->persist($project);

        $entityManager->flush();

        return $this->redirectToRoute('resultpage', array('pid'=>$projectID));

    }

    public function showResult(Request $request){


        $projectID = $request->attributes->get('pid');

        return $this->redirectToRoute('resultpage', array('pid'=>$projectID));
    }
}
