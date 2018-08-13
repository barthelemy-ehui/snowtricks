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
     * @Route("/category/new/{slug}", name="category_new")
     */
    public function new($slug, Request $request)
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTime());
        $category->setUpdatedAt(new \DateTime());
        $formOrRedirect = $this->AddOrEdit($category, $request);
        if(isset($formOrRedirect['redirect']))
        {
            return $this->redirectToRoute('show_trick',[
                'slug' => $slug
            ]);
        }
        return $this->render('category/add.html.twig', [
            'form' => $formOrRedirect['form']
        ]);
    }
    
    /**
     * @Route("/category/edit/{slug}", name="category_edit")
     */
    public function edit($slug, Request $request)
    {
        $category = new Category();
        $category->setUpdatedAt(new \DateTime());
        $category->setUser($this->userRepository->findOneBy(['id'=>1]));
        $formOrRedirect = $this->AddOrEdit($category, $request);
        if(isset($formOrRedirect['redirect']))
        {
            return $this->redirectToRoute('show_trick',[
                'slug' => $slug
            ]);
        }

        $form = $formOrRedirect['form'];
        return $this->render('category/edit.html.twig', [
           'form' => $form
        ]);
    }
    
    private function AddOrEdit(Category $category, Request $request) {
        
        $form = $this->createForm(CategoryType::class, $category);
        if($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->save($category);
            return ['redirect' => true];
        }
        return ['form' => $form];
    }
    
    /**
     * @Route("/category/delete/{slug}/{id}", name="category_delete")
     */
    public function delete($id, $slug)
    {
        $this->categoryRepository->delete($id);
        return $this->redirectToRoute('show_trick',['slug' => $slug]);
    }
    
}
