<?php

class MapsController extends BaseController {

	public function getIndex() {
    // I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
  

    return View::make('home.maps');    	
    }
    
    public function getEmbed(){
    	 return View::make('maps.embed'); 
    }

    public function getSearch(){
         return View::make('maps.search'); 
    }

    public function getDirection(){
         return View::make('maps.direction'); 
    }

    public function getPlaces(){
         return View::make('maps.places'); 
    }

    public function getView(){
         return View::make('maps.view'); 
    }

    public function getStatic(){
         return View::make('maps.static'); 
    }

    public function getMarker(){
         return View::make('maps.marker'); 
    }
    public function getGeolocation(){
         return View::make('maps.geolocation'); 
    }

    public function getSignedin(){
         return View::make('maps.signedin'); 
    }

    public function getInfowindow(){
         return View::make('maps.infowindow'); 
    }
    public function getIcon(){
         return View::make('maps.icon'); 
    }

    public function getStaticdouble(){
         return View::make('maps.static_double'); 
    }
    public function getDoubleinfo(){
         return View::make('maps.ageng'); 
    }
    
   
    public function getPanduan() {
    // I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
  

    return View::make('Login.panduan');
    // return View::make('Home.bisdinamis');
      
    }


  public function postRegister(){
    return "sampai";
  }

	

      

}


	