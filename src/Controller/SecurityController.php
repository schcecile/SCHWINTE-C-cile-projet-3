<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationType;
use App\Entity\User;

class SecurityController extends AbstractController
{
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        //links the object to the form fields
        $user = new User();

        //allows to build the registration form
        $form = $this->createForm(RegistrationType::class, $user);

        //analyzes the given request
        $form->handleRequest($request);

        //if all conditions are met
        if ($form->isSubmitted() and $form->isValid()) {

            //hashes the password in the database
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            //saves the user data in the database
            $manager->persist($user);
            $manager->flush();

            //redirects to the login page
            return $this->redirectToRoute('login');
        }

        //displays the view
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function legals()
    {
        return $this->render('security/legals.html.twig');
    }

    public function conditions()
    {
        return $this->render('security/conditions.html.twig');
    }

    public function confidentiality()
    {
        return $this->render('security/confidentiality.html.twig');
    }

    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    public function logout()
    {
    }

}
