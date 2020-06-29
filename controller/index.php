<?php

if_get('/tag/get_user_ids', function ()
{
    $res = [];

    for ($i = 0; $i < 10000; $i ++) {
        $res[] = rand(100000, 999999);
    }

    return [
        'res' => 0,
        'data' => $res,
    ];
});


if_post('/user_rand_half', function ()
{
    $user_ids = input('user_ids', []);

    log_module('test', count($user_ids));

    $rand_keys = $user_ids? (array) array_rand($user_ids, count($user_ids) / 2): [];

    $data = array_build($rand_keys, function ($key, $value) use ($user_ids) {
        return [null, $user_ids[$value]];
    });

    return [
        'res' => 0,
        'data' => $data,
    ];
});
