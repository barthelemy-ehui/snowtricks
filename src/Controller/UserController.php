<?php
namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    public function __construct(UserRepository $userRepository, FileUploader $fileUploader)
    {
        $this->userRepository = $userRepository;
        $this->fileUploader = $fileUploader;
    }
    
    /**
     * @Route("/profile", name="user_profile")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, ['attr' => ['profile' => true]]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $moreThanOneSlash = 1;
            if($user->getPicture() !== null && substr_count($user->getPicture(), '/') + 1 >$moreThanOneSlash) {
                $file = new UploadedFile($user->getPicture(), 'tmp');
                $filename = $this->fileUploader->upload($file);
                $user->setPicture($filename);
            }

            $user->setUpdatedAt(new \DateTime());
            $this->userRepository->save($user);
            $this->addFlash('success','Votre profile a été mis à jour avec succès');
        }
        
        return $this->render('user/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/newpassword", name="change_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, ['attr' => ['change_password' => true]]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if(!$passwordEncoder->isPasswordValid($user, $user->getCurrentPassword())){
                $this->addFlash('failed','Mot de passe incompatible');
                goto badcurrentpassword;
            }
            $password = $passwordEncoder->encodePassword($user, $user->getChangePassword());
            $user->setPassword($password);
            $this->userRepository->save($user);
            $this->addFlash('success','Mot de passe modifier avec succès');
            return $this->redirectToRoute('user_profile');
        }
    
        badcurrentpassword:
        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}