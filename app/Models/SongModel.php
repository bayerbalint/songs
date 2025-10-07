<?php

namespace App\Models;

class SongModel extends Model
{
    public int|null $artistId = null;
    public int|null $albumId = null;
    public int|null $bandId = null;
    public string|null $song = null;
    public string|null $title = null;
    public string|null $genre = null;
    public string|null $language = null;

    protected static $table = 'songs';

    public function __construct(?int $artistId = null, ?int $albumId = null, ?int $bandId = null, ?string $song = null, ?string $title = null, ?string $genre = null, ?string $language = null)
    {
        parent::__construct();

        if ($artistId){
            $this->artistId = $artistId;
        }

        if ($albumId){
            $this->albumId = $albumId;
        }

        if ($bandId){
            $this->bandId = $bandId;
        }

        if ($song){
            $this->song = $song;
        }

        if ($title){
            $this->title = $title;
        }

        if ($genre){
            $this->genre = $genre;
        }

        if ($language){
            $this->language = $language;
        }
    }

    public function getArtist()
    {
        $artist = new ArtistModel();
        return $artist->find($this->artistId);
    }

    public function getAlbum()
    {
        $album = new AlbumModel();
        return $album->find($this->albumId);
    }

    public function getBand(){
        $band = new BandModel();
        return $band->find($this->bandId);
    }

    public function getArtists($id = ""){
        $artist = new ArtistModel();
        $artists = $artist->all(['order_by' => ['name']]);
        $options = "";
        for ($i = 0; $i < count($artists); $i++){
            $options .= '<option value="' . $artists[$i]->id . '"';
            if ($artists[$i]->id == $id){
                $options .= ' selected';
            }
            $options .= '>' . $artists[$i]->name . '</option>';
        }
        return $options;
    }

    public function getAlbums($id = ""){
        $album = new AlbumModel();
        $albums = $album->all(['order_by' => ['name']]);
        $options = "";
        for ($i = 0; $i < count($albums); $i++) {
            $options .= '<option value="' . $albums[$i]->id . '"';
            if ($albums[$i]->id == $id) {
                $options .= ' selected';
            }
            $options .= '>' . $albums[$i]->name . '</option>';
        }
        return $options;
    }

    public function getBands($id = ""){
        $band = new BandModel();
        $bands = $band->all(['order_by' => ['name']]);
        $options = "";
        for ($i = 0; $i < count($bands); $i++) {
            $options .= '<option value="' . $bands[$i]->id . '"';
            if ($bands[$i]->id == $id) {
                $options .= ' selected';
            }
            $options .= '>' . $bands[$i]->name . '</option>';
        }
        return $options;
    }
}