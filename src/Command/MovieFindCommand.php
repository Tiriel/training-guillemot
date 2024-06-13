<?php

namespace App\Command;

use App\Movie\Consumer\Enum\SearchType;
use App\Movie\Provider\MovieProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Find a movie by IMDb or Title',
)]
class MovieFindCommand extends Command
{
    protected ?string $value = null;
    protected ?SearchType $type = null;
    protected ?SymfonyStyle $io = null;
    public function __construct(protected readonly MovieProvider $provider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The IMDb ID or Title of the movie you are searching for')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->value = $input->getArgument('value');
        $this->io = new SymfonyStyle($input, $output);
        $this->provider->setIo($this->io);

        if ($this->value) {
            $this->type = $this->getType();
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$this->value) {
            $this->value = $this->io->ask('What is the title or IMDb ID you are searching for ?');
            $this->type = $this->getType();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title(sprintf('You are searching for a movie with %s "%s"', $this->type->name, $this->value));

        try {
            $movie = $this->provider->getOne($this->value, $this->type);
        } catch (HttpException $e) {
            $this->io->error('Unknown error : '.$e->getMessage());

            return Command::FAILURE;
        }

        if (null === $movie) {
            $this->io->error('Movie not found!');

            return Command::FAILURE;
        }

        $this->io->table(
            ['Id', 'IMDb Id', 'Title', 'Rated'],
            [[$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]]
        );
        $this->io->success('Movie in database!');

        return Command::SUCCESS;
    }

    protected function getType(): SearchType
    {
        return 0 !== preg_match('/tt\d{6,8}/i', $this->value) ? SearchType::Id : SearchType::Title;
    }
}
