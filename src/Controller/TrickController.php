<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\ResourceRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\SendToken;
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
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var SendToken
     */
    private $sendToken;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * @var ResourceRepository
     */
    private $resourceRepository;
    
    public function __construct(
        TrickRepository $trickRepository,
        CommentRepository $commentRepository,
        FileUploader $fileUploader,
        SendToken $sendToken,
        UserRepository $userRepository,
        ResourceRepository $resourceRepository
    )
    
    {
    
        $this->trickRepository = $trickRepository;
        $this->commentRepository = $commentRepository;
        $this->fileUploader = $fileUploader;
        $this->sendToken = $sendToken;
        $this->userRepository = $userRepository;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @Route("/{page}", name="trick", defaults={"page"=1})
     */
    public function index($page): \Symfony\Component\HttpFoundation\Response
    {
        $page = $page - 1;
        $trickPaginator = $this->trickRepository->paginator($page);
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickPaginator,
            'currentPage' => $page + 1
        ]);
    }
    
    /**
     * @Route("/trick/show/{slug}", name="show_trick")
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, $slug): \Symfony\Component\HttpFoundation\Response
    {
        
        $trick = $this->trickRepository->findOneBy([
            'slug' => $slug
        ]);
        
        $comment = new Comment();
        $comment->setTrick($trick);
        $form = $this->createForm(CommentType::class, $comment);
    
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTime());
            $this->commentRepository->save($comment);
            unset($comment, $form);
    
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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

        return $this->render('trick/edit.html.twig', [
           'form' => $form->createView(),
           'trick' => $trick
        ]);
    }
    
    
    private function AddOrEdit(Trick $trick, Request $request): array
    {
    
        $trick->setUpdatedAt(new \DateTime());
        $trick->setUser($this->getUser());
        
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if(!empty($trick->getResources())) {
                /** @var Resource $resource */
                
                foreach ($trick->getResources() as $resource) {
                    $resource->setTrick($trick);
                    if((int) $resource->getId()) {
                        $resource->setName($resource->getFilename());
                        continue;
                    }
                    
                    if($resource->getName() === null){ break;}
                    
                    $filenamePath = $resource->getName();
                    
                    /** @var UploadedFile $file */
                    $file = new UploadedFile($filenamePath,'tmp');
                    $type = $this->fileUploader->getFileType($file->guessExtension());
                    $filename = $this->fileUploader->upload($file);
                    $resource->setName($filename);
                    $resource->setType($type);
                }
            }
            $this->trickRepository->save($form->getData());
            return ['redirect'=>true];
        }
        
        return ['form' => $form];
    }
    
    /**
     * @Route("/trick/delete/{slug}", name="trick_delete")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($slug): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        
        /** @var Trick $trick */
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
        
        /** @var Resource $resource */
        foreach ($trick->getResources() as $resource) {
            $this->fileUploader->deleteFile($resource->getName());
            $this->resourceRepository->delete($resource);
        }
        
        foreach($trick->getComments() as $comment) {
            $this->commentRepository->delete($comment);
        }
        
        $this->trickRepository->delete($trick);
        
        return $this->redirectToRoute('trick');
    }
}
