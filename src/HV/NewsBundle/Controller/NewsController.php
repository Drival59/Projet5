<?php

namespace HV\NewsBundle\Controller;

use HV\NewsBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NewsController extends Controller
{
    public function indexAction()
    {
      $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('HVNewsBundle:News')
      ;
      $listNews = $repository->findAll();
      return $this->render('@HVNews/News/index.html.twig', array('listNews' => $listNews));
    }

    public function viewCurrentEventsAction($id)
    {
      // On récupère le repository
      $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('HVNewsBundle:News')
      ;
      $news = $repository->find($id);
      if (null === $news) {
        throw new NotFoundHttpException("La news d'id ".$id." n'existe pas.");
      }
      return $this->render('@HVNews/News/viewCurrentEvents.html.twig', array('news' => $news));
    }
}
