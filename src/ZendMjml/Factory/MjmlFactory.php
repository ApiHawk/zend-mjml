<?php

namespace ZendMjml\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZendMjml\Service\Mjml;
use Zend\ServiceManager\ServiceLocatorInterface;

class MjmlFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Mjml
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        $config        = $container->get('Config')['mjml'];
        $httpClient    = $container->get('Client\Mjml');
        $renderer      = $container->get('Zend\View\Renderer\RendererInterface');
        $adapterConfig = $config['transportAdapter'];
        $transport     = null;

        if (is_string($adapterConfig)) {
            $transport = $container->get($adapterConfig);
        } elseif (is_array($adapterConfig)) {
            $transport = \Zend\Mail\Transport\Factory::create($adapterConfig);
        }
        if (null === $transport) {
            throw new \Exception('Transport Adapter cannot be found.');
        }

        return new Mjml($httpClient, $renderer, $transport);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ZendMjml\Service\Mjml
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['mjml'];
        $httpClient = $serviceLocator->get('Client\Mjml');
        $renderer = $serviceLocator->get('Zend\View\Renderer\RendererInterface');

        $adapterConfig = $config['transportAdapter'];
        $transport = null;
        if (is_string($adapterConfig)) {
            $transport = $serviceLocator->get($adapterConfig);
        } elseif (is_array($adapterConfig)) {
            $transport = \Zend\Mail\Transport\Factory::create($adapterConfig);
        }
        if (null === $transport) {
            throw new \Exception('Transport Adapter cannot be found.');
        }

        return new Mjml($httpClient, $renderer, $transport);
    }
}
