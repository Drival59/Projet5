<?php

namespace HV\ForumBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ForumPostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForumPostRepository extends \Doctrine\ORM\EntityRepository
{
  public function myFindByForumTopic($idForumTopic, $page, $nbMaxPerPage)
  {
    if (!is_numeric($page))
    {
      throw new InvalidArgumentException(
         'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
      );
    }

    if ($page < 1)
    {
        throw new NotFoundHttpException('La page demandée n\'existe pas');
    }

    if (!is_numeric($nbMaxPerPage)) {
        throw new InvalidArgumentException(
            'La valeur de l\'argument $nbMaxParPage est incorrecte (valeur : ' . $nbMaxPerPage . ').'
        );
    }

    $qb = $this->createQueryBuilder('p');
    $qb->where('p.forumTopic = :idForumTopic');
    $qb->setParameter('idForumTopic', $idForumTopic);

    $query = $qb->getQuery();

    $firstResult = ($page - 1) * $nbMaxPerPage;
    $query->setFirstResult($firstResult)->setMaxResults($nbMaxPerPage);
    $paginator = new Paginator($query);

    if (($paginator->count() <= $firstResult) AND $page != 1) {
        throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
    }

    return $paginator;
  }
}