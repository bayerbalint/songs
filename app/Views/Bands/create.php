<?php
echo <<<HTML
        <form method='post' action='/bands' enctype="multipart/form-data">
            <fieldset>
                <label for="band">Együttes</label>
                <input type="text" name="name" id="name"><br>
                <label for="band">Együttes fotó</label>
                <input type="file" name="bandImage" id="bandImage">
                <hr>
                <button type="submit" name="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/bands"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;