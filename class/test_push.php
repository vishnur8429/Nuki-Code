<?php

$type    = 'test';
$message = "hello Push";
$count   = 0;
$deviceToken = 'APA91bGD6V0gfK3gPE-SPS5s2ltpXmJ2vIfii5CaqMm9K7Y7snS6crqCW9pqDB6gXfZP8bxvj_QpeFeGHwGy0unRMPPoMOFpkr_2s63f-HgZKE69-ZMCZm43hJnmxMH4qoEUocawTtdW';

$url          = 'https://android.googleapis.com/gcm/send';
$serverApiKey = "AIzaSyB5lzISZy5ZoUpyOFpdroi8aEJ13tSTcUU";

$headers = array(
    'Content-Type:application/json',
    'Authorization:key=' . $serverApiKey
);

$data = array(
    'registration_ids' => array($deviceToken),
    'data' => array(
        'type'      => $type,
        'title'     => 'Nuki',
        'message'   => $message,
        'sound'     => 'default',
        'badge'     => $count,
        'url'       => 'http://androidmyway.wordpress.com'
    )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

if ($headers)
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);
print ($response);
?>