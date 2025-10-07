<?php

namespace App\Controllers;

use App\Models\AlbumModel;
use App\Views\Display;

class AlbumController extends Controller
{
    public function __construct(){
        $album = new albumModel();
        parent::__construct($album);
    }

    public function index(){
        $album = $this->model->all(['order_by' => ['name'], 'direction' => ['ASC']]);
        $this->render('albums/index', ['albums' => $album]);
    }

    public function create(){
        $this->render('albums/create');
    }

    public function edit($id){
        $album = $this->model->find($id);
        if (!$album){
            $_SESSION['warning_message'] = "Az album a megadott azonosítóval: $id nem található";
            $this->redirect('/albums');
        }
        $this->render('albums/edit', ['album' => $album]);
    }

    public function save($data){
        if (empty($data['name']) || empty($data['releaseDate'])){
            $_SESSION['warning_message'] = "Hiányos adatok";
            $this->redirect('/albums');
        }
        // check for the datatype of the given information

        $albums = $this->model->all();
        foreach ($albums as $album){
            if ($album->name == $data['name']){
                $_SESSION['warning_message'] = "A megadott album már szerepel!";
                $this->redirect('/albums');
            }
        }
        $this->model->name = $data['name'];
        $this->model->releaseDate = $data['releaseDate'];
        $fileName = "../files/" . $_FILES['albumCover']['name'];
        if (move_uploaded_file($_FILES['albumCover']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $this->model->albumCover = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }
        $this->model->create();
        $this->redirect('/albums');
    }

    public function update($id, $data){
        $album = $this->model->find($id);
        if (!$album || empty($data['name']) || empty($data['releaseDate'])){
            $_SESSION['warning_message'] = "Hiányos adatok";
            $this->redirect('/albums');
        }
        // check for the datatype of the given information

        $albums = $this->model->all();
        foreach ($albums as $curralbum){
            if ($id != $curralbum->id && $curralbum->name == $data['name']){
                $_SESSION['warning_message'] = "A megadott album már szerepel!";
                $this->redirect('/albums');
            }
        }
        $album->name = $data['name'];
        $album->releaseDate = $data['releaseDate'];
        $fileName = "../files/" . $_FILES['albumCover']['name'];
        if (move_uploaded_file($_FILES['albumCover']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $album->albumCover = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }
        $album->update();
        $this->redirect('/albums');
    }

    function show(int $id): void
    {
        $album = $this->model->find($id);
        if (!$album) {
            $_SESSION['warning_message'] = "Az album a megadott azonosítóval: $id nem található.";
            $this->redirect('/albums'); // Handle invalid ID
        }
        $this->render('albums/show', ['album' => $album]);
    }

    function delete(int $id): void
    {
        $album = $this->model->find($id);
        if ($album) {
            $result = $album->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/albums'); // Redirect regardless of success
    }
}