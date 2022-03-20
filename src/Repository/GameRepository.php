<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $entity, bool $flush = true): Game
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function update(Game $entity, bool $flush = true): Game
    {
        $this->_em->merge($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function getGamesWinnedBy(string $player): int
    {
        $qb = $this->createQueryBuilder('g');
        $qb->select('count(g.id)');
        $qb->where('g.winner = :player');
        $qb->setParameter('player', $player);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
