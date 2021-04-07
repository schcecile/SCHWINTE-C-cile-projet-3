<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UsersController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     */

    public function index()
    {
        return $this->render('users/index.html.twig');
    }

    public function editprofile (Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        //if all conditions are met
        if ($form->isSubmitted() AND $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //displays a success message
            $this->addFlash('message', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('users');
        }

        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function editpassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            //get the user since we need to have access to the password
            $user = $this->getUser();

            //check if both passwords are equals
            if($request->request->get('pass') == $request->request->get('pass2')){

                //hashes the password in the database
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));

                // saves the user password in the database
                $em->flush();

                //displays a success message
                $this->addFlash('message', 'Mot de passe mis à jour avec succès !');

                //redirects to the profile page
                return $this->redirectToRoute('users');

            } else {
                //displays an error message
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques.');
            }
        }
        return $this->render('users/editpassword.html.twig');

    }

}
