<?php

namespace HV\HomeBundle\Controller;

use HV\NewsBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
      $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('HVNewsBundle:News')
      ;
      $lastNews = $repository->getLastNews(3);
      return $this->render('@HVHome/Home/index.html.twig', array('lastNews' => $lastNews));
    }
}
