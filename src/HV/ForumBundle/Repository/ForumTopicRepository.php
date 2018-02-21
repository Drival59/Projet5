<?php

namespace HV\ForumBundle\Repository;

/**
 * ForumTopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForumTopicRepository extends \Doctrine\ORM\EntityRepository
{
  public function myFindByForumSection($idForumSection)
  {

    $qb = $this->createQueryBuilder('t');
    $qb->where('t.forumSection = :idForumSection');
    $qb->setParameter('idForumSection', $idForumSection);
    $qb->orderBy('t.id', 'DESC');
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
}
