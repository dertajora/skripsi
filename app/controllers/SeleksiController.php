<?php

class SeleksiController extends BaseController {

	public function ShowSeleksi(){

		$Datapraproses = FiturPraproses::get();
		$i=0;

		foreach ($Datapraproses as $row) {
			
			$token[$i] = $row->term;
			$i++;
		}

		$Dataseleksi = FiturSeleksi::get();
		$j=0;
		$token_seleksi=array();
		foreach ($Dataseleksi as $row) {
			
			$token_seleksi[$j]['term']=$row->term;
			$token_seleksi[$j]['prob_valid_m']=$row->prob_valid_m;
			$token_seleksi[$j]['prob_spam_m']=$row->prob_spam_m;
			$token_seleksi[$j]['prob_valid_b']=$row->prob_valid_b;
			$token_seleksi[$j]['prob_spam_b']=$row->prob_spam_b;
			$j++;
		}

		

         return View::make('seleksi.show_hasil')->with('token_seleksi',$token_seleksi)->with('token',$token); 
    }
      

}


	