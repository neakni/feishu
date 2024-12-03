# laravel 飞书sdk

## install

```sh
composer require larafly/feishu
```

## Config

1. publish the config file to `config` directory：

```shell
php artisan vendor:publish --provider="Larafly\Feishu\FeishuServiceProvider"
```

## Use

* get application

```php
<?php
use Larafly\Feishu\Application;

$config = [
    "app_id" => "1234",
    "app_secret" => "AT7rW8JOJUFOVrWSdgh5XdQ3Akia8K1r"
];
$application = Application::getInstance($config);

dump($application->getConfig());
```

* auth login

```php
<?php
use Larafly\Feishu\Application;

$config = [
    "app_id" => "1234",
    "app_secret" => "AT7rW8JOJUFOVrWSdgh5XdQ3Akia8K1r",
    "redirect_uri" =>"http://localhost"
];
$redirect_uri = Application::getInstance($config)->createAuth()->redirect($config['redirect_uri']);

redirect($redirect_uri);

//after login
$code = request()->get('code');
try {
    $user_info = Application::getInstance($config)->createAuth()->getUserByCode($code);
    dump($user_info);
 }catch (\Larafly\Feishu\Exceptions\RequestException $exception){
    
 }

```

* send message

```php
<?php
use Larafly\Feishu\Application;

$config = [
    "app_id" => "1234",
    "app_secret" => "AT7rW8JOJUFOVrWSdgh5XdQ3Akia8K1r",
    "redirect_uri" =>"http://localhost"
];
$message = Application::getInstance($config)->createMessage();

//send message str
$content = "send a message";
$receive_id = 'ou_ce8eaa702c9f310401a2b21f2a00b13d';
$response = $message->text($receive_id,$content);

//send message card
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
```
