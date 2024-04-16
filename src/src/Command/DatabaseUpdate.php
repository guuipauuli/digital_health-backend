<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(name: 'app:database-update')]
class DatabaseUpdate extends Command
{
    private EntityManagerInterface $entityManager;

    private KernelInterface $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $output->writeln('Inicialização Atualização Banco de Dados');

        $params = (object)$this->entityManager->getConnection()->getParams();
        $searchPath = "--searchPath='" . $this->kernel->getProjectDir() . "/tools/liquibase'";
        $driver = "--driver=org.postgresql.Driver";
        $changeLog = "--changelog-file=changelog.xml";
        $url = "--url=jdbc:postgresql://{$params->host}:{$params->port}/{$params->dbname}";
        $auth = "--username={$params->user} --password={$params->password}";
        exec("liquibase {$searchPath} {$driver} {$changeLog} {$url} {$auth} update");

        $output->writeln('Banco de dados Atualizado');

        return Command::SUCCESS;
    }
}
