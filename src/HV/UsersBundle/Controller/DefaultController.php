<?php

namespace HV\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HVUsersBundle:Default:index.html.twig');
    }
}
