<?php

namespace Larafly\Feishu;

use Larafly\Feishu\Support\AppClient;

class Message extends AppClient
{
    public function text($receive_id, $message)
    {
        $url = 'im/v1/messages?receive_id_type=open_id';
        $content = json_encode([
            'text' => $message,
        ]);
        $data = [
            'receive_id' => $receive_id,
            'content' => $content,
            'msg_type' => 'text',
        ];

        return $this->post($url, $data);
    }

    public function card($receive_id, $template_id,?array $template_variable=[])
    {
        $url = 'im/v1/messages?receive_id_type=open_id';
        $content = json_encode([
            'type'=>'template',
            'data'=>[
                'template_id'=>$template_id,
                'template_variable'=>$template_variable
            ]
        ]);

        $data = [
            'receive_id' => $receive_id,
            'content' => $content,
            'msg_type' => 'interactive',
        ];

        return $this->post($url, $data);
    }
}
