<?php

namespace App;

class AssetService{

    private array $assets;
    private const CSS_FILE = 'css';
    private const JS_FILE = 'js';
    private string $filetype;

    public function __construct() {
        
        $this->assets = [
            'css' => [],
            'js' => [],
        ];
    }

    public function type(string $type): object
    {
        $this->filetype = $type;
        return $this;
    }

    public function register($filename): void
    {
        switch ($this->filetype) {
            case AssetService::CSS_FILE:
                array_push($this->assets['css'], $filename);
                break;

            case AssetService::JS_FILE:
                array_push($this->assets['js'], $filename);
                break;
        }
    }
}