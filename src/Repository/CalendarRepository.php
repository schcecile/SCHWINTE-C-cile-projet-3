<?php

namespace App\Repository;

use App\Entity\Calendar;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    //displays all the events of a user (function called in the calendar page)
    public function findEventsByUser(User $user)
    {
        return $this->createQueryBuilder('e')
            ->join('e.users', 'user')
            ->where('user.id = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    //displays the 5 last events (function called in the dashboard page)
    public function displayLastEvents (User $user)
    {
        return $this->createQueryBuilder('e')
                ->join('e.users', 'user')
                ->where('user.id = :user')
                ->setParameter('user', $user)
                ->orderBy('e.id', 'DESC')
                ->setMaxResults(3)
                ->getQuery()
                ->execute();
    }

}
