<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class TrickType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction(
                $this->container->get('router')->generate('trick_save')
            )
            ->add('id', HiddenType::class)
            ->add('name', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'description'
            ])
            ->add('slug', TextType::class,[
                'label' => 'Le slug'
            ])
            ->add('resource', FileType::class, [
                'label' => 'Image/Video'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => function($category) {
                    return $category->getName();
                }
            ])
            ->add('save',SubmitType::class, [
                'label' => 'Envoyer'
            ]);
        
        $builder->get('resource')->addModelTransformer(new CallbackTransformer(function($resource) {
            if(!is_null($resource)) {
            return new UploadedFile(
                $this->container->getParameter('files_directory') .'/'.  $resource,
                'tmp');
            }
            return $resource;
        },function($resource){
            return $resource;
        }));
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
