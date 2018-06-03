<?php
declare(strict_types=1);

namespace App;

class Settings
{
    private $root_dir;
    private $data;


    public function __construct()
    {
        $this->loadData();
    }


    private function loadData()
    {
        $path = $this->getSettingsFilePath();
        $this->data = require_once($path);
    }


    private function getSettingsFilePath(): string
    {
        return $this->getRootDir() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php';
    }


    public function get(string $key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new \Exception('Such setting does not exist');
        }
        return $this->data[$key];
    }


    public function getRootDir(): string
    {
        return $this->root_dir ?? $this->root_dir = realpath(__DIR__ . '/../');
    }

}