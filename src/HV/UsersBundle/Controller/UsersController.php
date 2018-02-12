<?php

namespace HV\UsersBundle\Controller;

use HV\UsersBundle\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends Controller
{
    public function logoutAction()
    {
      $session = new Session();
      $session->clear();
      return $this->redirectToRoute('hv_home_homepage');
    }
}
