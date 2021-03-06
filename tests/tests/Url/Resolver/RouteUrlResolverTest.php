<?php

namespace Concrete\Tests\Url\Resolver;

use Concrete\TestHelpers\Url\Resolver\ResolverTestCase;

class RouteUrlResolverTest extends ResolverTestCase
{
    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected $routeList;

    public function setUp():void
    {
        parent::setUp();

        $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();

        $path_url_resolver = $app->make('Concrete\Core\Url\Resolver\PathUrlResolver');
        $routes = $app->make('Symfony\Component\Routing\RouteCollection');
        $context = $app->make('Symfony\Component\Routing\RequestContext');
        $generator = $app->make('Symfony\Component\Routing\Generator\UrlGenerator', ['routes' => $routes, 'context' => $context]);

        $this->urlResolver = $app->make('Concrete\Core\Url\Resolver\RouteUrlResolver',
            ['path_url_resolver' => $path_url_resolver, 'generator' => $generator, 'route_list' => $routes]);

        $this->routeList = $routes;
    }

    /**
     * Make sure that we can actually resolve a basic route.
     */
    public function testRoute()
    {
        $path = '/named/route/path';
        $name = 'named_route';
        $route = new \Concrete\Core\Routing\Route($path);
        $route->setPath($path);

        $this->routeList->add($name, $route);
        $url = $this->canonicalUrlWithPath($path);

        $this->assertEquals((string) $url, (string) $this->urlResolver->resolve(["route/{$name}"]));
    }

    /**
     * Test routes that have inline parameters.
     */
    public function testRouteWithParameters()
    {
        $path = '/named/{parameter}/route';
        $name = 'named_route';
        $value = uniqid();

        $route = new \Concrete\Core\Routing\Route($path);
        $route->setPath($path);

        $this->routeList->add($name, $route);
        $url = $this->canonicalUrlWithPath(str_replace('{parameter}', $value, $path));

        $this->assertEquals((string) $url, (string) $this->urlResolver->resolve(["route/{$name}", [
            'parameter' => $value,
        ]]));
    }

    /**
     * Test not finding a named route in the list.
     */
    public function testRouteMiss()
    {
        $resolved = uniqid();
        $this->assertEquals($this->urlResolver->resolve(['route/miss'], $resolved), $resolved);
    }

    /**
     * Test not matching the expected syntax.
     */
    public function testNoMatch()
    {
        $resolved = uniqid();
        $this->assertEquals($this->urlResolver->resolve(['no match'], $resolved), $resolved);
    }
}
