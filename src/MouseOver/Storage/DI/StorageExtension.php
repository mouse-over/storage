<?php
/**
 * This file is part of IntelliJ IDEA
 *
 * @author Vaclav Prokes (vprokes@mouse-over.net)
 */


namespace MouseOver\Storage\DI;


use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\Statement;

/**
 * Class StorageExtension
 *
 * @package MouseOver\Storage\DI
 */
class StorageExtension extends CompilerExtension
{
    private $defaults = [
        'module' => 'files'
    ];

    /**
     * Load DI configuration
     *
     * @return void
     */
    public function loadConfiguration()
    {
        $container = $this->getContainerBuilder();
        $config = $this->getConfig($this->defaults);
        $this->loadRoutes($container, $config);
    }

    /**
     * Routes definition
     *
     * @param ContainerBuilder $container Container
     * @param array            $config    Config
     *
     * @return void
     */
    protected function loadRoutes(ContainerBuilder $container, $config)
    {
        $container->addDefinition($this->prefix('storages'))
            ->setClass('MouseOver\Storage\Application\StorageList');

        $container->addDefinition($this->prefix('responder'))
            ->setClass('MouseOver\Storage\Application\StorageResponder');

        $container->addDefinition($this->prefix('linkResolver'))
            ->setClass('MouseOver\Storage\Application\StorageLinkResolver', [$config['module']]);

        $container->getDefinition('nette.latteFactory')
            ->addSetup('MouseOver\Storage\Application\StorageMacros::install($service->getCompiler(), ?)', array($this->prefix('@linkResolver')));

        $routesList = $container->addDefinition($this->prefix('storageRoutes')) // no namespace for back compatibility
            ->setClass('Nette\Application\Routers\RouteList')->setAutowired(false);



        $routesList->addSetup(
            '$service[] = new Nette\Application\Routers\Route(?, function ($presenter, $storage, $file) { return ?->handle($storage, $file, $presenter->request->getParameters()); })',
            array(
                $config['module'].'/<storage>/<file [a-zA-Z0-9\-_.\/]+>',
                $this->prefix('@responder')
            )
        );

        $container->getDefinition('router')
            ->addSetup(
                'MouseOver\Storage\DI\StorageExtension::prependTo($service, ?)',
                [$this->prefix('@storageRoutes')]
            );


    }

    public static function prependTo(IRouter &$router, $route)
    {
        if (!$router instanceof RouteList) {
            throw new \Exception(
                'Router must be an instance of Nette\Application\Routers\RouteList'
            );
        }

        $router[] = $route; // need to increase the array size

        $lastKey = count($router) - 1;
        foreach ($router as $i => $route) {

            if ($i === $lastKey) {
                break;
            }
            $router[$i + 1] = $route;
        }

        $router[0] = $route;
    }

}