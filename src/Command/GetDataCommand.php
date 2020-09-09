<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;


use App\api\ReaderFactory;
use App\Entity\Project;
use App\Entity\Tasks;
use App\Repository\ProjectRepository;
use App\Repository\ProviderRepository;
use App\Repository\CompanyRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function Symfony\Component\String\u;

/**

 */
class GetDataCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:get-data';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $project;
    private $provider;
    private $company;
    private $validator;

    public function __construct(EntityManagerInterface $em, Validator $validator, ProjectRepository $project, ProviderRepository $provider, CompanyRepository $company)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->project = $project;
        $this->provider = $provider;
        $this->company = $company;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Gets to-do list data from providers')
            ->setHelp($this->getCommandHelp())
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            ->addArgument('projectname', InputArgument::REQUIRED, 'The name of the new project')
            ->addArgument('providername', InputArgument::REQUIRED, 'To-do list provider for the project')
            ->addArgument('companyname', InputArgument::REQUIRED, 'The company project will be assigned')
            //->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
        ;
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('projectname') && null !== $input->getArgument('providername') && null !== $input->getArgument('companyname')) {
            return;
        }

        $this->io->title('Get Data Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:get-data projectname providername companyname',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        // Ask for the projectname if it's not defined
        $projectname = $input->getArgument('projectname');
        if (null !== $projectname) {
            $this->io->text(' > <info>Project Name</info>: '.$projectname);
        } else {
            $projectname = $this->io->ask('Project Name', null, [$this->validator, 'validateProjectname']);
            $input->setArgument('projectname', $projectname);
        }

        // Ask for the providername if it's not defined
        $providername = $input->getArgument('providername');
        if (null !== $providername) {
            $this->io->text(' > <info>Provider Name</info>: '.u('*')->repeat(u($providername)->length()));
        } else {
            $providername = $this->io->ask('Provider Name', 'Provider1', [$this->validator, 'validateProvidername']);
            $input->setArgument('providername', $providername);
        }

        // Ask for the companyname if it's not defined
        $companyname = $input->getArgument('companyname');
        if (null !== $companyname) {
            $this->io->text(' > <info>Company Name</info>: '.$companyname);
        } else {
            $companyname = $this->io->ask('Company Name', null, [$this->validator, 'validateCompanyname']);
            $input->setArgument('companyname', $companyname);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('get-data-command');

        $projectname = $input->getArgument('projectname');
        $providername = $input->getArgument('providername');
        $companyname = $input->getArgument('companyname');

        //company var mı
        $company= $this->company->findOneBy(['name' => $companyname]);
        if (null == $company) {
            throw new RuntimeException(sprintf('There is no company registered with the "%s" company name.', $companyname));
        }

        //provider tanındı mı
        $provider = $this->provider->findOneBy(['name' => $providername]);
        if ( null == $provider ) {
            throw new RuntimeException(sprintf('There is no provider registered with the "%s" x name.', $providername));
        }


        //proje datası önceden var mı
        $project = $this->project->findOneBy(['name' => $projectname]);
        if (null !== $project) {
            throw new RuntimeException(sprintf('There is already a project registered with the "%s" project name.', $projectname));
        }

        $reader = ReaderFactory::createTodolistReader($providername);
        $data = $reader->read($provider);

        //yeni proje oluştur
        $prjEntity = new Project();
        $prjEntity->setName($projectname);
        $prjEntity->setProviderId($provider->getId());
        $prjEntity->setCompanyId($company->getId());
        $prjEntity->setStatus('A');
        $this->entityManager->persist($prjEntity);


        //Taskları gir
        foreach ($data as $key=> $task){

            $line = new Tasks();
            $line->setProjectId($prjEntity->getId());
            $line->setName($task['name']);
            $line->setDifficulty($task['difficulty']);
            $line->setTime($task['time']);
            $line->setWorkHours($task['workhour']);

            $this->entityManager->persist($line);

        }
        $this->entityManager->flush();

        $this->io->success(sprintf('Projects&Tasks was successfully created: %S (%s)', $projectname, $providername));

        $event = $stopwatch->stop('get-data-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $prjEntity->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return 0;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command creates new users and saves them in the database:

  <info>php %command.full_name%</info> <comment>username password email</comment>

By default the command creates regular users. To create administrator users,
add the <comment>--admin</comment> option:

  <info>php %command.full_name%</info> username password email <comment>--admin</comment>

If you omit any of the three required arguments, the command will ask you to
provide the missing values:

  # command will ask you for the email
  <info>php %command.full_name%</info> <comment>username password</comment>

  # command will ask you for the email and password
  <info>php %command.full_name%</info> <comment>username</comment>

  # command will ask you for all arguments
  <info>php %command.full_name%</info>

HELP;
    }
}
