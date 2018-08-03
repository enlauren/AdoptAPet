<?php
declare(strict_types = 1);

namespace App\AppBundle\Resources\Twig;

use App\AppBundle\Interfaces\LinkableInterface;
use InvalidArgumentException;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class UrlExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url', array($this, 'generate')),
        );
    }

    /**
     * @param LinkableInterface $object
     * @param array             $params
     * @param bool              $absolute
     * @return string
     */
    public function generate(LinkableInterface $object, array $params = [], $absolute = false)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException("Argument passed to generate must be type object.");
        }

        if (true === $absolute) {
            $absoluteValue = Router::ABSOLUTE_URL;
        } else {
            $absoluteValue = Router::ABSOLUTE_URL;
        }

        return $this->router->generate(
            $object->getRoute(),
            array_merge(
                [
                    $object->getIdentifierName() => $object->getIdentifier()
                ],
                $params
            ),
            $absoluteValue
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'url_extension';
    }
}
