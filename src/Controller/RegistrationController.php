<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface

class RegistrationController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
    
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $user->setToken(md5(uniqid('token', true)));
            $user->setActive(false);
            $this->userRepository->save($user);
            
            // todo: Envoyer un e-email avec le token à valider
            
            return $this->redirectToRoute('trick');
        }
        
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/renew/{token}", name="renew_password")
     */
    public function passwordRenew($token){
        // todo: renew password
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        if($form->isSubmitted() && $form->isValid()){
            // todo Implementer le test
            // todo Envoyer un token
        }
        
        // Vérifier le token dans la base puis montrer la page de renouvellement de mot de passe
        
        // Sinon afficher qu'il est impossible de renouveller
        
        return $this->render('registration/renew.html.twig',[
           '$form' => $form->createView()
        ]);
    }
}
