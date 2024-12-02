<?php

namespace Larafly\Feishu;

use Larafly\Feishu\Exceptions\RequestException;
use Larafly\Feishu\Support\BaseClient;

/**
 * @see https://open.feishu.cn/document/uQjL04CN/ucDOz4yN4MjL3gzM
 */
class Auth extends BaseClient
{
    protected ?string $state = null;

    public function __construct(string $app_id, string $app_secret,protected string $redirect_uri)
    {
        parent::__construct($app_id, $app_secret);
    }

    protected function getAuthUrl(): string
    {
        $query = $this->getCodeFields() + ($this->state ? ['state' => $this->state] : []);

        return $this->baseUrl.'authen/v1/index'.'?'.\http_build_query($query, '', '&');

    }
    protected function getCodeFields(): array
    {
        return [
            'redirect_uri' => $this->redirect_uri,
            'app_id'=> $this->app_id,
        ];
    }

    /**
     * get user info
     * @throws RequestException
     */
    public function getUserByCode(string $code): array
    {
        $token = $this->getAccessToken($code);
        if($token){
            $headers = ['Authorization' => 'Bearer '.$token];
            $response = $this->get('/authen/v1/user_info', $headers);
            if ((int) $response['code'] != 0) {
                throw new RequestException('Fail to get user info'.$response['msg']);
            }

            return $response['data'];
        }
        throw new RequestException('Fail to get user token');
    }

    public function getAccessToken(string $code)
    {
        try {
            $params = [
                'grant_type' => 'authorization_code',
                'client_id' => $this->app_id,
                'client_secret' => $this->app_secret,
                'code' => $code,
                'redirect_uri' => urlencode($this->redirect_uri),
            ];
            $response = $this->post('/authen/v2/oauth/token', $params);

            if (isset($response['code']) && (int) $response['code'] == 0 && isset($response['access_token']) && $response['access_token']) {
                return $response['access_token'];
            }

            throw new \Exception($response['error_description'] ?? 'get user_access_token error');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function redirect(?string $redirect_uri = null): string
    {
        if (! empty($redirect_uri)) {
            $this->withRedirectUri($redirect_uri);
        }

        return $this->getAuthUrl();
    }

    public function withRedirectUri(string $redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;

        return $this;
    }

}
