<?php

namespace Asd\Http;

use Asd\Collections\MapInterface;
use Asd\Collections\HashMap;

class Headers extends HashMap implements MapInterface
{
    const CONTENT_KEYS = [
        'CONTENT_LENGTH' => 'Content-Length',
        'CONTENT_TYPE' => 'Content-Type',
        'CONTENT_MD5' => 'Content-Md5'
    ];

    const AUTH_KEYS = [
        'REDIRECT_HTTP_AUTHORIZATION',
        'PHP_AUTH_USER',
        'PHP_AUTH_DIGEST'
    ];
    public function withGlobals(array $env = null) : self
    {
        $env = $env ?? $_SERVER;
        $clone = clone $this;
        foreach ($env as $key => $value) {
            $key = strtoupper($key);
            $name = null;
            $content = null;
            if (substr($key, 0, 5) === 'HTTP_') {
                $name = str_replace('_', ' ', strtolower(substr($key, 5)));
                $name = str_replace(' ', '-', ucwords($name));
                $content = explode(',', $value);
            } elseif (array_key_exists($key, self::CONTENT_KEYS)) {
                $name = self::CONTENT_KEYS[$key];
                $content = [$value];
            } elseif (in_array($key, self::AUTH_KEYS)) {
                $name = 'Authorization';
                if ($key === 'PHP_AUTH_USER') {
                    $content = ['Basic ' . base64_encode($value . ':' . ($env['PHP_AUTH_PW'] ?? ''))];
                } else {
                    $content = [$value];
                }
            }
            if ($name !== null && $content !== null) {
                $header = new Header($name, $content);
                $clone = $clone->put($name, $header);
            }
        }
        return $clone;
    }
}
