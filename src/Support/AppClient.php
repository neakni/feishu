<?php

namespace Larafly\Feishu\Support;

class AppClient extends BaseClient
{
    public function __construct(string $app_id, string $app_secret,protected $access_token)
    {
        parent::__construct($app_id, $app_secret);
    }

    public function post($url, ?array $data = [], ?array $header = []): array
    {
        $header = array_merge($header,[
            'Authorization' => 'Bearer '.$this->access_token,
        ]);

        return parent::post($url, $data, $header);
    }

    public function get($url, ?array $data = [], ?array $header = []): array
    {
        $header = array_merge($header,[
            'Authorization' => 'Bearer '.$this->access_token,
        ]);

        return parent::get($url, $header);
    }

}
