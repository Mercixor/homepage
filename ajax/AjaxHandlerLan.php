<?php
namespace ajax;

use core\Sanitize;
use DateTime;
use libs\Database;

// Nur einfache Fehler melden
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// load Constants
require_once PATH_LIB . 'database_constants.php';

class AjaxHandlerLan extends BasicAjaxHandler {

	const LEVEL_ADMIN = 100;
    /**
     *
     * @var Database
     */
    protected $database;

	protected $user_id;
	protected $user_level;


	public function __construct() {
        $this->database = Database::getInstance();
    }

    public function authUser() {
        $hash = Sanitize::xss_clean($_REQUEST['hash']);
        $sql =  'SELECT id, level FROM ' . TABLE_LAN_USER .
                ' WHERE auth_hash = "' . $hash. '"';
        $result = $this->database->getResult($sql);
		$user = $result[0];
        if (!$user) {
            exit();
        }
        $this->user_id = $user['id'];
		$this->user_level = $user['level'];
		return $user['id'];
    }

    public function addNewgame() {
        $creator_id = $this->authUser();
        $name       = Sanitize::xss_clean($_POST['name']);
        $art        = Sanitize::xss_clean($_POST['art']);
		$description= Sanitize::xss_clean($_POST['description']);
		$link		= Sanitize::xss_clean($_POST['link']);
        $min_user   = (int) $_POST['min_user'];
        $max_user   = (int) $_POST['max_user'];
        
        $sql =	'INSERT INTO ' .TABLE_LAN_GAMES.
				' (name, art, user_min, user_max, creator_id, description, link)'.
                ' VALUES ("'.$name.'","'.$art.'",'.$min_user.','
				.$max_user.','.$creator_id.',"'.$description.'","'. $link.'")';
        $this->database->getSingleResult($sql);
    }

    public function addNewUser() {
        $creator_id = $this->authUser();
        $name   = Sanitize::xss_clean($_POST['name']);
        $email  = Sanitize::xss_clean($_POST['email']);
        $hash   = Sanitize::generate_code(30);

		// look if email is valid
		$emailcount = substr_count($email, '@');
		if ($emailcount != 1) {
			return array('error' => 'Fehler beim Erstellen der Einladung');
		} else {
			// check if email exists
			$sql = 'SELECT id FROM ' .TABLE_LAN_USER. ' WHERE email = "' .$email. '"';
			$exists = $this->database->getSingleResult($sql);
			if ($exists) {
				return array('error' => 'Die eingegebene E-Mail-Adresse existiert bereits im System.');
			}
			// check if user generated an user in last 5 minutes
			$sql = 'SELECT MAX(create_time) FROM ' .TABLE_LAN_USER. ' WHERE creator_id = ' .$creator_id;

			$time = $sql = $this->database->getSingleResult($sql);
			$timestamp = strtotime($time);
			$checkTime = new \DateTime();
			$checkTime->modify('- 5 minutes');
			if ($timestamp >= $checkTime->getTimestamp()) {
				return array('error' => 'Sie müssen 5 Minuten zwischen den Einladungen warten.');
			}
		}

        $sql = 'INSERT INTO ' . TABLE_LAN_USER. ' (name, email, auth_hash, creator_id)'
                . ' VALUES ("'.$name.'","'.$email.'","'.$hash.'",'.$creator_id.')';

        $result = $this->database->getResult($sql);
        if ($result) {
            $sql = 'SELECT id FROM ' . TABLE_LAN_USER. ' WHERE auth_hash = "' . $hash.'"';
            $user_id = $this->database->getResult($sql);
			$user_id = $user_id[0]['id'];
            $sendResult = $this->sendMail($user_id);
            if ($sendResult) {
                return array('info' => 'Einladung erfolgreich verschickt');
            } else {
				$sql = 'DELETE FROM ' .TABLE_LAN_USER. ' WHERE id = ' .$user_id;

				$this->database->getResult($sql);
			}
        }
        return array('error' => 'Fehler beim Erstellen der Einladung');
    }

    public function getFrontendData() {
        $user_id = $this->authUser();
        $user_data = $this->gatherUserData($user_id);
        $data = array(
            'lans' => $this->gatherOverviewData(),
            'name' => $user_data['name'],
            'nickname' => $user_data['nickname'],
            'userort' => $user_data['ort'],
            'email' => $user_data['email']);
        return $data;
    }

    public function addTerminByLanId() {
        $user_id = $this->authUser();
        $lan_id = (int) $_POST['lan_id'];
        $termin = new DateTime($_POST['termin']);

		// check if user is allowed to add new termin
		if ($this->user_level < 100) {
			die();
		}

        $sql =  'INSERT INTO ' .TABLE_LAN_TERMINE. ' (termin, lan_id, creator_id)'.
                ' VALUES ("' .$termin->format('Y-m-d').'", ' .$lan_id. ', ' .$user_id. ')';
        $result = $this->database->getSingleResult($sql);
        if ($result) {
            return array('info' => 'Termin erfolgreich hinzugefuegt');
        }
        return array('error' => 'Fehler beim Speichern des neuen Termins.');
    }

    public function getTermineByLanId() {
        $lan_id = (int) $_GET['id'];
        $termine = array(
            'termine' => $this->getLanTermine($lan_id));
		if ($this->user_level >= self::LEVEL_ADMIN) {
			$termine['level'] = 'allowed';
		}
        return $termine;
    }

    public function getGames() {
        $lan_id = (int) $_GET['lan_id'];
        $games = array(
            'games' => $this->getLanGames($lan_id));
        return $games;
    }

	public function getGameDetails() {
		$game_id = (int) $_GET['game_id'];
		$result = $this->getGameDetailsByGameId($game_id);
		$result['rating'] = $this->getGameRatingByUserId($game_id);
		return $result;
	}

    public function changeTerminStatus() {
        $user_id = $this->authUser();
        $termin_id = (int) $_POST['termin_id'];
        $status = $_POST['status'];

        if ($status === 'true') {
            $this->changeTerminByUserId($termin_id, $user_id, false);
        } else if ($status === 'false') {
            $this->changeTerminByUserId($termin_id, $user_id, true);
        }
    }

    public function changeGamesVoting() {
        $user_id = $this->authUser();
        $game_id = (int) $_POST['game_id'];
        $lan_id  = (int) $_POST['lan_id'];

		if($_POST['status'] == 'rate_up') {
            $this->changeLanGameRatingByUserIdAndGamesId($lan_id, $user_id, $game_id, true);
        } else if($_POST['status'] == 'rate_down') {
            $this->changeLanGameRatingByUserIdAndGamesId($lan_id, $user_id, $game_id, false);
        }
    }

    public function getTerminZusagen() {
        $termin_id = (int) $_GET['termin_id'];

        $sql =  'SELECT name, nickname FROM ' .TABLE_LAN_TERMINE_ZUSAGEN. ' AS t1'.
                ' LEFT JOIN '.TABLE_LAN_USER. ' AS t2 ON t2.id = t1.user_id'.
                ' WHERE termin_id = ' .$termin_id;

        $result = $this->database->getResult($sql);
        return array ( 'user' => $result);
    }

    protected function changeTerminByUserId($termin_id, $user_id, $delete) {
        if($delete) {
            $sql =  'DELETE FROM ' .TABLE_LAN_TERMINE_ZUSAGEN.
                    ' WHERE termin_id = '.$termin_id.' AND user_id = ' .$user_id;
        } else {
            $sql =  'INSERT INTO ' .TABLE_LAN_TERMINE_ZUSAGEN. ' (user_id, termin_id)'.
                    ' VALUES ('.$user_id.','.$termin_id.')';
        }
        $result = $this->database->getResult($sql);
        return $result;
    }

    protected function changeLanGameRatingByUserIdAndGamesId($lan_id, $user_id, $game_id, $up_vote) {
        ($up_vote) ? $rating = 1 : $rating = -1;
		if ($this->checkIfVotingExist($lan_id, $user_id, $game_id)) {
			$sql =  'UPDATE ' .TABLE_LAN_GAMES_RATING.
					' SET rating = ' .$rating.
                    ' WHERE lan_id = ' .$lan_id.
                    ' AND games_id = ' .$game_id.
                    ' AND user_id = ' .$user_id;
		} else {
			$sql =  'INSERT INTO ' .TABLE_LAN_GAMES_RATING. 
					' (lan_id, games_id, user_id, rating)'.
                    ' VALUES ('.$lan_id.','.$game_id.','.$user_id.','.$rating.')'; 
		}
        $result = $this->database->getResult($sql);
        return $result;
    }

    protected function checkIfVotingExist($lan_id, $user_id, $game_id) {
        $sql =  'SELECT * FROM ' .TABLE_LAN_GAMES_RATING.
                ' WHERE lan_id = ' .$lan_id.
                ' AND games_id = ' .$game_id.
                ' AND user_id = ' .$user_id;
		$result = $this->database->getSingleResult($sql);
		if ($result) {
			return true;
		}
		return false;
    }

    public function modifyUserInformation() {
        $user_id    = $this->authUser();
        $name       = Sanitize::xss_clean($_POST['name']);
        $ort        = Sanitize::xss_clean($_POST['ort']);
        $nickname   = Sanitize::xss_clean($_POST['nickname']);
        $email      = Sanitize::xss_clean($_POST['email']);

        $sql =  'UPDATE ' . TABLE_LAN_USER . ' SET' .
                ' name = "' . $name . '" , nickname = "' .$nickname. '"'.
                ', ort = "' .$ort.'" , email = "' . $email. '"'. 
                ' WHERE id = ' .$user_id;

        $this->database->getResult($sql);
    }

    protected function getLanGames($lan_id) {
		$response = array();
		$sql =	'SELECT t1.id, name, art, user_min, user_max, SUM(t2.rating) AS vote FROM ' .TABLE_LAN_GAMES. ' AS t1 '.
				'LEFT JOIN '. TABLE_LAN_GAMES_RATING .' AS t2 ON t1.id = t2.games_id '.
				'GROUP BY name '.
				'ORDER BY vote DESC';
		$result = $this->database->getResult($sql);
		foreach ($result as $value) {
			$data = array(
				'game_id'	=> $value['id'],
				'name'		=> $value['name'],
				'art'		=> $value['art'],
				'user_min'	=> $value['user_min'],
				'user_max'	=> $value['user_max'],
				'rating'	=> $value['vote'],
				'auswahl'	=> $this->getGameRatingByUserId($value['id'])
			);
			$response[] = $data;
		}
		return $response;
   }

   protected function getGameRatingByUserId($game_id) {
	   $response = array();
	   $sql =	'SELECT rating FROM ' .TABLE_LAN_GAMES_RATING.
				' WHERE user_id = ' .$this->user_id.
				' AND games_id = ' .$game_id;
	   $result = $this->database->getSingleResult($sql);
	   if ($result == '-1') {
		   $response['status'] = 'down';
	   } else if ($result == '1') {
		   $response['status'] = '';
	   } else {
		   $response['notvoted'] = 'not voted';
	   }
	   return $response;
   }

   protected function getLanTermineCount( $lan_id, $termin_id ) {
       $sql =   'SELECT count(t2.id) FROM ' .TABLE_LAN_TERMINE. ' AS t1'.
                ' LEFT JOIN ' .TABLE_LAN_TERMINE_ZUSAGEN. ' AS t2 ON t1.id = t2.termin_id'.
                ' WHERE lan_id = ' .$lan_id. ' AND t1.id = ' .$termin_id.
                ' GROUP BY t1.id';
       $count = $this->database->getSingleResult($sql);
       return $count;
	}

    protected function getLanTermine($lan_id) {
        $response = array();
        $sql =  'SELECT t1.id, termin FROM ' .TABLE_LAN_TERMINE. ' AS t1'.
                ' WHERE lan_id = '.$lan_id.
                ' ORDER BY termin';
        $result = $this->database->getResult($sql);
        foreach ($result as $value) {
            $termin = new DateTime($value['termin']);

            $data = array(
                'termin_id' => $value['id'],
                'termin' => $termin->format('d.m.Y'),
                'termin_count' => $this->getLanTermineCount($lan_id, $value['id']),
                'status' => $this->getLanTerminUserStatus($value['id'])
            );
			$response[] = $data;
        }
        return $response;
	}

	protected function getGameDetailsByGameId($game_id) {
		$sql =	'SELECT name, art, user_min, user_max, description, link FROM '.TABLE_LAN_GAMES.
				' WHERE id = '.$game_id;
		$game = $this->database->getResult($sql);
		return $game[0];
	}

	protected function getLanTerminUserStatus($termin_id) {
		$sql =	'SELECT id FROM '.TABLE_LAN_TERMINE_ZUSAGEN.
				' WHERE termin_id = '.$termin_id.
				' AND user_id = '.$this->user_id;
		$result = $this->database->getResult($sql);
		(empty($result)) ? $result = '' : $result = 'zusage';
		return $result;
	}

    protected function gatherUserData( $user_id ) {
        $sql =  'SELECT id, name, nickname, ort, email FROM ' .TABLE_LAN_USER.
                ' WHERE id = ' . $user_id;
        $user_data = $this->database->getResult($sql);
        return $user_data[0];
    }

    protected function gatherOverviewData() {
        $response = array();
        $sql = 'SELECT id,name FROM lan';
        $result = $this->database->getResult($sql);
        foreach ($result as $value) {
            $data = array(
                'lan_id' => $value['id'],
                'name' => $value['name']
            );
            $response[] = $data;
        }
        return $response;
    }

	public function debugMail() {
		die();
		$sql = 'SELECT t1.email, t1.auth_hash, t1.name, t2.name AS creator FROM ' . TABLE_LAN_USER . ' AS t1'
			   . ' LEFT JOIN ' . TABLE_LAN_USER.' AS t2 ON t1.creator_id = t2.id'
               . ' WHERE t1.id = 4';

        $user_data = $this->database->getResult($sql);
		$user_data = $user_data[0];
				$message = 'Hi ' . $user_data['name'] . ",\r\n".
                'Du wurdest von ' . $user_data['creator'] . " zu einer LAN eingeladen!\r\n".
                "Unter folgenden Link kannst du dich für die LAN anmelden und Spiele vorschlagen:\r\n".
                'Link: http://lan.nm-gaming.de/module.php?module=lan_vorbereitung&hash=' .$user_data['hash']. "\r\n".
                'LG Dennis';
		// additional headers
		var_dump($message);
		die();
		$user_data['hash'] = 'ilfgZU9u3fVA1IJ9qoh4LCGfqw4AEz';
		$user_data['creator'] = 'Dummy';
		$subject = 'Einladung zur LAN';
		$to = 'dennisjandt@gmx.de';

		$headers = array();
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = "Content-type: text/plain; charset=utf-8";
		$headers[] = 'Subject: '.$subject;
		$headers[] = 'From: Dennis <no-reply@nm-gaming.de>';
		$headers[] = 'X-Mailer: PHP/'.phpversion();

		$boolean = mail($to,$subject,$message, implode("\r\n", $headers));
		var_dump($boolean);
	}

    protected function sendMail($user_id) {
        $sql = 'SELECT t1.email, t1.auth_hash, t1.name, t2.name AS creator FROM ' . TABLE_LAN_USER . ' AS t1'
                . ' LEFT JOIN ' . TABLE_LAN_USER.' AS t2 ON t1.creator_id = t2.id'
                . ' WHERE t1.id = ' .$user_id;

        $user_data = $this->database->getResult($sql);
		$user_data = $user_data[0];

		$subject = 'Einladung zur LAN';
		$to = $user_data['email'];
		$message = 'Hi ' . $user_data['name'] . ",\r\n".
                'Du wurdest von ' . $user_data['creator'] . " zu einer LAN eingeladen!\r\n".
                "Unter folgenden Link kannst du dich für die LAN anmelden und Spiele vorschlagen:\r\n".
                'Link: http://lan.darkdragons.de/module.php?module=lan_vorbereitung&hash=' .$user_data['auth_hash']. "\r\n".
                'LG Dennis';
		// additional headers
		$headers = array();
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = "Content-type: text/plain; charset=utf-8";
		$headers[] = 'Subject: '.$subject;
		$headers[] = 'From: Dennis <no-reply@nm-gaming.de>';
		$headers[] = 'X-Mailer: PHP/'.phpversion();

		$boolean = mail($to,$subject,$message, implode("\r\n", $headers));
		return $boolean;
    }
}
