<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Trick;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
    
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Commentaire'
            ])
            ->add('trick', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
        
        $builder->get('trick')->addModelTransformer(new CallbackTransformer(function($trick){
            if(null === $trick){
                return null;
            }
            return $trick->getSlug();
        },function($trick){
           $em = $this->container->get('doctrine.orm.default_entity_manager');
           $trick = $em->getRepository(Trick::class)->findOneBy([
               'slug' => $trick
           ]);
            return $trick;
        }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
