<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Asphxia\Batchio\Syncr;

$app = new Silex\Application();
$configuration = array(
    'hostname' => 'localhost',
    'username' => 'example',
    'password' => 'example',
    'database' => 'batchio',
    'max_attemps' => 0,
    'attemps_delay' => 0
);

$app->post('/twilio/callback.me', function (Request $request) use ($configuration) {    

    $syncr = new Syncr\Drivers\Db();
    $syncr->bootstrap($configuration);
    
    $status = array(
        'sid'       => $request->get('SmsSid'),
        'status'    => $request->get('SmsStatus'),
        'call_from' => $request->get('From'),
        'call_to'   => $request->get('To'),
        'account_sid' => $request->get('AccountSid')
    );

    $syncr->sync($status);
    
    return new Response('', 200);
});

$app->run();
