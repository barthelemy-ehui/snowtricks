<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var ValidatorInterface
     */
    private $validator;
    
    public function __construct(TrickRepository $trickRepository, UserRepository $userRepository, ValidatorInterface $validator)
    {
    
        $this->trickRepository = $trickRepository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
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
    public function show($slug) {
        
        $trick = $this->trickRepository->findOneBy([
            'slug'=>$slug
        ]);
        
        $comment = new Comment();
        $comment->setTrick($trick);
        
        $form = $this->createForm(CommentType::class, $comment);
        
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/trick/add", name="trick_add")
     */
    public function add(){
        
        $trickForm = $this->createForm(TrickType::class);
        return $this->render('trick/add.html.twig',[
            'form' => $trickForm->createView()
        ]);
    }
    
    
    /**
     * @Route("/trick/save", name="trick_save")
     */
    public function save(Request $request) {
        
        $trick = new Trick();
        $trick->setCreatedAt(new \DateTime());
        $trick->setUpdatedAt(new \DateTime());
        
        //todo à remplacer avec $request->getUser();
        $trick->setUser($this->userRepository->findOneBy(['id'=>21]));
        
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
    
        // todo: gestion des retours d'erreurs
        $errors = $this->validator->validate($trick);
        if( count($errors) > 0 ) {
            return $this->redirectToRoute('trick_add');
        }
    
        if($form->isSubmitted() && $form->isValid()) {
            
            // todo à déplacer dans un service pour ensuite être utilisé dans la version modifier
            if(!empty($trick->getResource())){
                /** @var UploadedFile $file */
                $file = new UploadedFile($trick->getResource(),'tmp');
                $fileName = $this->generateUniqueFileName() . '.' .$file->guessExtension();
                $file->move(
                  $this->getParameter('files_directory'),
                  $fileName
                );
                $trick->setResource($fileName);
            }
            
            $trick = $this->trickRepository->save($form->getData());
            return $this->redirectToRoute('show_trick', [
                'slug' => $trick->getSlug()
            ]);
        }
        
        return $this->redirectToRoute('trick_add');
    }
    
    
    /**
     * @Route("/trick/edit/{slug}", name="trick_edit")
     */
    public function edit($slug) {
        
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(TrickType::class, $trick);
        return $this->render('trick/edit.html.twig', [
           'form' => $form->createView()
        ]);
    }
    
    private function generateUniqueFileName()
    {
        return md5(uniqid('file',false));
    }
}
