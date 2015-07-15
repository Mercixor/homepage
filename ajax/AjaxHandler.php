<?php

namespace ajax;

class AjaxHandler extends BasicAjaxHandler{

	public function getStarted() {
		$response = array('message' => 'Hallo');
		return $response;
	}
}
