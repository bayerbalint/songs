<?php

namespace App\Models;

class ArtistModel extends Model{
    public int|null $bandId = null;
    public string|null $name = null;
    public int|null $born = null;
    public string|null $instrument = null;
    public string|null $artistImage = null; // blob

    protected static $table = 'artists';

    public function __construct(?int $bandId = null, ?string $name = null, ?int $born = null, ?string $instrument = null, ?string $artistImage = null){
        parent::__construct();

        if ($bandId){
            $this->bandId = $bandId;
        }

        if ($name){
            $this->name = $name;
        }

        if ($born){
            $this->born = $born;
        }

        if ($instrument){
            $this->instrument = $instrument;
        }

        if ($artistImage){
            $this->artistImage = $artistImage;
        }
    }

    public function getBand(){
        $band = new BandModel();
        return $band->find($this->bandId);
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