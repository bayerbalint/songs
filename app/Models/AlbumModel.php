<?php

namespace App\Models;

class AlbumModel extends Model{
    public string|null $name = null;
    public int|null $releaseDate = null;
    public string|null $albumCover = null; // blob
    
    protected static $table = 'albums';

    public function __construct(?string $name = null, ?int $releaseDate = null, ?string $albumCover = null){
        parent::__construct();

        if ($name){
            $this->name = $name;
        }

        if ($releaseDate){
            $this->releaseDate = $releaseDate;
        }

        if ($albumCover){
            $this->albumCover = $albumCover;
        }
    }
}