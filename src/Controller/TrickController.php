<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends Controller
{
    
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    public function __construct(
        TrickRepository $trickRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        FileUploader $fileUploader
    )
    
    {
    
        $this->trickRepository = $trickRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->fileUploader = $fileUploader;
    }
    
    /**
     * @Route("/trick", name="trick")
     */
    public function index()
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $this->trickRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/trick/show/{slug}", name="show_trick")
     */
    public function show(Request $request, $slug) {
        
        $trick = $this->trickRepository->findOneBy([
            'slug'=>$slug
        ]);
        
        $comment = new Comment();
        $comment->setTrick($trick);
        $form = $this->createForm(CommentType::class, $comment);
    
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            // todo à remplacer par $request->getUser()
            $comment->setUser($this->userRepository->findOneBy(['id'=>21]));
            $comment->setCreatedAt(new \DateTime());
            $this->commentRepository->save($comment);
            
            unset($comment);
            unset($form);
            $comment = new Comment();
            $comment->setTrick($trick);
            $form = $this->createForm(CommentType::class, $comment);
            
        }
        
        
        
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/trick/new", name="trick_new")
     */
    public function new(Request $request) {
        
        $trick = new Trick();
        $trick->setCreatedAt(new \DateTime());
       
        $formOrRedirect = $this->AddOrEdit($trick, $request);
        
        if(isset($formOrRedirect['redirect'])) {
            return $this->redirectToRoute('show_trick', [
               'slug' => $trick->getSlug()
            ]);
        }
        
        $form = $formOrRedirect['form'];
        
        return $this->render('trick/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    
    /**
     * @Route("/trick/edit/{slug}", name="trick_edit")
     */
    public function edit($slug, Request $request) {
        
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
    
        $formOrRedirect = $this->AddOrEdit($trick, $request);
        if(isset($formOrRedirect['redirect'])){
            return $this->redirectToRoute('show_trick', [
                'slug' => $trick->getSlug()
            ]);
        }
    
        $form = $formOrRedirect['form'];
        $this->redirectToRoute('show_trick', [
            'slug' => $trick->getSlug()
        ]);
        
        return $this->render('trick/edit.html.twig', [
           'form' => $form->createView(),
           'trick' => $trick
        ]);
    }
    
    
    private function AddOrEdit(Trick $trick, Request $request) {
    
        $trick->setUpdatedAt(new \DateTime());
        
        //todo à remplacer avec $request->getUser();
        $trick->setUser($this->userRepository->findOneBy(['id'=>1]));
        
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) {
            if(!empty($trick->getResource())){
                /** @var UploadedFile $file */
                $file = new UploadedFile($trick->getResource(),'tmp');
                $trick->setResource(
                    $this->fileUploader->upload($file)
                );
            }
            
            $trick = $this->trickRepository->save($form->getData());
            return ['redirect'=>true];
        }
        
        return ['form' => $form];
    }
}
