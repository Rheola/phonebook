<?php

namespace core;

class Cache
{
    public static function setValue($key, $value)
    {
        $cacheFile = __DIR__ . '/../cache/' . $key . '.bin';
        $value = serialize($value);
        if (@file_put_contents($cacheFile, $value, LOCK_EX) !== false) {

            @chmod($cacheFile, 775);

            $duration = 31536000; // 1 year

            return @touch($cacheFile, $duration + time());
        }
        return false;
    }

    /**
     * Retrieves a value from cache with a specified key.
     * This is the implementation of the method declared in the parent class.
     * @param string $key a unique key identifying the cached value
     * @return string|false the value stored in cache, false if the value is not in the cache or expired.
     */
    public static function getValue($key)
    {
        $cacheFile = __DIR__ . '/cache/' . $key . '.bin';

        if (@filemtime($cacheFile) > time()) {
            $fp = @fopen($cacheFile, 'r');
            if ($fp !== false) {
                @flock($fp, LOCK_SH);
                $cacheValue = @stream_get_contents($fp);
                @flock($fp, LOCK_UN);
                @fclose($fp);
                return unserialize($cacheValue);
            }
        }

        return false;
    }
}