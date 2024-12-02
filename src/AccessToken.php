<?php

declare(strict_types=1);

namespace Larafly\Feishu;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;
use Larafly\Feishu\Exceptions\RequestException;
use Larafly\Feishu\Support\BaseClient;

use function intval;
use function is_string;
use function sprintf;

class AccessToken extends BaseClient
{
    public function getKey(): string
    {
        return sprintf('feishu.tenant_access_token.%s', $this->app_id);
    }

    /**
     * @throws GuzzleException
     * @throws RequestException
     */
    public function getToken(): string
    {
        $token = Cache::get($this->getKey());

        if ($token && is_string($token)) {
            return $token;
        }

        return $this->refresh();
    }

    /**
     * @return array<string, string>
     */
    #[ArrayShape(['tenant_access_token' => 'string'])]
    public function toQuery(): array
    {
        return ['tenant_access_token' => $this->getToken()];
    }

    /**
     * @throws GuzzleException
     * @throws RequestException
     */
    public function refresh(): string
    {
        $data = [
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
        ];
        $response = $this->post(url: 'auth/v3/tenant_access_token/internal', data: $data);
        Cache::put($this->getKey(), $response['tenant_access_token'], intval($response['expire']));

        return $response['tenant_access_token'];
    }
}
