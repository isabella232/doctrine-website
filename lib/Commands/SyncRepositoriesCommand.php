<?php

declare(strict_types=1);

namespace Doctrine\Website\Commands;

use Doctrine\Website\Projects\ProjectDataRepository;
use Doctrine\Website\Projects\ProjectGitSyncer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

class SyncRepositoriesCommand extends Command
{
    /** @var string|null */
    protected static $defaultName = 'sync-repositories';

    /** @var ProjectDataRepository */
    private $projectDataRepository;

    /** @var ProjectGitSyncer */
    private $projectGitSyncer;

    public function __construct(
        ProjectDataRepository $projectDataRepository,
        ProjectGitSyncer $projectGitSyncer
    ) {
        $this->projectDataRepository = $projectDataRepository;
        $this->projectGitSyncer      = $projectGitSyncer;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Initialize or update all project repositories.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repositoryNames = $this->projectDataRepository->getProjectRepositoryNames();

        foreach ($repositoryNames as $repositoryName) {
            if ($this->projectGitSyncer->isRepositoryInitialized($repositoryName)) {
                $output->writeln(sprintf('Updating <info>%s</info>', $repositoryName));

                $this->projectGitSyncer->syncRepository($repositoryName);

                continue;
            }

            $output->writeln(sprintf('Initializing <info>%s</info>', $repositoryName));

            $this->projectGitSyncer->initRepository($repositoryName);
        }

        return 0;
    }
}
