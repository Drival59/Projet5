<?php

namespace HV\UsersBundle\Controller;

use HV\UsersBundle\Entity\Users;
use HV\UsersBundle\Form\UsersType;
use HV\UsersBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class UsersController extends Controller
{
  public function registrationAction(Request $request)
  {
    $repository = $this->getDoctrine()->getManager();

    $formUsers = $this->createForm(UsersType::class, new Users());


    if ($formUsers->handleRequest($request)->isValid()) {
      $newUser = new Users();
      $newUser->setLogin($_POST['hv_usersbundle_users']['login']);
      $newUser->setPassword(password_hash($_POST['hv_usersbundle_users']['password'], PASSWORD_DEFAULT));
      $newUser->setEmail($_POST['hv_usersbundle_users']['email']);
      $repository->persist($newUser);
      $repository->flush();
      return $this->redirectToRoute('hv_home_homepage');
    }
    if (isset($_POST['email']) AND isset($_POST['password'])) {
      $connection = $repository->getRepository('HVUsersBundle:Users')->getConnection($_POST['email'], $_POST['password']);
      if ($connection != false) {
        $session = new Session();
        $session->set('User', $connection);
        return $this->redirectToRoute('hv_news_currentevents', array(
        'id' => $id));
      } else {
        $this->addFlash('danger', 'Identifiant ou mot de passe incorrects');
        return $this->redirectToRoute('hv_users_registration');
      }
    }

    return $this->render('@HVUsers/Users/registration.html.twig', array(
      'formUsers' =>$formUsers->createView(),
    ));
  }
  public function editAction($login)
  {
    return $this->render('@HVUsers/Users/edit.html.twig');
  }
  public function logoutAction()
  {
    $session = new Session();
    $session->clear();
    return $this->redirectToRoute('hv_home_homepage');
  }
}
