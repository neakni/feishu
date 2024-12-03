<?php

use Larafly\Feishu\Application;

it('get application', function () {
    $config = [
        "app_id" => "1234",
        "app_secret" => "AT7rW8JOJUFOVrWSdgh5XdQ3Akia8K1r"
    ];
    $application = Application::getInstance($config);

    dump($application->getConfig());
});

it('get access token', function () {

    $access_token = Application::getInstance()->createAccessToken();

    dump($access_token->getToken());
});

it('get user info', function () {
    $auth = Application::getInstance()->createAuth();
    $user_access_token ='ef5s4a8e714f407cb4a42549e17ecef2';
    $user_info = $auth->getUserByCode($user_access_token);
    expect($user_info)->notEmpty();
});

it('send text message', function () {
    $message = Application::getInstance()->createMessage();
    $content = "send a message";
    $receive_id = 'ou_ce8eaa702c9f310401a2b21f2a00b13d';
    $response = $message->text($receive_id,$content);
    expect($response['code'])->toBe(0);
});

it('send card message', function () {
    $message = Application::getInstance()->createMessage();
    $receive_id = 'ou_ce8eaa702c9f310401a2b21f2a00b13d';
    $template_id = "AAqjjGlz51b4V";
    $template_variable = [
        'title'=>"title",
        'id'=>"123",
        'content'=>"this is a message\n created_at:2024-01-01 12:12:12",
        'created_info'=>"updated_at:2024-01-01 12:12:12",
        "redirect_url"=>"https://open.feishu.cn/cardkit/editor?cardId=AAqjjGlz51b4V&cardLocale=zh_cn&host=message"
    ];
    $response = $message->card($receive_id,$template_id,$template_variable);
    expect($response['code'])->toBe(0);
});

it('get redirect url', function () {
    $auth = Application::getInstance()->createAuth();
    $url = $auth->redirect();
    expect($url)->not()->toBeNull();
});

it('get user access token', function () {
    $auth = Application::getInstance()->createAuth();
    $code = "ef5s4a8e714f407cb4a42549e17ecef2";
    $access_token = $auth->getAccessToken($code);
    expect($access_token)->not()->toBeNull();
});

it('send batch message', function () {
    $message = Application::getInstance()->createMessage();
    $open_ids = ['ou_ce8eaa702c9f310401a2b21f2a00b13d'];
    $content = "batch message";
    $response = $message->batchText($open_ids,$content);
    expect($response['code'])->toBe(0);
});

it('send batch card', function () {
    $message = Application::getInstance()->createMessage();
    $open_ids = ['ou_ce8eaa702c9f310401a2b21f2a00b13d'];
    $template_id = "AAqjjGlz51b4V";
    $template_variable = [
        'title'=>"企业认证",
        'id'=>"123",
        'content'=>"你有企业认证需要审核\n创建时间:2024-01-01 12:12:12",
        'created_info'=>"更新时间:2024-01-01 12:12:12",
        "redirect_url"=>"https://open.feishu.cn/cardkit/editor?cardId=AAqjjGlz51b4V&cardLocale=zh_cn&host=message"
    ];
    $response = $message->batchCard($open_ids,$template_id,$template_variable);
    expect($response['code'])->toBe(0);
});
