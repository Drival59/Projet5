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
}
