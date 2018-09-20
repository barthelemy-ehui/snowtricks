<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
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
        $defaultPicture = null;
        if($this->container->get('security.token_storage')->getToken()->getUser() !== 'anon.') {
            $defaultPicture = $this->container->get('security.token_storage')->getToken()->getUser()->getPicture();
        }
        
        $builder
            ->add('id', HiddenType::class)
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo de profil',
                'empty_data' => $defaultPicture
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'label' => ' ',
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                    'required' => true,
                    'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('currentPassword', PasswordType::class,[
                    'label' => 'Mot de passe actuel',
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('changePassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'label' => ' ',
                    'first_options' => ['label' => 'Noueau mot de passe'],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                    'constraints' => [
                        new NotBlank()
                    ]
                ]);
        
        if(isset($options['attr']['profile']) && $options['attr']['profile']) {
            $builder->remove('plainPassword');
            $builder->remove('currentPassword');
            $builder->remove('changePassword');
            $builder->get('picture')->addModelTransformer(new CallbackTransformer(function($picture) {
            if($picture !== null) {
                return new UploadedFile(
                    $this->container->getParameter('files_directory') . '/' . $picture,
                    'tmp'
                );
            }
            }, function($picture) {
                return $picture;
            }));
        }
        
        
       if(isset($options['attr']['change_password']) && $options['attr']['change_password']) {
            $builder->remove('id');
            $builder->remove('firstname');
            $builder->remove('lastname');
            $builder->remove('username');
            $builder->remove('email');
            $builder->remove('picture');
            $builder->remove('plainPassword');
        }
    
        if(isset($options['attr']['registration']) && $options['attr']['registration']) {
            $builder->remove('currentPassword');
            $builder->remove('changePassword');
            $builder->remove('picture');
       }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
