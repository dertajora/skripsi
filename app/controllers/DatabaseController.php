<?php

class DatabaseController extends BaseController {

	public function getFirst(){
		// ?$derta = Markers::where('id','=',1)->pluck('name');
		// $tanggal_reg = Researchs::where('researcher_id','=',$id_peneliti)->pluck('tanggal_reg');
         
		// return dd($derta);

		$server = "localhost";
		$username = "root";
		$password = "";
		$database = "maps-test";
         return View::make('database.first')
         ->with('username',$username)
         ->with('password',$password)
         ->with('database',$database); 
    }


	

      

}


	