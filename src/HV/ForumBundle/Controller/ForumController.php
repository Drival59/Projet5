<?php

namespace HV\ForumBundle\Controller;

use HV\ForumBundle\Entity\ForumCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      $listCategory = $em->getRepository('HVForumBundle:ForumCategory')->findAll();
      $listSection = $em->getRepository('HVForumBundle:ForumSection')->findAll();
      return $this->render('@HVForum/Forum/index.html.twig', array(
        'listCategory' => $listCategory,
        'listSection' => $listSection,
      ));
    }
}
