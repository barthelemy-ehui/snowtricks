<?php

namespace App\Form;

use App\Entity\Resource;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ResourceType extends AbstractType
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
            ->add('id', HiddenType::class)
            ->add('filename', HiddenType::class)
            ->add('name', FileType::class, [
            'label' => false,
            'required' => true,
            'constraints' => [
                new File()
            ]
        ])
       ->add('principal', HiddenType::class, [
           'label' => false
       ]);
       
        $builder->get('name')->addModelTransformer(new CallbackTransformer(function($name){
            if($name !== null) {
                return new UploadedFile(
                    $this->container->getParameter('files_directory') . '/' . $name,
                'tmp');
            }
        }, function($name){
            return $name;
        }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
        ]);
    }
}
