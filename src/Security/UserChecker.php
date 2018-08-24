<?php
namespace App\Security;


use App\Exceptions\AccountMustBeActive;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @var SessionInterface
     */
    private $flashBag;
    /**
     * @var RouterInterface
     */
    private $router;
    
    public function __construct(FlashBagInterface $flashBag, RouterInterface $router)
    {
    
        $this->flashBag = $flashBag;
        $this->router = $router;
    }
    
    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user->getisActive()){
            $this->flashBag->add('failed','Veuillez activer votre compte grâce au lien envoyé par e-mail.');
            return new RedirectResponse($this->router->generate('trick'));
        }
    }
    
    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}