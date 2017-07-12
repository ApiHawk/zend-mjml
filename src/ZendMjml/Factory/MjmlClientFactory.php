<?php

namespace ZendMjml\Factory;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Subscriber\Cache\CacheSubscriber;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MjmlClientFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return GuzzleClient
     */
    public function __invoke(ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        $config = $container->get('Config')['mjml'];

        $client = new GuzzleClient([
            'base_url' => rtrim($config['mjmlServiceUrl'], '/'),
            'defaults' => [
                'timeout' => $config['timeout'],
                'connect_timeout' => $config['connectTimeout'],
            ],
        ]);

        CacheSubscriber::attach($client);

        return $client;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return GuzzleHttp\Client
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['mjml'];
        $client = new GuzzleClient([
            'base_url' => rtrim($config['mjmlServiceUrl'], '/'),
            'defaults' => [
                'timeout' => $config['timeout'],
                'connect_timeout' => $config['connectTimeout'],
            ],
        ]);

        CacheSubscriber::attach($client);

        return $client;
    }
}
