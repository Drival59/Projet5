<?php

namespace HV\NewsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class NewsController
{
    public function indexAction()
    {
        return new Response("Notre propre Hello World !");
    }
}
