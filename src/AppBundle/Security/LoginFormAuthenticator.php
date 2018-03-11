<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RouterInterface
     */
    protected $router;


    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home', ['template' => 'surgery-date']));
    }


    public function getCredentials(Request $request)
    {
        $isLogininSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if(!$isLogininSubmit){
            return null;
        }

        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);
        $data = $form->getData();


        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        return $this->em->getRepository('AppBundle:Patient')->findOneBy( ['email' => $email]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if($credentials['password'] === $user->getPassword()){
                return true;
        }
        return false;
    }

}