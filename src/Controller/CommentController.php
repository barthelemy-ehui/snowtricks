<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
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
    
    public function __construct(
        TrickRepository $trickRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    )
    {
    
        $this->trickRepository = $trickRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }
    
    /**
     * @Route("/comment/save", name="comment_save")
     */
    public function save(Request $request)
    {
        
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // todo à remplacer par $request->getUser()
            $comment->setUser($this->userRepository->findOneBy(['id'=>21]));
            $comment->setCreatedAt(new \DateTime());
            $this->commentRepository->save($comment);
        }
        
        return $this->redirectToRoute('show_trick', [
            'slug' => $comment->getTrick()->getSlug()
        ]);
    }
    
}
