<?php

namespace Haolyy\Ssdb;

use Haolyy\Ssdb\Cache\SimpleSSDB;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Cache\TaggableStore;

class Ssdb extends TaggableStore implements Store
{
    protected $ssdb;
    protected $prefix;

    public function __construct($app)
    {
        $config = $this->_getConfig($app);
        $this->ssdb = new SimpleSSDB($config['host'], $config['port']);
        $this->prefix = $config['prefix'];
    }

    public function get($key)
    {
        return $this->ssdb->get($this->_getKey($key));
    }

    public function put($key, $value, $minutes)
    {
        $this->ssdb->setx($this->_getKey($key), $value, $minutes);
    }

    public function many(array $keys)
    {
        $prefix = $this->prefix;
        $keys = array_map(function ($k) use ($prefix) {
            return $prefix . '_' . $k;
        }, $keys);

        return $this->ssdb->multi_get($keys);
    }

    public function putMany(array $values, $minutes)
    {
        foreach ($values as $k => $v) {
            $this->put($k, $v, $minutes);
        }
    }

    public function increment($key, $value = 1)
    {
        $value = (int)$value;

        return $this->ssdb->incr($this->_getKey($key), $value);
    }

    public function decrement($key, $value = 1)
    {
        $value = (int)$value;

        return $this->ssdb->incr($this->_getKey($key), -$value);
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function forget($key)
    {
        return $this->ssdb->del($this->_getKey($key));
    }

    public function forever($key, $value)
    {
        $this->ssdb->set($this->_getKey($key), $value);
    }

    public function flush()
    {
        return $this->ssdb->flushdb();
    }

    private function _getConfig($app)
    {
        $config = $app['config']['cache']['stores']['ssdb'];

        return [
            'host' => array_get($config, 'host', '127.0.0.1'),
            'port' => array_get($config, 'port', 8888),
            'auth' => array_get($config, 'auth'),
            'time_out' => array_get($config, 'time_out', 2),
            'prefix' => array_get($app['config']['cache'], 'prefix'),
        ];
    }

    private function _getKey($key)
    {
        return $this->prefix . '_' . $key;
    }
}