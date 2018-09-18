<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    public function __construct(
        CategoryRepository $categoryRepository,
        UserRepository $userRepository
)
    {
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @Route("/category/new/{slug}", name="category_new", defaults={"slug"=""})
     */
    public function new($slug, Request $request)
    {
            $category = new Category();
            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());
            $formOrRedirect = $this->AddOrEdit($category, $request);

            if(isset($formOrRedirect['redirect']))
            {
                if(!empty($slug)){
                    return $this->redirectToRoute('trick_edit',[
                        'slug' => $slug
                    ]);
                } else {
                    return $this->redirectToRoute('trick_new');
                }
            }
            
            
            return $this->render('category/add.html.twig', [
                'form' => $formOrRedirect['form']->createView()
            ]);
    }
    
    /**
     * @Route("/category/edit/{id}/{slug}", name="category_edit", defaults={"slug"=""})
     */
    public function edit($id,$slug, Request $request)
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $category->setUpdatedAt(new \DateTime());
        $category->setUser($this->userRepository->findOneBy(['id'=>1]));
        $formOrRedirect = $this->AddOrEdit($category, $request);
        if(isset($formOrRedirect['redirect']))
            if(isset($formOrRedirect['redirect']))
            {
                if(!empty($slug)){
                    return $this->redirectToRoute('trick_edit',[
                        'slug' => $slug
                    ]);
                } else {
                    return $this->redirectToRoute('trick_new');
                }
            }
    
    
        return $this->render('category/edit.html.twig', [
           'form' => $formOrRedirect['form']->createView()
        ]);
    }
    
    /**
     * @Route("/category/delete/{id}/{slug}", name="category_delete", defaults={"slug"=""})
     */
    public function delete($id, $slug)
    {
        try{
            $this->categoryRepository->delete($id);
        }catch (\Exception $e){
            $this->addFlash('failed','Cette catégorie est déja utilisée. Impossible donc de la supprimer.');
        }
        
        if(!empty($slug)){
            return $this->redirectToRoute('trick_edit',['slug' => $slug]);
        }
        return $this->redirectToRoute('trick_new');
    }
    
    private function AddOrEdit(Category $category, Request $request) {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->save($category);
            return ['redirect' => true];
        }
        return ['form' => $form];
    }
}
