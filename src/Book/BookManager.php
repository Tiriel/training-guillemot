<?php

namespace App\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BookManager
{
    protected array $cached = [];

    public function __construct(
        protected readonly EntityManagerInterface $manager,
        protected readonly int $limit,
        protected readonly Security $security,
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
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $user = $this->security->getUser();
        }

        return $repository->findBy([], ['id' => 'DESC'], $this->limit, $this->limit * ($page - 1));
    }

    public function hasCachedTitle(string $title): bool
    {
        return \array_key_exists($title, $this->cached);
    }
}
