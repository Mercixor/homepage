<?php
/**
 * @author Dennis Jandt
 */
// load Constants
require_once PATH_LIB . 'database_constants.php';

class AjaxHandlerLan extends ajax\BasicAjaxHandler {
    protected $database;
    
    public function __construct() {
        $this->database = new Database();
    }
    
    public function addNewUser() {
        $requestCreator = (int) $_POST['creator'];
        
    }
    
    public function modifyUserInformation() {
        $id     = (int) $_POST['id'];
        $name   = Sanitize::xss_clean($_POST['name']);
        $ort    = Sanitize::xss_clean($_POST['ort']);
        
        $sql =  'UPDATE ' . TABLE_LAN_USER . ' (name, ort) '.
                ' SET (' .$name. ', ' .$ort.')'.
                ' WHERE id = ' .$id;
        $result = $this->database->getResult($sql);
        
        if ($result) {
            return array('info' => 'Daten erfolgreich geändert');
        }
        return array('error' => 'Fehler beim Ändern der Daten');
    }
}
