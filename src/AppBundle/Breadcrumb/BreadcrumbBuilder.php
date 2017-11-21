<?php

namespace AppBundle\Breadcrumb;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

class BreadcrumbBuilder
{
    /**
     * @var Router $router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function addItem(string $route, string $title = ''): array
    {
        $url = $this->router->generate($route, [], UrlGeneratorInterface::ABSOLUTE_URL);

        return [$url => $title];
    }

    public function addItemList(array $routeList, string $currentRoute)
    {
        $breadCrumbItems = [];

        foreach ($routeList as $route => $title)
        {
            $breadCrumbItems[] = [$this->router->generate($route, [], UrlGeneratorInterface::ABSOLUTE_URL) => $title];

            if($route == $currentRoute){
                return $breadCrumbItems;
            }
        }
        return $breadCrumbItems;

    }

}