<?php

namespace App\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

class BookManager
{
    protected array $cached = [];

    public function __construct(
        protected readonly EntityManagerInterface $manager,
        protected readonly int $limit,
    ) {
    }

    public function findByTitle(string $title): ?Book
    {
        return $this->cached[$title] = $this->manager
            ->getRepository(Book::class)
            ->findLikeTitle($title)[0] ?? null;
    }

    public function findPaginated(int $page): iterable
    {
        $repository = $this->manager->getRepository(Book::class);

        return $repository->findBy([], ['id' => 'DESC'], $this->limit, $this->limit * ($page - 1));
    }

    public function hasCachedTitle(string $title): bool
    {
        return \array_key_exists($title, $this->cached);
    }
}
