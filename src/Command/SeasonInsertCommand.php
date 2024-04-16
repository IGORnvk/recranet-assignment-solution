<?php

namespace App\Command;

use App\Repository\SeasonRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'seasons-insert',
    description: 'Inserts all available Eredivisie seasons into the database',
)]
class SeasonInsertCommand extends Command
{
    private SeasonRepository $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Inserts all available Eredivisie seasons into the database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // get current football season year
        $year = date('Y') - 1;

        $this->seasonRepository->insertSeasons(range($year - 3, $year));

        $io->success('Seasons were successfully inserted!');

        return Command::SUCCESS;
    }
}
