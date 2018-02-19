<?php

namespace HV\ForumBundle\Controller;

use HV\ForumBundle\Entity\ForumCategory;
use HV\ForumBundle\Entity\ForumTopic;
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
      $listTopic = $em->getRepository('HVForumBundle:ForumTopic')->findAll();
      $listPost = $em->getRepository('HVForumBundle:ForumPost')->findAll();
      return $this->render('@HVForum/Forum/index.html.twig', array(
        'listCategory' => $listCategory,
        'listSection' => $listSection,
        'listTopic' => $listTopic,
        'listPost' => $listPost,
      ));
    }

    public function viewSectionAction($url)
    {
      $em = $this->getDoctrine()->getManager();
      $section = $em->getRepository('HVForumBundle:ForumSection')->findByUrl($url);

      if (!$section) {
        throw $this->createNotFoundException(
            'Aucune section disponible avec l\'url suivant : '.$url
        );
      }

      $idSection = $section[0]->getId();
      $nameSection = $section[0]->getName();
      $urlSection = $section[0]->getUrl();
      $listTopic = $em->getRepository('HVForumBundle:ForumTopic')->findByForumSection($idSection);
      $listPost = $em->getRepository('HVForumBundle:ForumPost')->findAll();
      return $this->render('@HVForum/Forum/viewSection.html.twig', array(
        'listTopic' => $listTopic,
        'nameSection' => $nameSection,
        'listPost' => $listPost,
        'urlSection' => $urlSection,
      ));
    }

    public function viewTopicAction($url, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $section = $em->getRepository('HVForumBundle:ForumSection')->findByUrl($url);
      if (!$section) {
        throw $this->createNotFoundException(
            'Aucune section disponible avec l\'url suivant : '.$url
        );
      }

      $topic = $em->getRepository('HVForumBundle:ForumTopic')->find($id);
      if (!$topic) {
        throw $this->createNotFoundException(
            'Aucun topic disponible avec l\'id suivant : '.$id
        );
      }

      $views = $topic->getView();
      $views++;
      $topic->setView($views);
      $em->flush();
      $nameTopic = $topic->getSubject();
      $listPost = $em->getRepository('HVForumBundle:ForumPost')->findByForumTopic($id);
      return $this->render('@HVForum/Forum/viewTopic.html.twig', array(
        'listPost' => $listPost,
        'nameTopic' => $nameTopic,
        'urlSection' => $url,
      ));
    }
}
