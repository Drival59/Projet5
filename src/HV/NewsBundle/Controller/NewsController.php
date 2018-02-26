<?php

namespace HV\NewsBundle\Controller;


use HV\NewsBundle\Entity\Comments;
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
    public function indexAction(Request $request)
    {
      $repository = $this->getDoctrine()->getManager();

      $listNews = $repository->getRepository('HVNewsBundle:News')->myfindAll();
      $newsInCarousel = $repository->getRepository('HVNewsBundle:News')->getNewsCarousel();
      $popularNews = $repository->getRepository('HVNewsBundle:News')->getPopularNews();

      if ($request->isMethod('POST')) {
        $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
        if ($connection != false) {
          $session = new Session();
          $session->set('User', $connection);
          return $this->redirectToRoute('hv_news_listnews');
        } else {
          $this->addFlash('danger', 'Identifiant ou mot de passe incorrects');
          return $this->redirectToRoute('hv_news_listnews');
        }
      }
      return $this->render('@HVNews/News/index.html.twig', array(
        'listNews' => $listNews,
        'newsInCarousel' => $newsInCarousel,
        'popularNews' => $popularNews,
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

      $formComments = $this->createForm(CommentsType::class, new Comments());

      if (null === $news) {
        throw new NotFoundHttpException("La news d'id ".$id." n'existe pas.");
      }

      if ($request->isMethod('POST')) {
        if (isset($_POST['hv_newsbundle_comments'])) {
          if ($formComments->handleRequest($request)->isValid()) {
            $newComment = new Comments();
            $newComment->setNews($news);
            $newComment->setUsers($session->get('User'));
            $newComment->setContent($_POST['hv_newsbundle_comments']['content']);
            $repository->merge($newComment);
            $repository->flush();
            $this->addFlash('notice', 'Votre commentaire a bien été ajouté.');
            return $this->redirectToRoute('hv_news_currentevents', array(
              'id' => $id));
          }
        } else {
          $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
          if ($connection != false) {
            $session = new Session();
            $session->set('User', $connection);
            return $this->redirectToRoute('hv_news_currentevents', array(
            'id' => $id));
          } else {
            $this->addFlash('danger', 'Identifiant ou mot de passe incorrects');
            return $this->redirectToRoute('hv_news_currentevents', array(
            'id' => $id));
          }
        }
      }

      return $this->render('@HVNews/News/viewCurrentEvents.html.twig', array(
        'news' => $news,
        'comments' => $comments,
        'formComments' =>$formComments->createView(),
      ));
    }

    public function createNewAction(Request $request)
    {
      return $this->render('@HVNews/News/createNew.html.twig');
    }
}
