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
    $formRegistration = $this->createForm(RegistrationType::class, new Users());

    if ($request->isMethod('POST')) {
      $newUser = new Users();
      $newUser->setLogin($_POST['hv_usersbundle_registration']['login']);
      $newUser->setPassword(password_hash($_POST['hv_usersbundle_registration']['password'], PASSWORD_DEFAULT));
      $newUser->setEmail($_POST['hv_usersbundle_registration']['email']);
      $repository->persist($newUser);
      $repository->flush();
      return $this->redirectToRoute('hv_home_homepage');
    }
    return $this->render('@HVUsers/Users/registration.html.twig', array(
      'formUsers' => $formUsers->createView(),
      'formRegistration' =>$formRegistration->createView(),
    ));
  }
  public function logoutAction()
  {
    $session = new Session();
    $session->clear();
    return $this->redirectToRoute('hv_home_homepage');
  }
}
