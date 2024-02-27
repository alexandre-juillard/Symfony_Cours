<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUserName();

        return $this->render('Security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/register', 'app.register',methods: ['GET', 'POST'])]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        ): Response | RedirectResponse 
    {
        $user = new User;

        //on crée le formulaire avec infos du user en instanciant un User avant
       $form = $this->createForm(UserType::class, $user);
       
       //on stocke les infos de la requete du formulaire pour soummetre à verification
       $form->handleRequest($request);

       //verif si formulaire est soumis et valide
       if($form->isSubmitted() && $form->isValid()) {
        $user
        ->setPassword(
            $hasher->hashPassword($user, $form->get('password')->getData())
            //password est hash
        );

        //file d'attente et envoie en bdd
        $em->persist($user);
        $em->flush();

        //message de succes
        $this->addFlash('success', 'Votre inscription a été prise en compte Merci!');

        //redirection a page de login
        return $this->redirectToRoute('app.login');
       }

       return $this->render('Security/register.html.twig', [
            'form' => $form
       ]);
    }
}
