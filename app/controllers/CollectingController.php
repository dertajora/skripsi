<?php

class CollectingController extends BaseController {

	public function getConfirm(){

		return View::make('collecting.collect');
	}

	public function getView(){
		
		return View::make('collecting.collect');
	}

	public function getForm(){
		
		return View::make('collecting.form');
	}

	public function postRequestTweet(){
		
		include(app_path().'/includes/TwitterAPIExchange.php');
		
		#variabel parameter searching
		$keyword = rawurlencode(Input::get('keyword'));
		$jumlah = Input::get('jumlah');

		##konfigurasi autentifikasi twitter API
		$settings = array(
		    'oauth_access_token' => "3228114704-9XbnfYIuMu3FicOSxImQmoqwUjkzposZ576IUnB",
		    'oauth_access_token_secret' => "wqUjVbIoxQpzwtBys4C5GYpfGspnasiXVtwpzT89HlSsS",
		    'consumer_key' => "4jElwcqJSkYaUE05UHhEPLzUz",
		    'consumer_secret' => "HGAdUDoIDFu9SCXZKRaDC4eMWAAfoFp3fA4sD3gF0yHmbibsWF"
		);


		##Pilihan Twitter API
		$url = "https://api.twitter.com/1.1/statuses/mentions_timeline.json"; //just for the auntheticating user tok bro,
		$requestMethod = "GET";
		$getfield = 'count=22&since_id=3228114704';


		#pilihan twitter API
		$url = "https://api.twitter.com/1.1/search/tweets.json";
		##method twitter API
		$requestMethod = "GET";
		##keyword pengambilan data

		$getfield = "q=".$keyword."&count=".$jumlah;

		// $getfield = 'q=jalan%20bergelombang&count=10';


		#eksekusi pengambilan data oleh twitter API
		$twitter = new TwitterAPIExchange($settings);
		$string = json_decode($twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(),$assoc = TRUE);
		
		//normalisasi format data
		$string = $string['statuses'];

		$data_tweet =[];

		$i=0;
		foreach ($string as $row) {
			$data_tweet[$i]['tweet'] = $string[$i]['text'];
			$data_tweet[$i]['username'] = $string[$i]['user']['screen_name'];
			// $input_data = new TabelCoba;
			// $input_data->tweet = $data_tweet[$i];
			// $input_data->kelas = 1;
			// $input_data->save();
			$i=$i+1;
		}
		$jumlah = count($data_tweet);
		if ($jumlah == 0) {
			return View::make('collecting.form')->with('gagal','Penambahan data tweet berhasil !');
		}
		
		return View::make('collecting.collect')->with('data_tweet',$data_tweet);
	}

	public function postSaveTweet(){
		$tweet = Input::get('tweet');
		$confirm_save = Input::get('confirm_save');
		$jumlah_data = count($confirm_save);
		$kelas_tweet = Input::get('kelas_tweet');

		
		$data_simpan = [];
		$i=0;
		foreach ($confirm_save as $key => $n) {
			$data_simpan[$i] = $tweet[$n];
			$i=$i+1;
		
		}

		$jumlah = count($data_simpan);
		if ($jumlah == 0) {
			return View::make('collecting.form')->with('gagal','Penambahan data tweet berhasil !');
		}

		for ($i=0; $i < $jumlah ; $i++) { 
			$input_data = new TabelCoba;
			$input_data->tweet = $data_simpan[$i];
			$input_data->kelas = $kelas_tweet;
			$input_data->save();
		}
			
		return View::make('collecting.form')->with('sukses','Penambahan data tweet berhasil !');
	}
	public function getHome(){
			include(app_path().'/includes/TwitterAPIExchange.php');
			#konfigurasi autentifikasi twitter API
		##konfigurasi autentifikasi twitter API
		$settings = array(
		    'oauth_access_token' => "3228114704-9XbnfYIuMu3FicOSxImQmoqwUjkzposZ576IUnB",
		    'oauth_access_token_secret' => "wqUjVbIoxQpzwtBys4C5GYpfGspnasiXVtwpzT89HlSsS",
		    'consumer_key' => "4jElwcqJSkYaUE05UHhEPLzUz",
		    'consumer_secret' => "HGAdUDoIDFu9SCXZKRaDC4eMWAAfoFp3fA4sD3gF0yHmbibsWF"
		);


		##Pilihan Twitter API
		$url = "https://api.twitter.com/1.1/statuses/mentions_timeline.json"; //just for the auntheticating user tok bro,
		$requestMethod = "GET";
		$getfield = 'count=22&since_id=3228114704';


		#pilihan twitter API
		$url = "https://api.twitter.com/1.1/search/tweets.json";
		##method twitter API
		$requestMethod = "GET";
		##keyword pengambilan data
		$getfield = 'q=daniel%20oscar&count=10';


		#eksekusi pengambilan data oleh twitter API
		$twitter = new TwitterAPIExchange($settings);
		$string = json_decode($twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(),$assoc = TRUE);

		//normalisasi format data
		$string = $string['statuses'];

		$data_tweet=[];

		$i=0;
		foreach ($string as $row) {
			$data_tweet[$i] = $string[$i]['text'];
			// $input_data = new TabelCoba;
			// $input_data->tweet = $data_tweet[$i];
			// $input_data->kelas = 1;
			// $input_data->save();
			$i=$i+1;
		}
		$jumlah = count($data_tweet);
		if ($jumlah == 0) {
			return "zonk";
		}
		


		// return View::make('collecting.collect')->with('data',$data_tweet);
		return dd($data_tweet); 






         
    }


    public function getEmbed(){
         return View::make('openstreet.embed'); 
    }

  
	

      

}


	