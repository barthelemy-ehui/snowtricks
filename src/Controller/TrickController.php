<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends Controller
{
    
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    
    public function __construct(TrickRepository $trickRepository)
    {
    
        $this->trickRepository = $trickRepository;
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
     * @Route("/trick/{slug}", name="show_trick")
     */
    public function show($slug) {
        
        $trick = $this->trickRepository->findOneBy([
            'slug'=>$slug
        ]);
        
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }
}
