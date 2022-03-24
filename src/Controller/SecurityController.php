<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\UserType;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
       
    }

    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager)
    {
        $user = new User();

        $formulaireUser = $this->createForm(UserType::class, $user);

        $formulaireUser->handleRequest($request);

        if ($formulaireUser->isSubmitted() && $formulaireUser->isValid()){
            // $manager->persist($user);
            // $manager->flush();

            return $this->redirectToRoute('ProStage_accueil');
        }

        return $this->render(
            'security/inscription.html.twig',
            [
                'vueFormulaireUser' => $formulaireUser->createView()
            ]
        );
    }
    
}
