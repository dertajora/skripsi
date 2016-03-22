<?php

class DashboardController extends BaseController {

	public function getIndex(){
         return View::make('dashboard.dashboard'); 
    }

    public function getDraggable(){
         return View::make('resource.draggable-3'); 
    }

    public function postDraggable(){
         $latlng = Input::get('txt_latlng');
         return dd($latlng);
    }


    
  
	

      

}


	