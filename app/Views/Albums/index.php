<?php

// Videó konvertálása
// <source src="data:video/mp3;base64, ' . base64_encode($song['audio_source']) . '" type="audio/mpeg">

$tableBody = "";
foreach ($albums as $album) {
    $image = '<img class="albumImg" src= "data:image/png;base64, ' . base64_encode($album->albumCover) . '" alt="' . $album->name . '" >';
    $tableBody .= <<<HTML
            <tr>
                <td>{$album->id}</td>
                <td>{$album->name}</td>
                <td>{$album->releaseDate}</td>
                <td>{$image}</td>
                <td class='flex float-right'>
                    <form method='post' action='/albums/edit'>
                        <input type='hidden' name='id' value='{$album->id}'>
                        <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                    </form>
                    <form method='post' action='/albums'>
                        <input type='hidden' name='id' value='{$album->id}'>    
                        <input type='hidden' name='_method' value='DELETE'>
                        <button type='submit' name='btn-del' title='Töröl'><i class='fa fa-trash trash'></i></button>
                    </form>
                </td>
            </tr>
            HTML;
}

$html = <<<HTML
        <table id='admin-subjects-table' class='admin-subjects-table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Név</th>
                    <th>Kiadási év</th>
                    <th>Kép</th>
                    <th>
                        <form method='post' action='/albums/create'>
                            <button type="submit" name='btn-plus' title='Új'>
                                <i class='fa fa-plus plus'></i>&nbsp;Új</button>
                        </form>
                    </th>
                </tr>
            </thead>
             <tbody>%s</tbody>
            <tfoot>
            </tfoot>
        </table>
        HTML;

echo sprintf($html, $tableBody);
