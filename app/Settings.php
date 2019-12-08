<?php

declare(strict_types=1);

namespace App;

class Settings
{
    private $root_dir;
    private $settings_dir;
    private $settings_file_name;
    private $data;


    public function __construct(string $dir, string $file_name)
    {
        $this->settings_dir = $dir;
        $this->settings_file_name = $file_name;
        $this->loadData();
    }


    private function loadData()
    {
        $path = $this->getSettingsFilePath();
        $this->data = require_once($path);
    }


    private function getSettingsFilePath(): string
    {
        return $this->settings_dir . DIRECTORY_SEPARATOR . $this->settings_file_name;
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
        return $this->root_dir ?? $this->root_dir = realpath($this->settings_dir . '/../');
    }

}