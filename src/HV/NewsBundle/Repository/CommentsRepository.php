<?php

namespace HV\NewsBundle\Repository;

/**
 * CommentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentsRepository extends \Doctrine\ORM\EntityRepository
{
  public function getComments($newsId)
  {
    $qb = $this->createQueryBuilder('n');
    $qb->where('n.news = :newsId');
    $qb->setParameter('newsId', $newsId);
    $qb->addOrderBy('n.dateComment', "DESC");
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
}
