<?php

namespace HV\NewsBundle\Controller;

use HV\NewsBundle\Entity\News;
use HV\NewsBundle\Entity\Comments;
use HV\UsersBundle\Entity\Users;
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
      $listNews = $repository->myfindAll();
      $newsInCarousel = $repository->getNewsCarousel();
      return $this->render('@HVNews/News/index.html.twig', array('listNews' => $listNews, 'newsInCarousel' => $newsInCarousel));
    }

    public function viewCurrentEventsAction($id)
    {
      $repository = $this->getDoctrine()->getManager();

      $news = $repository->getRepository('HVNewsBundle:News')->find($id);
      $comments = $repository->getRepository('HVNewsBundle:Comments')->getComments($id);  

      if (null === $news) {
        throw new NotFoundHttpException("La news d'id ".$id." n'existe pas.");
      }

      return $this->render('@HVNews/News/viewCurrentEvents.html.twig',
        array('news' => $news,
      'comments' => $comments));
    }
}
