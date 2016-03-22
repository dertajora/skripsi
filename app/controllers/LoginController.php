<?php

class LoginController extends BaseController {

	public function getLogin(){
		return View::make('login.login');
    }
    public function getDrag(){
		return View::make('maps.drag');
    }
	

}


	