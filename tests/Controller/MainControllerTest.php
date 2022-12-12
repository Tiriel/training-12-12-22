<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class MainControllerTest extends WebTestCase
{
    private static KernelBrowser $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = static::createClient();
    }

    /**
     * @dataProvider provideUrisAndCode
     */
    public function testPublicUrlsAreOk(string $method, string $url): void
    {
        static::$client->request($method, $url);

        $this->assertSame(200, static::$client->getResponse()->getStatusCode());
    }

    public function provideUrisAndCode(): \Generator
    {
        $router = static::getContainer()->get(RouterInterface::class);
        $collection = $router->getRouteCollection();
        static::ensureKernelShutdown();

        foreach ($collection as $routeName => $route) {
            /** @var Route $route */
            $variables = $route->compile()->getVariables();
            if (count($variables) > 0 ) {
                if (count(array_diff($variables, $route->getDefaults())) > 0) {
                    continue;
                }
            }
            if ([] === $methods = $route->getMethods()) {
                $methods[] = 'GET';
            }
            foreach ($methods as $method) {
                $path = $router->generate($routeName);
                yield "$method $path" => [$method, $path];
            }
        }
    }
}
