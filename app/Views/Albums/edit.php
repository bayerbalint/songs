<?php
$html = <<<HTML
    <form method='post' action='/albums' enctype="multipart/form-data">
        <input type='hidden' name='_method' value='PATCH'>
        <input type="hidden" name="id" value="{$album->id}">
        <fieldset>
            <label for="album">Név</label>
            <input type="text" name="name" id="name" value="{$album->name}"><br>
            <label for="album">Kép</label>
            <input type="file" name="albumCover" id="albumCover"><br>
            <label for="album">Kiadási év</label>
            <input type="number" name="releaseDate" id="releaseDate" min="1900" max="2025" value="{$album->releaseDate}">
            <hr>
            <button type="submit" name="btn-update"><i class="fa fa-save">                    
                </i>&nbsp;Mentés
            </button>
            <a href="/albums"><i class="fa fa-cancel"></i>&nbsp;Mégse
            </a>
        </fieldset>
    </form>
HTML;

echo $html;