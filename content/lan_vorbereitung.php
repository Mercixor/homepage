<?php
\libs\WwwPackages::loadPackages('lan_vorbereitung');

if( isset($_GET['ajax']) ) {
    new AjaxHandlerLan();
    die();
}
?>

<div id="action_set">
    <fieldset>
        <legend>Eigene Daten</legend>
        <label>Name:</label>
        <input type="text" id="user_name" />
        <label>Nickname:</label>
        <input type="text" id="user_nickname" />
        <label>Ort:</label>
        <input type="text" id="user_ort" />
        <button id="user_data_save">Daten speichern</button>
    </fieldset>
    <fieldset>
        <legend>Optionen f&uuml;r die LAN</legend>
        <button type="button" id="add_new_user">Neue Einladung erstellen</button>
    </fieldset>
</div>