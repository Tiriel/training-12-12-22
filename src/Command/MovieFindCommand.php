<?php

namespace App\Command;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MovieFindCommand extends Command
{
    protected static $defaultName = 'app:movie:find';
    protected static $defaultDescription = 'Add a short description for your command';

    private ?SymfonyStyle $io;
    private MovieProvider $provider;
    private MovieRepository $repository;

    private ?string $type = '';
    private ?string $value = '';

    public function __construct(
        MovieProvider $provider,
        MovieRepository $repository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->provider = $provider;
        $this->repository = $repository;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The movie you are searching for.')
            ->addArgument('type', InputArgument::OPTIONAL, 'The type of search (title or id).')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->provider->setSymfonyStyle($this->io);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$this->value = $input->getArgument('value')) {
            $this->value = $this->io->ask('What is the title or id of the movie you\'re searching for?');
        }

        $this->type = strtolower($input->getArgument('type'));
        if (\in_array($this->type, ['title', 'id'])) {
            $this->type = substr($this->type, 0, 1);
        }
        if (!\in_array($this->type, ['t', 'i'])) {
            $this->type = $this->io->choice('What type of data are your searching on?', ['t' => 'title', 'i' => 'id'], 't');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Your search :');
        $this->io->text(sprintf("Searching for a movie with %s=%s", $this->type, $this->value));
        if (OmdbApiConsumer::MODE_ID === $this->type && $movie = $this->repository->findOneBy(['imdbId' => $this->value])) {
            $this->displayResult($movie);

            return Command::SUCCESS;
        }

        try {
            $movie = $this->provider->getMovie($this->type, $this->value);
        } catch (NotFoundHttpException $e) {
            $this->io->error('Movie not found!');

            return Command::FAILURE;
        }

        $this->displayResult($movie);

        return Command::SUCCESS;
    }

    private function displayResult(Movie $movie): void
    {
        $this->io->section('Result :');
        $this->io->table(['id', 'imdbId', 'Title', 'Rated'],[
            [$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()],
        ]);

        $this->io->success('Movie successfully found and imported!');
    }
}
