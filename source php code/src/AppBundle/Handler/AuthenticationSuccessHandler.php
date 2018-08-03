<?php
declare(strict_types = 1);

namespace App\AppBundle\Handler;

use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    protected $container;

    public function __construct(
        HttpUtils $httpUtils,
        ContainerInterface $cont,
        array $options
    )
    {
        parent::__construct($httpUtils, $options);
        $this->container = $cont;
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token
    )
    {
        $url = $this->determineTargetUrl($request);

        return $this->httpUtils
            ->createRedirectResponse(
                $request,
                $this->determineTargetUrl($request)
            );
    }
}
