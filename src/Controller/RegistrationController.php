<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RegistrationController extends AbstractController/*Controller*/
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
    public function register(Request $request, UserPasswordEncoder $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            $this->userRepository->save($user);
            
            // todo: Envoyer un token à l'utilisateur pour confirmer l'inscription
            
            return $this->redirectToRoute('/trick');
        }
        
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/connexion", name="user_connexion")
     */
    public function connexion(Request $request)
    {
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        if($form->isSubmitted() && $form->isValid()){
            //todo: verifier l'utilisateur depuis la base puis le faire connecter
        }
        
        return $this->render('registration/connexion.html.twig', [
            'form' => null
        ]);
    }
    
    /**
     * @Route("/renew", name="renew_password")
     */
    public function passwordRenew(){
        // todo: renew password
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        if($form->isSubmitted() && $form->isValid()){
            //todo implemente le test
        }
        
        return $this->redirectToRoute('registration/renew.html.twig',[
           '$form' => $form->createView()
        ]);
    }
}
