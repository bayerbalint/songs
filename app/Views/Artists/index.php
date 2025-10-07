<?php

// Videó konvertálása
// <source src="data:video/mp3;base64, ' . base64_encode($song['audio_source']) . '" type="audio/mpeg">

$tableBody = "";
foreach ($artists as $artist) {
    $image = '<img class="artistImg" src= "data:image/png;base64, ' . base64_encode($artist->artistImage) . '" alt="' . $artist->name . '" >';
    $tableBody .= <<<HTML
            <tr>
                <td>{$artist->id}</td>
                <td>{$artist->getBand()->name}</td>
                <td>{$artist->name}</td>
                <td>{$artist->born}</td>
                <td>{$artist->instrument}</td>
                <td>{$image}</td>
                <td class='flex float-right'>
                    <form method='post' action='/artists/edit'>
                        <input type='hidden' name='id' value='{$artist->id}'>
                        <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                    </form>
                    <form method='post' action='/artists'>
                        <input type='hidden' name='id' value='{$artist->id}'>    
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
                    <th>Együttes</th>
                    <th>Előadó</th>
                    <th>Születési év</th>
                    <th>Hangszer</th>
                    <th>Előadó fotó</th>
                    <th>
                        <form method='post' action='/artists/create'>
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
