<?php

namespace HV\NewsBundle\Controller;

use HV\NewsBundle\Entity\News;
use HV\NewsBundle\Entity\Comments;
use HV\UsersBundle\Entity\Users;
use HV\UsersBundle\Form\UsersType;
use HV\NewsBundle\Form\CommentsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
      $popularNews = $repository->getPopularNews();

      $formUsers = $this->createForm(UsersType::class, new Users());

      if ($request->isMethod('POST')) {
        if (isset($_POST['hv_usersbundle_users'])) {
          $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['hv_usersbundle_users']);
          if ($connection != false) {
            $session = new Session();
            $session->set('User', $connection);
          }
          return $this->redirectToRoute('hv_news_listnews');
        }
      }

      return $this->render('@HVNews/News/index.html.twig', array(
        'listNews' => $listNews,
        'newsInCarousel' => $newsInCarousel,
        'popularNews' => $popularNews,
        'formUsers' => $formUsers->createView(),
      ));
    }

    public function viewCurrentEventsAction($id, Request $request)
    {
      $session = $request->getSession();
      $repository = $this->getDoctrine()->getManager();

      $news = $repository->getRepository('HVNewsBundle:News')->find($id);
      $news->setViews();
      $repository->flush();
      $comments = $repository->getRepository('HVNewsBundle:Comments')->getComments($id);

      $formUsers = $this->createForm(UsersType::class, new Users());
      $formComments = $this->createForm(CommentsType::class, new Comments());

      if (null === $news) {
        throw new NotFoundHttpException("La news d'id ".$id." n'existe pas.");
      }

      if ($request->isMethod('POST')) {
        if (isset($_POST['hv_usersbundle_users'])) {
          $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['hv_usersbundle_users']);
          if ($connection != false) {
            $session = new Session();
            $session->set('User', $connection);
          }
          return $this->redirectToRoute('hv_news_currentevents', array(
            'id' => $id));
        } else {
          $newComment = new Comments();
          $newComment->setNews($news);
          $newComment->setUsers($session->get('User'));
          $newComment->setContent($_POST['hv_newsbundle_comments']['content']);
          $repository->merge($newComment);
          $repository->flush();
          return $this->redirectToRoute('hv_news_currentevents', array(
            'id' => $id));
        }
      }
      
      return $this->render('@HVNews/News/viewCurrentEvents.html.twig', array(
        'news' => $news,
        'comments' => $comments,
        'formUsers' => $formUsers->createView(),
        'formComments' =>$formComments->createView(),
      ));
    }
}
