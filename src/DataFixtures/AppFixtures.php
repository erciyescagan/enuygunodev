<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Developers;
use App\Entity\Params;
use App\Entity\Provider;
use App\Entity\Staff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\String\Slugger\SluggerInterface;
use http\Env;
use function Symfony\Component\String\u;

class AppFixtures extends Fixture
{
    //private $passwordEncoder;
    //private $slugger;

    public function __construct()//UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger)
    {
        //$this->passwordEncoder = $passwordEncoder;
        //$this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCompany($manager);
        $this->loadDevelopers($manager);
        $this->loadProviders($manager);
        $this->loadStaff($manager);
        $this->loadParams($manager);
    }

    private function loadCompany(ObjectManager $manager): void
    {
        foreach ($this->getCompanyData() as [$name,$status]) {
            $company = new Company();
            $company->setName($name);
            $company->setStatus($status);

            $manager->persist($company);
        }

        $manager->flush();
    }
    private function loadDevelopers(ObjectManager $manager): void
    {
        foreach ($this->getDeveloperData() as [$name,$powerlevel,$dwh,$status]) {
            $developer= new Developers();
            $developer->setName($name);
            $developer->setPowerlevel($powerlevel);
            $developer->setDailyhours($dwh);
            $developer->setStatus($status);

            $manager->persist($developer);
        }

        $manager->flush();
    }
    private function loadStaff(ObjectManager $manager): void
    {
        foreach ($this->getStaffData() as [$companyId,$devId]) {
            $staff = new Staff();
            $staff->setCompanyId($companyId);
            $staff->setDevId($devId);

            $manager->persist($staff);
        }

        $manager->flush();
    }
    private function loadProviders(ObjectManager $manager): void
    {
        foreach ($this->getProvidersData() as [$name,$requestURL,$status]) {
            $provider = new Provider();
            $provider->setName($name);
            $provider->setRequestURL($requestURL);
            $provider->setStatus($status);

            $manager->persist($provider);
        }

        $manager->flush();
    }
    private function loadParams(ObjectManager $manager): void
    {
        foreach ($this->getParamData() as [$name,$value,$companyId]) {
            $param = new Params();
            $param->setKey($name);
            $param->setVal($value);
            $param->setCompanyId($companyId);

            $manager->persist($param);
        }

        $manager->flush();
    }

    private function getCompanyData(): array
    {
        return [
            // $userData = [$name, $status];
            ['Enuygun', 'A'],
            ['Ubit', 'P'],
        ];
    }
    private function getDeveloperData(): array
    {
        return [
            // $userData = [$name,$powerlevel,$dwh,$status];
            ['Developer1', 1, 9, 'A'],
            ['Developer2', 2, 9, 'A'],
            ['Developer3', 3, 9, 'A'],
            ['Developer4', 4, 9, 'A'],
            ['Developer5', 5, 9, 'A'],
            ['Goku', 9001, 24, 'P'],
        ];
    }
    private function getStaffData(): array
    {
        return [
            // $userData = [$companyId,$devId];
            ['1', '1'],
            ['1', '2'],
            ['1', '3'],
            ['1', '4'],
            ['1', '5'],
        ];
    }
    private function getProvidersData(): array
    {
        return [
            // $userData = [$name,$requestURL,$status];
            ['Provider1', 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa' ,'A'],
            ['Provider2', 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7', 'A'],
            ['Provider3', 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7', 'P'],

        ];
    }
    private function getParamData(): array
    {
        return [
            // $userData = [$name, $val,$companyId];
            ['WeekLength', '5', 1],
        ];
    }

}
