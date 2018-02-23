<?php

namespace HV\ForumBundle\Controller;

use HV\ForumBundle\Entity\ForumCategory;
use HV\ForumBundle\Entity\ForumTopic;
use HV\ForumBundle\Entity\ForumPost;
use HV\ForumBundle\Entity\ForumTopicView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        if (!empty($topicView)) {
          $em->remove($topicView[0]);
        }
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
        'id' => $id,
      ));
    }

    public function createTopicAction(Request $request, $url)
    {
      $em = $this->getDoctrine()->getManager();
      $session = $request->getSession();
      $section = $em->getRepository('HVForumBundle:ForumSection')->findByUrl($url);

      if (!$section) {
        throw $this->createNotFoundException(
            'Aucune section disponible avec l\'url suivant : '.$url
        );
      }

      if ($request->isMethod('POST')) {
        $newTopic = new ForumTopic();
        $newTopic->setSubject($_POST['topicTitle']);
        $newTopic->setForumSection($section[0]);
        $newTopic->setUsers($session->get('User'));
        $em->merge($newTopic);
        $em->flush();

        $newTopic = $em->getRepository('HVForumBundle:ForumTopic')->findByDateLastPost($newTopic->getDateLastPost());

        $newPost = new ForumPost();
        $newPost->setContent($_POST['topicContent']);
        $newPost->setUsers($session->get('User'));
        $newPost->setForumTopic($newTopic[0]);
        $em->merge($newPost);
        $em->flush();

        $allUsers = $em->getRepository('HVUsersBundle:Users')->findAll();

        foreach ($allUsers as $user) {
          if ($user->getId() != $session->get('User')->getId()) {
            $topicView = new ForumTopicView();
            $topicView->setUsers($user);
            $topicView->setForumTopic($newTopic[0]);
            $em->merge($topicView);
            $em->flush();
          }
        }
        return $this->redirectToRoute('hv_forum_section', array(
          'url' => $url,
        ));
      }
      return $this->render('@HVForum/Forum/createTopic.html.twig', array(
        'urlSection' => $url,
      ));
    }

    public function addPostAction(Request $request, $url, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $session = $request->getSession();

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

      if ($request->isMethod('POST')) {
        $newPost = new ForumPost();
        $newPost->setContent($_POST['postContent']);
        $newPost->setUsers($session->get('User'));
        $newPost->setForumTopic($topic);
        $em->merge($newPost);
        $em->flush();

        $allUsers = $em->getRepository('HVUsersBundle:Users')->findAll();

        foreach ($allUsers as $user) {
          if ($user->getId() != $session->get('User')->getId()) {
            $topicView = new ForumTopicView();
            $topicView->setUsers($user);
            $topicView->setForumTopic($topic);
            $em->merge($topicView);
            $em->flush();
          }
        }
        return $this->redirectToRoute('hv_forum_topic', array(
          'url' => $url,
          'id' => $id,
        ));
      }

      return $this->render('@HVForum/Forum/addPost.html.twig', array(
        'urlSection' => $url,
        'idTopic' => $id,
      ));
    }

    public function editPostAction(Request $request, $url, $id, $idPost)
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

      $post = $em->getRepository('HVForumBundle:ForumPost')->find($idPost);

      if ($request->isMethod('POST')) {
        $post->setContent($_POST['postContent']);
        $em->flush();

        return $this->redirectToRoute('hv_forum_topic', array(
          'url' => $url,
          'id' => $id,
        ));
      }

      return $this->render('@HVForum/Forum/editPost.html.twig', array(
        'urlSection' => $url,
        'id' => $id,
        'post' => $post,
      ));
    }

    public function deletePostAction($url, $id, $idPost)
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

      $post = $em->getRepository('HVForumBundle:ForumPost')->find($idPost);

      $em->remove($post);
      $em->flush();

      return $this->redirectToRoute('hv_forum_topic', array(
        'url' => $url,
        'id' => $id,
      ));
    }
}
