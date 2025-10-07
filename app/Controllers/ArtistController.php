<?php

namespace App\Controllers;

use App\Models\ArtistModel;
use App\Views\Display;

class ArtistController extends Controller
{
    public function __construct(){
        $artist = new ArtistModel();
        parent::__construct($artist);
    }

    public function index(){
        $artist = $this->model->all(['order_by' => ['name'], 'direction' => ['ASC']]);
        $this->render('artists/index', ['artists' => $artist]);
    }

    public function create(){
        $this->render('artists/create');
    }

    public function edit($id){
        $artist = $this->model->find($id);
        if (!$artist){
            $_SESSION['warning_message'] = "Az előadó a megadott azonosítóval: $id nem található";
            $this->redirect('/artists');
        }
        $this->render('artists/edit', ['artist' => $artist]);
    }

    public function save($data){
        if (empty($data['name']) || empty($data['instrument'])){
            $_SESSION['warning_message'] = "Hiányos adatok";
            $this->redirect('/artists');
        }
        // check for the datatype of the given information

        $artists = $this->model->all();
        foreach ($artists as $artist){
            if ($artist->name == $data['name']){
                $_SESSION['warning_message'] = "A megadott előadó már szerepel!";
                $this->redirect('/artists');
            }
        }
        $this->model->name = $data['name'];
        $this->model->instrument = $data['instrument'];
        $fileName = "../files/" . $_FILES['artistImage']['name'];
        if (move_uploaded_file($_FILES['artistImage']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $this->model->artistImage = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }
        $this->model->create();
        $this->redirect('/artists');
    }

    public function update($id, $data){
        $artist = $this->model->find($id);
        if (!$artist || empty($data['name']) || empty($data['instrument'])){
            $_SESSION['warning_message'] = "Hiányos adatok";
            $this->redirect('/artists');
        }
        // check for the datatype of the given information

        $artists = $this->model->all();
        foreach ($artists as $currArtist){
            if ($id != $currArtist->id && $currArtist->name == $data['name']){
                $_SESSION['warning_message'] = "A megadott előadó már szerepel!";
                $this->redirect('/artists');
            }
        }
        $artist->name = $data['name'];
        $artist->instrument = $data['instrument'];
        $fileName = "../files/" . $_FILES['artistImage']['name'];
        if (move_uploaded_file($_FILES['artistImage']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $artist->artistImage = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }
        $artist->update();
        $this->redirect('/artists');
    }

    function show(int $id): void
    {
        $artist = $this->model->find($id);
        if (!$artist) {
            $_SESSION['warning_message'] = "Az előadó a megadott azonosítóval: $id nem található.";
            $this->redirect('/artists'); // Handle invalid ID
        }
        $this->render('artists/show', ['artist' => $artist]);
    }

    function delete(int $id): void
    {
        $artist = $this->model->find($id);
        if ($artist) {
            $result = $artist->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/artists'); // Redirect regardless of success
    }
}