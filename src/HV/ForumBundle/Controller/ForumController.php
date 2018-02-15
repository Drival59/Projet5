<?php

namespace HV\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    public function indexAction()
    {
        return $this->render('@HVForum/Forum/index.html.twig');
    }
}
