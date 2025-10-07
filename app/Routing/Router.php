<?php

namespace App\Routing;

use App\Controllers\ArtistController;
use App\Controllers\AlbumController;
use App\Controllers\BandController;
use App\Controllers\SongController;
use App\Views\Display;

class Router{
    public function handle(){
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $requestUri = $_SERVER['REQUEST_URI'];

        if ($method === 'POST' && isset($_POST['_method'])){
            $method = strtoupper($_POST['_method']);
        }

        $this->dispatch($method, $requestUri);
    }

    private function dispatch($method, $requestUri){
        switch ($method){
            case 'GET':
                $this->handleGetRequests($requestUri);
                break;
            case 'POST':
                $this->handlePostRequests($requestUri);
                break;
            case 'PATCH':
                $this->handlePatchRequests($requestUri);
                break;
            case 'DELETE':
                $this->handleDeleteRequests($requestUri);
                break;
            default:
                $this->methodNotAllowed();
                break;
        }
    }

    private function handleGetRequests($requestUri){
        switch ($requestUri){
            case '/':
                $songController = new SongController();
                $songController->index();
                break;
            case '/artists':
                $artistController = new ArtistController();
                $artistController->index();
                break;
            case '/albums':
                $albumController = new AlbumController();
                $albumController->index();
                break;
            case '/bands':
                $bandController = new BandController();
                $bandController->index();
                break;
            default:
                $this->notFound();
        }
    }

    private function handlePostRequests($requestUri){
        $data = $this->filterPostData($_POST);
        $id = $data['id'] ?? null;

        switch ($requestUri){
            case '/':
                if (!empty($data)){
                    $songController = new SongController();
                    $songController->save($data);
                }
                break;
            case '/create':
                $songController = new SongController();
                $songController->create();
                break;
            case '/edit':
                $songController = new SongController();
                $songController->edit($id);
                break;
            case '/artists':
                if (!empty($data)){
                    $artistController = new ArtistController();
                    $artistController->save($data);
                }
                break;
            case '/artists/create':
                $artistController = new ArtistController();
                $artistController->create();
                break;
            case '/artists/edit':
                $artistController = new ArtistController();
                $artistController->edit($id);
                break;
            case '/albums':
                if (!empty($data)){
                    $albumController = new AlbumController();
                    $albumController->save($data);
                }
                break;
            case '/albums/create':
                $albumController = new AlbumController();
                $albumController->create();
                break;
            case '/albums/edit':
                $albumController = new AlbumController();
                $albumController->edit($id);
                break;
            case '/bands':
                if (!empty($data)){
                    $bandController = new BandController();
                    $bandController->save($data);
                }
                break;
            case '/bands/create':
                $bandController = new BandController();
                $bandController->create();
                break;
            case '/bands/edit':
                $bandController = new BandController();
                $bandController->edit($id);
                break;
            default:
                $this->notFound();
        }
    }

    private function handlePatchRequests($requestUri){
        $data = $this->filterPostData($_POST);
        $id = $data['id'] ?? null;

        switch ($requestUri){
            case '/':
                $songController = new SongController();
                $songController->update($id, $data);
                break;
            case '/artists':
                $artistController = new ArtistController();
                $artistController->update($id, $data);
                break;
            case '/albums':
                $albumController = new AlbumController();
                $albumController->update($id, $data);
                break;
            case '/bands':
                $bandController = new BandController();
                $bandController->update($id, $data);
            default:
                $this->notFound();
        }
    }

    private function handleDeleteRequests($requestUri){
        $data = $this->filterPostData($_POST);

        switch ($requestUri){
            case '/':
                $songController = new SongController();
                $songController->delete($data['id']);
            case '/artists':
                $artistController = new ArtistController();
                $artistController->delete($data['id']);
            case '/albums':
                $albumController = new AlbumController();
                $albumController->delete($data['id']);
            case '/bands':
                $bandController = new BandController();
                $bandController->delete($data['id']);
            default:
                $this->notFound();
        }
    }

    private function filterPostData(array $data): array
    {
        // Remove unnecessary keys in a clean and simple way
        $filterKeys = ['_method', 'submit', 'btn-del', 'btn-save', 'btn-edit', 'btn-plus', 'btn-update'];
        return array_diff_key($data, array_flip($filterKeys));
    }

    private function notFound(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        Display::message("404 Not Found", 'error');
    }

    private function methodNotAllowed(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        Display::message("405 Method Not Allowed", 'error');
    }
}