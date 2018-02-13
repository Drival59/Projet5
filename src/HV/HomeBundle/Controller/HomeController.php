<?php

namespace HV\HomeBundle\Controller;

use HV\NewsBundle\Entity\News;
use HV\UsersBundle\Entity\Users;
use HV\UsersBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
      $repository = $this->getDoctrine()->getManager();

      $lastNews = $repository->getRepository('HVNewsBundle:News')->getLastNews(3);

      if ($request->isMethod('POST')) {
        $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
        if ($connection != false) {
          $session = new Session();
          $session->set('User', $connection);
          return $this->redirectToRoute('hv_home_homepage');
        } else {
          $this->addFlash('danger', 'Identifiant ou mot de passe incorrects');
          return $this->redirectToRoute('hv_home_homepage');
        }
      }
      return $this->render('@HVHome/Home/index.html.twig', array(
        'lastNews' => $lastNews,
      ));
    }
}
