<?php

namespace HV\NewsBundle\Repository;

use Doctrine\ORM\QueryBuilder;
/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsRepository extends \Doctrine\ORM\EntityRepository
{
  public function getLastNews($limit)
  {
    $qb = $this->createQueryBuilder('n');
    $qb->orderBy('n.dateNews', 'DESC');
    $qb->setMaxResults($limit);
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
  public function myFindAll()
  {
    $qb = $this->createQueryBuilder('n');
    $qb->orderBy('n.dateNews', 'DESC');
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
  public function getNewsCarousel()
  {
    $qb = $this->createQueryBuilder('n');
    $qb->where('n.inCarousel = 1');
    $qb->orderBy('n.dateNews', 'DESC');
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
}
