<?php

namespace Larafly\Feishu;

use Larafly\Feishu\Contracts\Config;
use Larafly\Feishu\Exceptions\InvalidArgumentException;
use Larafly\Feishu\Support\AppClient;

class Application
{
    private Config $config;

    private static ?Application $instance = null;

    protected ?AccessToken $accessToken = null;

    protected ?Auth $auth = null;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(array|Config|null $config = null)
    {
        if (! $config) {
            $config = config('feishu');
            if (! $config || ! is_array($config)) {
                throw new \Exception('feishu config not found');
            }
        }
        $this->config = is_array($config) ? new \Larafly\Feishu\Config($config) : $config;

    }

    /**
     * get Application Object
     *
     * @throws InvalidArgumentException
     */
    public static function getInstance(array|Config|null $config = null): Application
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    /**
     * get access token
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createAccessToken(): AccessToken
    {
        if (! $this->accessToken) {
            $this->accessToken = new AccessToken(
                app_id: $this->config->get('app_id'),
                app_secret: $this->config->get('app_secret'),
            );
        }

        return $this->accessToken;
    }

    /**
     * create Auth
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createAuth(): Auth
    {
        if (! $this->auth) {
            $this->auth = new Auth(
                app_id: $this->config->get('app_id'),
                app_secret: $this->config->get('app_secret'),
                redirect_uri: $this->config->get('redirect_uri'),
            );
        }

        return $this->auth;
    }

    /**
     * get message
     *
     * @throws Exceptions\RequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createMessage(): Message
    {
        return new Message(
            app_id: $this->config->get('app_id'),
            app_secret: $this->config->get('app_secret'),
            access_token: $this->createAccessToken()->getToken(),
        );
    }

    /**
     * get client
     *
     * @throws Exceptions\RequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createClient(): AppClient
    {
        return new AppClient(
            app_id: $this->config->get('app_id'),
            app_secret: $this->config->get('app_secret'),
            access_token: $this->createAccessToken()->getToken(),
        );
    }
}
