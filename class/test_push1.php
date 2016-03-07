<?php

$deviceToken = 'b8e9c48eef5d47b6bbeb9a47937cd33e437210cd7f215a87147919d55800f169';
$passphrase  = "sics";
$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

$fp = stream_socket_client(
    'ssl://gateway.sandbox.push.apple.com:2195', $err,
    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
    exit("Failed to connect: $err $errstr".PHP_EOL);

$body['aps'] = array(
    'type' => 'chat',
    'title' => 'Nuki',
    'alert' => 'Hello Hi',
    'sound' => 'default',
    'badge' => 1
);

$payload = json_encode($body);
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
$result = fwrite($fp, $msg, strlen($msg));

fclose($fp);

?>