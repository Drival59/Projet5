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
      $listTopicView = $em->getRepository('HVForumBundle:ForumTopicView')->findAll();
      $listPost = $em->getRepository('HVForumBundle:ForumPost')->findAll();
      return $this->render('@HVForum/Forum/index.html.twig', array(
        'listCategory' => $listCategory,
        'listSection' => $listSection,
        'listTopic' => $listTopic,
        'listTopicView' => $listTopicView,
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
      $listTopicView = $em->getRepository('HVForumBundle:ForumTopicView')->findAll();
      $listTopic = $em->getRepository('HVForumBundle:ForumTopic')->myFindByForumSection($section[0]->getId());
      $listPost = $em->getRepository('HVForumBundle:ForumPost')->findAll();
      return $this->render('@HVForum/Forum/viewSection.html.twig', array(
        'listTopic' => $listTopic,
        'listPost' => $listPost,
        'listTopicView' => $listTopicView,
        'nameSection' => $section[0]->getName(),
        'urlSection' => $section[0]->getUrl(),
      ));
    }

    public function viewTopicAction(Request $request, $url, $id, $page)
    {
      $nbPostPerPage = $this->container->getParameter('nb_post_per_page');
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

      $session = $request->getSession();
      if ($session->get('User') != null) {
        $idUsers = $session->get('User')->getId();
        $topicView = $em->getRepository('HVForumBundle:ForumTopicView')->getTopicView($idUsers, $id);
        $em->remove($topicView[0]);
      }
      
      $views = $topic->getView();
      $views++;
      $topic->setView($views);
      $em->flush();
      $listPost = $em->getRepository('HVForumBundle:ForumPost')->MyFindByForumTopic($id, $page, $nbPostPerPage);

      $pagination = array(
        'page' => $page,
        'nbPages' => ceil(count($listPost) / $nbPostPerPage),
        'nomRoute' => 'hv_forum_topic',
        'paramRoute' => array(
          'url' => $url,
          'id' => $id,
        ),
      );

      return $this->render('@HVForum/Forum/viewTopic.html.twig', array(
        'listPost' => $listPost,
        'nameTopic' => $topic->getSubject(),
        'urlSection' => $url,
        'pagination' => $pagination,
      ));
    }
}
