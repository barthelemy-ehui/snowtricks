<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\SendToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SendToken
     */
    private $sendToken;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    
    public function __construct(UserRepository $userRepository, SendToken $sendToken, UserPasswordEncoderInterface $encoder)
    {
    
        $this->userRepository = $userRepository;
        $this->sendToken = $sendToken;
        $this->encoder = $encoder;
    }
    
    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['attr' => ['registration' => true]]);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $token = md5(uniqid('token', true));
            $user->setPassword($password);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $user->setToken($token);
            $user->setIsActive(false);
            $this->userRepository->save($user);
            
            $urlGenerate = $this->generateUrl('token_validate',
                ['token'=> $token]);

            $this->sendToken->send(
                $user,
                'Inscription' ,
                $urlGenerate,
                SendToken::ACCOUNT_MESSAGE);
   
            $this->addFlash('success', 'Un email vous a été envoyé pour activer votre compte.');
            return $this->redirectToRoute('trick');
        }
        
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/token/validate/{token}", name="token_validate")
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registrationValidateToken($token) {
        
        $user = $this->userRepository->findOneBy(['token' => $token]);
        if($user === null){
         return new Response('Token invalide');
        }
        
        $token = md5(uniqid('token', true));
        $user->setToken($token);
        $user->setIsActive(true);
        $this->userRepository->save($user);
        $this->addFlash('success','Compte activé avec succès!');
        
        return $this->redirectToRoute('login');
    }
    
    /**
     * @Route("/renew_password", name="renew_password")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function renew(Request $request) {
        
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('save', SubmitType::class)
            ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $email = $form->getData()['email'];
            $user = $this->userRepository->findOneBy(['email' => $email]);
            if($user !== null) {
                $token = md5(uniqid('token', true));
                $urlGenerate = $this->generateUrl('token_renew', ['token' => $token]);
                $user->setToken($token);
                $this->userRepository->save($user);
                $this->sendToken->send(
                    $user,
                    'Renouveller mot de passe' ,
                    $urlGenerate,
                    SendToken::RENEW_MESSAGE
                );
                $this->addFlash('success','Le lien pour renouveller votre mot de passe est envoyé avec success par email.');
                return $this->redirectToRoute('trick');
            }
    
            $this->addFlash('failed','Adresse e-mail inexistant');
        }
        
        return $this->render('registration/renew.html.twig', [
           'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/token/renew/{token}", name="token_renew")
     * @param $token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function changePassword($token, Request $request) {
        
        $user = $this->userRepository->findOneBy(['token' => $token]);
        if($user === null) {
            return new Response('Token invalid');
        }
        
        $form = $this->createFormBuilder()
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])->getForm();
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $password = $this->encoder->encodePassword($user, $form->getData()['plainPassword']);
            $user->setPassword($password);
            $token = md5(uniqid('token', true));
            $user->setToken($token);
            $this->userRepository->save($user);
            
            $this->addFlash('success', 'Veuillez vous connecter avec votre nouveau mot de passe.');
            return $this->redirectToRoute('login');
        }
        
        return $this->render('registration/changePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
