<?php

namespace HV\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class NewsController extends Controller
{
    public function indexAction()
    {
      return $this->render('@HVNews/News/index.html.twig');
    }

    public function viewCurrentEventsAction($url)
    {
      return $this->render('@HVNews/News/viewCurrentEvents.html.twig', array('id' => $url));
    }
}
