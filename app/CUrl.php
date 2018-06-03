<?php
declare(strict_types = 1);

namespace App;

class CUrl
{ 
    protected $instance;
    protected static $defaults = [
        CURLOPT_RETURNTRANSFER => true
    ];

    public function __construct(array $params = [], string $url = '')
    {
        if (!empty($url)) {  
            $this->instance = curl_init($url);
        } else {
            $this->instance = curl_init();
        }

        $this->setDefaults();
            
        if (!empty($params)) {
            $this->setOpt($params);
        }
    }

    protected function setDefaults()
    {
        curl_reset($this->instance);
        $this->setOpt(self::$defaults);
    }

    public function __destruct()
    {
        curl_close($this->instance);
    }
    
    public function setOpt(...$args)
    {
        if (is_int($args[0])) {
            if (count($args) !== 2) {
                throw new \BadMethodCallException('Wrong arguments\' number.');
            }
            if (!curl_setopt($this->instance, $args[0], $args[1])) {
                $this->throwError();
            }

        } elseif (is_array($args[0])) {
            if (count($args) !== 1) {
                throw new \BadMethodCallException('Too many arguments.');
            }
            if (!curl_setopt_array($this->instance, $args[0])) {
                $this->throwError();
            }
        }

    }
    
    public function exec()
    {
        return curl_exec($this->instance);
    }

    public function getUrlBody(string $url): string
    {
        $this->setOpt(CURLOPT_URL, $url);
        $result = curl_exec($this->instance);
        return $result;
    }

    public function getUrlHeaders(string $url): string
    {
        $this->setDefaults();
        $this->setOpt(CURLOPT_NOBODY, true);
        $this->setOpt(CURLOPT_HEADER, true);
        $this->setOpt(CURLOPT_URL, $url);
        $result = curl_exec($this->instance);
        $this->setDefaults();
        return $result;
    }

    public function getUrlHeaderValue(string $url, string $header)
    {
        $headers = $this->getUrlHeaders($url);
        $pattern = "/$header: (.*)/i";
        if (preg_match($pattern, $headers, $matches)) {
            return $matches[1];
        }
        return false;
    }

    public function error(): string
    {
        return curl_error($this->instance);
    }

    protected function throwError()
    {
        throw new \Exception($this->error());
    }
}
