<?php

namespace HV\HomeBundle\Controller;

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
        if (isset($_POST['email']) AND isset($_POST['password'])) {
          $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
          if ($connection != false) {
            $session = new Session();
            $session->set('User', $connection);
            return $this->redirectToRoute('hv_home_homepage');
          } else {
            $this->addFlash('danger', 'Identifiant ou mot de passe incorrects');
            return $this->redirectToRoute('hv_home_homepage');
          }
        } else {
          $message = (new \Swift_Message($_POST['formSubjectContact']))
            ->setFrom($_POST['formEmailContact'])
            ->setTo($this->getParameter('mailer_user'))
            ->setBody('<strong>' . $_POST['formNameContact'] . '</strong> vous a écrit : <br /><br />' . $_POST['formMessageContact'], 'text/html');
          $this->get('mailer')->send($message);
          $this->addFlash('success', 'Merci ! Votre mail a bien été envoyé. Nous vous recontacterons au plus vite.');
          return $this->redirectToRoute('hv_home_homepage');
        }
      }
      return $this->render('@HVHome/Home/index.html.twig', array(
        'lastNews' => $lastNews,
      ));
    }
}
