<?php

namespace HV\UsersBundle\Controller;

use HV\UsersBundle\Entity\Users;
use HV\UsersBundle\Form\UsersType;
use HV\UsersBundle\Form\UsersAvatarType;
use HV\UsersBundle\Form\UsersPwdType;
use HV\UsersBundle\Form\UsersEmailType;
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
      $newUser->setAvatar("defaults_avatar_059_metal.jpg");
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


  public function editAction($login, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $session = $request->getSession();

    $users = $em->getRepository('HVUsersBundle:Users')->find($session->get('User')->getId());

    $usersForPwd = new Users();
    $formUsersPwd = $this->createForm(UsersPwdType::class, $usersForPwd);

    $usersForEmail = new Users();
    $formUsersEmail = $this->createForm(UsersEmailType::class, $usersForEmail);;

    $usersForAvatar = new Users();
    $formUsersAvatar = $this->createForm(UsersAvatarType::class, $usersForAvatar);

    if ($formUsersPwd->handleRequest($request)->isValid()) {
      $users->setPassword(password_hash($_POST['hv_usersbundle_usersPwd']['password'], PASSWORD_DEFAULT));
      $em->flush();
      $session->set('User', $users);
      $this->addFlash('successPwd', 'Votre mot de passe a bien été modifié.');
      return $this->redirectToRoute('hv_users_edit', array(
        'login' => $session->get('User')->getLogin(),
      ));
    }
    if ($formUsersEmail->handleRequest($request)->isValid()) {
      $users->setEmail($_POST['hv_usersbundle_usersEmail']['email']);
      $em->flush();
      $session->set('User', $users);
      $this->addFlash('successEmail', 'Votre adresse email a bien été modifiée.');
      return $this->redirectToRoute('hv_users_edit', array(
        'login' => $session->get('User')->getLogin(),
      ));
    }
    if ($formUsersAvatar->handleRequest($request)->isValid()) {
      /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
      $file = $usersForAvatar->getAvatar();
      $fileName = $file->getClientOriginalName();
      $file->move(
        $this->getParameter('users_avatar_directory'),
        $fileName
      );
      $users->setAvatar($fileName);
      $em->flush();
      $session->set('User', $users);
      $this->addFlash('successAvatar', 'Votre avatar a bien été modifié');
      return $this->redirectToRoute('hv_users_edit', array(
        'login' => $session->get('User')->getLogin(),
      ));
    }
    return $this->render('@HVUsers/Users/edit.html.twig', array(
      'formUsersPwd' => $formUsersPwd->createView(),
      'formUsersEmail' => $formUsersEmail->createView(),
      'formUsersAvatar' => $formUsersAvatar->createView(),
    ));
  }


  public function logoutAction()
  {
    $session = new Session();
    $session->clear();
    return $this->redirectToRoute('hv_home_homepage');
  }
}
