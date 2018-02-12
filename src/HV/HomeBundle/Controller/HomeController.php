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

      $formUsers = $this->createForm(UsersType::class, new Users());

      if ($request->isMethod('POST')) {
        $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['hv_usersbundle_users']);
        if ($connection != false) {
          $session = new Session();
          $session->set('User', $connection);
        }
        return $this->redirectToRoute('hv_home_homepage');
      }
      return $this->render('@HVHome/Home/index.html.twig', array(
        'lastNews' => $lastNews,
        'formUsers' => $formUsers->createView(),
      ));
    }
}
