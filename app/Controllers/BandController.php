<?php

namespace App\Controllers;

use App\Models\BandModel;
use App\Views\Display;

class BandController extends Controller
{
    public function __construct(){
        $band = new BandModel();
        parent::__construct($band);
    }

    public function index(){
        $band = $this->model->all(['order_by' => ['name'], 'direction' => ['ASC']]);
        $this->render('bands/index', ['bands' => $band]);
    }

    public function create(){
        $this->render('bands/create');
    }

    public function edit($id){
        $band = $this->model->find($id);
        if (!$band){
            $_SESSION['warning_message'] = "Az együttes a megadott azonosítóval: $id nem található";
            $this->redirect('/bands');
        }
        $this->render('bands/edit', ['band' => $band]);
    }

    public function save($data){
        if (empty($data['name'])){
            $_SESSION['warning_message'] = "Hiányos adatok";
            $this->redirect('/bands');
        }
        // check for the datatype of the given information

        $bands = $this->model->all();
        foreach ($bands as $band){
            if ($band->name == $data['name']){
                $_SESSION['warning_message'] = "A megadott együttes már szerepel!";
                $this->redirect('/bands');
            }
        }
        $this->model->name = $data['name'];
        $fileName = "../files/" . $_FILES['bandImage']['name'];
        if (move_uploaded_file($_FILES['bandImage']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $this->model->bandImage = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }
        $this->model->create();
        $this->redirect('/bands');
    }

    public function update($id, $data){
        $band = $this->model->find($id);
        if (!$band || empty($data['name'])){
            $_SESSION['warning_message'] = "Hiányos adatok";
            $this->redirect('/bands');
        }
        // check for the datatype of the given information

        $bands = $this->model->all();
        foreach ($bands as $currband){
            if ($id != $currband->id && $currband->name == $data['name']){
                $_SESSION['warning_message'] = "A megadott együttes már szerepel!";
                $this->redirect('/bands');
            }
        }
        $band->name = $data['name'];
        $fileName = "../files/" . $_FILES['bandImage']['name'];
        if (move_uploaded_file($_FILES['bandImage']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $band->bandImage = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }
        $band->update();
        $this->redirect('/bands');
    }

    function show(int $id): void
    {
        $band = $this->model->find($id);
        if (!$band) {
            $_SESSION['warning_message'] = "Az együttes a megadott azonosítóval: $id nem található.";
            $this->redirect('/bands'); // Handle invalid ID
        }
        $this->render('bands/show', ['band' => $band]);
    }

    function delete(int $id): void
    {
        $band = $this->model->find($id);
        if ($band) {
            $result = $band->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/bands'); // Redirect regardless of success
    }
}