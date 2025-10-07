<?php

namespace App\Models;

class BandModel extends Model{
    public string|null $name = null;
    public string|null $bandImage = null; // blob
    
    protected static $table = 'bands';

    public function __construct(?string $name = null, ?string $bandImage = null){
        parent::__construct();

        if ($name){
            $this->name = $name;
        }

        if ($bandImage){
            $this->bandImage = $bandImage;
        }
    }
}