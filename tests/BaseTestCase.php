<?php

namespace Isswp101\Persimmon\Test;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Elasticsearch\Client;
use Monolog\Logger;
use Orchestra\Testbench\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $es;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadDotenv();

        $this->es = app(Client::class);
    }

    /**
     * Load Dotenv.
     */
    protected function loadDotenv()
    {
        $dotenv = new Dotenv(__DIR__);
        try {
            $dotenv->load();
        } catch (InvalidPathException $e) {
            // It's workaround for Travis CI
        }
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->singleton(Client::class, function () {
            $params = [
                'hosts' => [
                    env('ELASTICSEARCH_HOSTS', '')
                ],
                'logPath' => 'app/storage/logs',
                'logLevel' => Logger::INFO,
                'connectionParams' => [
                    'auth' => [
                        env('ELASTICSEARCH_AUTH_USER', ''),
                        env('ELASTICSEARCH_AUTH_PASS', ''),
                        'Basic'
                    ]
                ]
            ];
            return new Client($params);
        });
    }

    /**
     * Sleep.
     *
     * @param int $seconds
     */
    protected function sleep($seconds = 1)
    {
        sleep($seconds);
    }
}
