<?php

namespace Larafly\Feishu\Contacts;

use Larafly\Feishu\Support\AppClient;

class User extends AppClient
{
    public function getOpenIdByMobiles(array $mobiles): array
    {
        $mobiles = implode('&', $mobiles);

        $res = $this->get('/user/v1/batch_get_id?mobiles='.$mobiles);
        if ($res && $res['code'] == 0) {
            return $res['data'];
        }

        return [];
    }

    public function getOpenIdByEmails(array $emails): array
    {
        $emails = implode('&', $emails);

        $res = $this->get('/user/v1/batch_get_id?emails='.$emails);
        if ($res && $res['code'] == 0) {
            return $res['data'];
        }

        return [];
    }
}
