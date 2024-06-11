<?php

namespace App\Tests\Smoke;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class SmokeControllerTest extends WebTestCase
{
    protected static KernelBrowser $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = static::createClient();
    }

    /**
     * @dataProvider provideMethodAndUri
     * @group smoke
     */
    public function testPublicHomepageIsOk(string $method, string $uri): void
    {
        static::$client->request($method, $uri);

        $this->assertSame(200, static::$client->getResponse()->getStatusCode());
    }

    public function provideMethodAndUri(): iterable
    {
        /** @var RouterInterface $router */
        $router = static::getContainer()->get(RouterInterface::class);
        $collection = $router->getRouteCollection();
        static::ensureKernelShutdown();

        foreach ($collection as $routeName => $route) {
            /** @var Route $route */
            $variable = $route->compile()->getVariables();
            if (count(array_diff($variable, array_keys($route->getDefaults()))) > 0) {
                continue;
            }
            if ([] === $methods = $route->getMethods()) {
                $methods[] = 'GET';
            }

            foreach ($methods as $method) {
                if ($method === 'POST' && $routeName === 'app_security_login') {
                    continue;
                }
                yield "$method $routeName" => [$method, $router->generate($routeName)];
            }
        }
    }
}
