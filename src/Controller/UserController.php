<?php
namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
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
     * @Route("/profile", name="user_profile")
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
    
            $password = $user->getPassword();
            if(!$passwordEncoder->isPasswordValid($user, $user->getPlainPassword())) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            }
            
            $user->setPassword($password);
            $user->setUpdatedAt(new \DateTime());
            $this->userRepository->save($user);
            
            $this->addFlash('success','Votre profile a été mis à jour avec succès');
        }
        
        return $this->render('user/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
}