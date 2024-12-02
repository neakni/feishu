<?php

namespace Larafly\Feishu\Support;

use GuzzleHttp\Client;
use Larafly\Feishu\Exceptions\RequestException;

class BaseClient
{
    protected Client $httpClient;
    protected string $baseUrl = 'https://open.feishu.cn/open-apis/';

    public function __construct(
        protected string $app_id,
        protected string $app_secret,
    ) {
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 5.0,
        ]);
    }

    /**
     * @throws RequestException|\GuzzleHttp\Exception\GuzzleException
     */
    public function post($url, ?array $data = [],?array $header =[]): array
    {
        $url = trim($url, '/');
        $header = array_merge($header,['Content-Type'=>'application/json; charset=utf-8']);
        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => $header,
                'json' => $data,
            ])->getBody()->getContents();

            return json_decode($response, true);
        } catch (\Exception $exception) {
            throw new RequestException('Failed to request: '.$exception->getMessage());
        }
    }

    public function get($url, ?array $header =[]): array
    {
        $url = trim($url, '/');
        $header = array_merge($header,['Content-Type'=>'application/json; charset=utf-8']);
        try {
            $response = $this->httpClient->request('GET', $url, [
                'headers' => $header,
            ])->getBody()->getContents();

            return json_decode($response, true);
        } catch (\Exception $exception) {
            throw new RequestException('Failed to request: '.$exception->getMessage());
        }
    }
}
