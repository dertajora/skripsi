<?php

class OpenstreetController extends BaseController {

	public function getOpenstreet(){
         return View::make('openstreet.openstreet'); 
    }


    public function getEmbed(){
         return View::make('openstreet.embed'); 
    }

  
	

      

}


	