<?php

namespace HV\UsersBundle\Controller;

use HV\UsersBundle\Entity\Users;
use HV\UsersBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;


class UsersController extends Controller
{
  public function registrationAction()
  {
    $formUsers = $this->createForm(UsersType::class, new Users());
    return $this->render('@HVUsers/Users/registration.html.twig', array(
      'formUsers' => $formUsers->createView(),
    ));
  }
  public function logoutAction()
  {
    $session = new Session();
    $session->clear();
    return $this->redirectToRoute('hv_home_homepage');
  }
}
