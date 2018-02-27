<?php

namespace HV\NewsBundle\Controller;

use HV\NewsBundle\Entity\News;
use HV\NewsBundle\Entity\Comments;
use HV\UsersBundle\Form\UsersType;
use HV\NewsBundle\Form\CommentsType;
use HV\NewsBundle\Form\NewsType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
      $em = $this->getDoctrine()->getManager();

      $listNews = $em->getRepository('HVNewsBundle:News')->myfindAll();
      $newsInCarousel = $em->getRepository('HVNewsBundle:News')->getNewsCarousel();
      $popularNews = $em->getRepository('HVNewsBundle:News')->getPopularNews();

      if ($request->isMethod('POST')) {
        $connection = $em->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
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
      $em = $this->getDoctrine()->getManager();

      $news = $em->getRepository('HVNewsBundle:News')->find($id);
      $news->setViews();
      $em->flush();
      $comments = $em->getRepository('HVNewsBundle:Comments')->getComments($id);

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
            $em->merge($newComment);
            $em->flush();
            $this->addFlash('notice', 'Votre commentaire a bien été ajouté.');
            return $this->redirectToRoute('hv_news_currentevents', array(
              'id' => $id));
          }
        } else {
          $connection = $em->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
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
      $session = $request->getSession();
      $em = $this->getDoctrine()->getManager();

      $addNew = new News();
      $formNew = $this->createForm(NewsType::class, $addNew);

      if ($formNew->handleRequest($request)->isValid()) {
        $new = new News();
        $new->setTitle($addNew->getTitle());
        $new->setContent($addNew->getContent());
        $new->setUsers($session->get('User'));
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $addNew->getImageUrl();
        $fileName = $file->getClientOriginalName();
        $file->move(
          $this->getParameter('news_images_directory'),
          $fileName
        );
        $new->setImageUrl($fileName);

        if ($addNew->getInCarousel() != null) {
          $new->setInCarousel($addNew->getInCarousel());
        }

        $em->merge($new);
        $em->flush();
        return $this->redirectToRoute('hv_users_admin');
      }
      return $this->render('@HVNews/News/createNew.html.twig', array(
        'formNew' => $formNew->createView(),
      ));
    }

    public function editNewAction(Request $request)
    {
      $session = $request->getSession();
      $em = $this->getDoctrine()->getManager();

      $new = $em->getRepository('HVNewsBundle:News')->find($_GET['id']);

      $editNew = new News();
      $formNew = $this->createForm(NewsType::class, $editNew);
      $formNew->remove('imageUrl');
      $formNew->add('imageUrl', FileType::class, array(
        'required' => false,
      ));

      if ($formNew->handleRequest($request)->isValid()) {
        $new->setTitle($editNew->getTitle());
        $new->setContent($editNew->getContent());

        if ($editNew->getImageUrl() != null) {
          /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
          $file = $editNew->getImageUrl();
          $fileName = $file->getClientOriginalName();
          $file->move(
            $this->getParameter('news_images_directory'),
            $fileName
          );
          $new->setImageUrl($fileName);
        }

        if ($editNew->getInCarousel() != null) {
          $new->setInCarousel($editNew->getInCarousel());
        } else {
          $new->setInCarousel(0);
        }

        $em->merge($new);
        $em->flush();
        return $this->redirectToRoute('hv_users_admin');
      }

      return $this->render('@HVNews/News/editNew.html.twig', array(
        'formNew' => $formNew->createView(),
        'new' => $new,
      ));
    }

    public function deleteNewAction()
    {
      $em = $this->getDoctrine()->getManager();
      $new = $em->getRepository('HVNewsBundle:News')->find($_GET['id']);
      if ($new != null) {
        $em->remove($new);
        $em->flush();
      }
      return $this->redirectToRoute('hv_users_admin');
    }

    public function adminCommentAction(Request $request)
    {
      $session = $request->getSession();
      if ($session->get('User') != null AND $session->get('User')->getRights() == 1) {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('HVNewsBundle:Comments')->getComments($_GET['id']);

        return $this->render('@HVNews/News/adminComment.html.twig', array(
          'comments' => $comments,
        ));
      }
      return $this->redirectToRoute('hv_news_currentevents', array(
        'id' => $_GET['id'],
      ));
    }

    public function deleteCommentAction(Request $request)
    {
      $session = $request->getSession();
      if ($session->get('User') != null AND $session->get('User')->getRights() == 1) {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('HVNewsBundle:Comments')->find($_GET['id']);

        $em->remove($comment);
        $em->flush();
      }
      return $this->redirectToRoute('hv_users_admin');
    }

    public function editCommentAction(Request $request)
    {
      $session = $request->getSession();
      if ($session->get('User') != null AND $session->get('User')->getRights() == 1) {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('HVNewsBundle:Comments')->find($_GET['id']);
        $formComments = $this->createForm(CommentsType::class, new Comments());

        if ($formComments->handleRequest($request)->isValid()) {
          $comment->setContent($_POST['hv_newsbundle_comments']['content']);
          $comment->setReports(0);
          $em->merge($comment);
          $em->flush();
          return $this->redirectToRoute('hv_users_admin');
        }
        return $this->render('@HVNews/News/editComment.html.twig', array(
          'comment' => $comment,
          'formComments' => $formComments->createView(),
        ));
      }
      return $this->redirectToRoute('hv_users_admin');
    }
}
