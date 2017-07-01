<?php

function _redis_connection($config)
{/*{{{*/
    $redis = new Redis();

    if (isset($config['sock'])) {
        $redis->pconnect($config['sock'], $config['timeout']);
    } else {
        $redis->pconnect($config['host'], $config['port'], $config['timeout']);
    }

    if (isset($config['password'])) {
        $redis->auth($config['password']);
    }

    if (isset($config['database'])) {
        $redis->select($config['database']);
    }

    if (isset($config['options'])) {
        foreach ($config['options'] as $key => $value) {
            $redis->setOption($key, $value);
        }
    }

    return $redis;
}/*}}}*/

function _redis_cache_closure($config_key, closure $closure)
{/*{{{*/
    static $configs = [];

    if (empty($configs)) {
        $configs = config('redis');
    }

    $redis = _redis_connection($configs[$config_key]);

    $res = call_user_func($closure, $redis);

    return $res;
}/*}}}*/

function cache_get($key, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key) {

        return $redis->get((string) $key);

    });
}/*}}}*/

function cache_multi_get(array $keys, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($keys) {

        $values = $redis->mGet((string) $keys);

        return array_combine($keys, $values);
    });
}/*}}}*/

function cache_set($key, $value, $expires = 0, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key, $value, $expires) {

        if ($expires) {
            return $redis->set($key, $value, (int) $expires);
        } else {
            return $redis->set($key, $value);
        }
    });
}/*}}}*/

function cache_add($key, $value, $expires = 0, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key, $value, $expires) {

        if ($expires) {
            return $redis->set($key, $value, ['nx', 'ex' => (int) $expires]);
        } else {
            return $redis->setNx($key, $value);
        }
    });
}/*}}}*/

function cache_replace($key, $value, $expires = 0, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key, $value, $expires) {

        if ($expires) {
            return $redis->set($key, $value, ['xx', 'ex' => (int) $expires]);
        } else {
            return $redis->setNx($key, $value);
        }
    });
}/*}}}*/

function cache_delete($key, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key) {

        return $redis->delete($key);
    });
}/*}}}*/

function cache_multi_delete(array $keys, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($keys) {

        return $redis->delete($keys);
    });
}/*}}}*/

function cache_increment($key, $number = 1, $expires = 0, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key, $number, $expires) {

        $res = $redis->incr($key, $number);

        if ($expires) {
            $redis->setTimeout($key, (int) $expires);
        }

        return $res;
    });
}/*}}}*/

function cache_decrement($key, $number = 1, $expires = 0, $config_key = 'default')
{/*{{{*/
    return _redis_cache_closure($config_key, function ($redis) use ($key, $number, $expires) {

        $res = $redis->decr($key, $number);

        if ($expires) {
            $redis->setTimeout($key, (int) $expires);
        }

        return $res;
    });
}/*}}}*/
