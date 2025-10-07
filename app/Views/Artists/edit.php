<?php
$html = <<<HTML
    <form method='post' action='/artists' enctype="multipart/form-data">
        <input type='hidden' name='_method' value='PATCH'>
        <input type="hidden" name="id" value="{$artist->id}">
        <fieldset>
            <label for="artist">Együttes</label>
            <select name="bandId" id="bandId">
                {$artist->getBands($artist->bandId)}
            </select><br>
            <label for="artist">Előadó</label>
            <input type="text" name="name" id="name" value="{$artist->name}"><br>
            <label for="artist">Születési év</label>
            <input type="text" name="name" id="name" value="{$artist->born}"><br>
            <label for="artist">Hangszer</label>
            <input type="text" name="instrument" id="instrument" value="{$artist->instrument}"><br>
            <label for="artist">Előadó fotó</label>
            <input type="file" name="artistImage" id="artistImage">
            <hr>
            <button type="submit" name="btn-update"><i class="fa fa-save">                    
                </i>&nbsp;Mentés
            </button>
            <a href="/artists"><i class="fa fa-cancel"></i>&nbsp;Mégse
            </a>
        </fieldset>
    </form>
HTML;

echo $html;