<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BookFindCommand extends Command
{
    protected static $defaultName = 'app:book:find';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('firstname', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NEGATABLE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $lastname = $input->getArgument('lastname');
        if (!$lastname) {
            $lastname = $io->ask('What is your lastname ?');
        }

        if ($lastname) {
            $io->note(sprintf('You passed a lastname: %s', $lastname));
        }

        $firstname = $input->getArgument('firstname');

        if ($firstname) {
            $io->note(sprintf('You passed a firstname: %s', implode(', ', $firstname)));
        }

        $opt = $input->getOption('option1');
        if ($opt === true) {
            $text = 'True';
        } elseif ($opt === false) {
            $text = 'False';
        } else {
            $text = 'Null';
        }
        $io->text(sprintf("You maybe passed an option : %s", $text));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
