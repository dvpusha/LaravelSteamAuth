<?php

$api_keys = [
    '',
    '',
    '',
    '',
    ''
];


return [
    'redirect_url' => '/',
    'api_key'      => $api_keys[array_rand($api_keys)],
    'https'        => true
];