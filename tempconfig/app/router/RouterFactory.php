<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\SimpleRouter;
use Nette\Application\Routers\CliRouter;


class RouterFactory
{

	private $container;

	public function __construct(Nette\DI\Container $container) {
         $this->container = $container;
	}
	

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		// alternativně zapsáno polem
	
		$router = new RouteList();
		if ($container->parameters['consoleMode']) {
			$router[] = new CliRouter(array('action' => 'Speed:savecron'));			
        } else {
		$router[] = new Route('<presenter>/<action>/[<sensorID>]', 'Relremote:default');		
		}
		return $router;
	}

}
