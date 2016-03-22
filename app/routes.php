<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//ini google maps
Route::get('/', array('as' => 'home', 'uses' =>'SkripsiController@getHome'));
Route::get('datatable', array('as' => 'datatable', 'uses' =>'SkripsiController@getDatatable'));

//collect data
Route::get('collect', array('as' => 'collect', 'uses' =>'CollectingController@getHome'));
Route::get('collect-form', array('as' => 'collect-form', 'uses' =>'CollectingController@getForm'));
Route::post('save-tweet', array('as' => 'save-tweet', 'uses' =>'CollectingController@postSaveTweet'));
Route::get('collecting', array('as' => 'collecting', 'uses' =>'CollectingController@getConfirm'));
Route::post('collecting-tweet',array('as' => 'request-tweet','uses' => 'CollectingController@postRequestTweet'));

Route::get('home', array('as' => 'home', 'uses' =>'SkripsiController@getHome'));
Route::get('data-training', array('as' => 'data-training', 'uses' =>'SkripsiController@getDatatraining'));
Route::get('data-training-2', array('as' => 'data-training-2', 'uses' =>'SkripsiController@getDatatrainingDua'));
Route::get('data-uji', array('as' => 'data-uji', 'uses' =>'SkripsiController@getDataUji'));
//tes
Route::get('chart-info', array('as' => 'chart', 'uses' =>'SkripsiController@getChart'));
#praproses
Route::get('tokenization', array('as' => 'tokenization', 'uses' =>'SkripsiController@getTokenization'));
Route::get('strtolower', array('as' => 'strtolower', 'uses' =>'SkripsiController@getStrtolower'));
Route::get('removesymbol',array('as'=> 'removesymbol','uses'=>'SkripsiController@getRemovesymbol'));
Route::get('removeusername', array('as' => 'removeusername', 'uses' =>'SkripsiController@getRemoveUsername'));
Route::get('removeurl', array('as' => 'removeurl', 'uses' =>'SkripsiController@getRemoveURL'));
Route::get('normalisasi', array('as' => 'normalisasi', 'uses' =>'SkripsiController@getNormalisasi'));
Route::get('stopword', array('as' => 'stopword', 'uses' =>'SkripsiController@getStopword'));
Route::get('stemmer', array('as' => 'stemmer', 'uses' =>'SkripsiController@getStemmer'));
Route::get('hasil-praproses', array('as' => 'hasil-praproses', 'uses' =>'SkripsiController@HasilPraproses'));

//seleksi chi square
Route::get('chi', array('as' => 'chi', 'uses' =>'SkripsiController@cobaSeleksi'));
Route::get('show-seleksi', array('as' => 'show-seleksi', 'uses' =>'SeleksiController@ShowSeleksi'));
Route::get('hasil-seleksi', array('as' => 'hasil-seleksi', 'uses' =>'SkripsiController@getHasilseleksi'));

//hasil-training
Route::get('multinomial', array('as' => 'multinomial', 'uses' =>'TrainingController@getTrainingMultinomial'));
Route::get('hasil_multinomial', array('as' => 'hasil-multinomial', 'uses' =>'TrainingController@HasilMultinomial'));
Route::get('bernoulli', array('as' => 'bernoulli', 'uses' =>'TrainingController@gettrainingBernoulli'));
Route::get('hasil_bernoulli', array('as' => 'hasil-bernoulli', 'uses' =>'TrainingController@HasilBernoulli'));
Route::get('hasiltraining', array('as' => 'hasil-training', 'uses' =>'TrainingController@HasilTraining'));
//klasifikasi
Route::get('multinomial_satu', array('as' => 'klasifikasi-multinomial', 'uses' =>'KlasifikasiController@MultinomialSatu'));
Route::get('bernoulli_satu', array('as' => 'klasifikasi-bernoulli', 'uses' =>'KlasifikasiController@BernoulliSatu'));
Route::get('ujimultinomial', array('as' => 'uji-multinomial', 'uses' =>'KlasifikasiController@UjiMultinomialPraproses'));
Route::get('ujimultinomial2', array('as' => 'uji-multinomial-2', 'uses' =>'KlasifikasiController@UjiMultinomialPraproses2'));
Route::get('ujimultinomial_seleksi', array('as' => 'uji-multinomial-seleksi', 'uses' =>'KlasifikasiController@UjiMultinomialSeleksi'));
Route::get('ujimultinomial_seleksi2', array('as' => 'uji-multinomial-seleksi-2', 'uses' =>'KlasifikasiController@UjiMultinomialSeleksi2'));
Route::get('ujibernoulli', array('as' => 'uji-bernoulli', 'uses' =>'KlasifikasiController@UjiBernoulli'));
Route::get('ujibernoulli_seleksi', array('as' => 'uji-bernoulli-seleksi', 'uses' =>'KlasifikasiController@UjiBernoulliSeleksi'));
Route::get('ujibernoulli_seleksi2', array('as' => 'uji-bernoulli-seleksi-2', 'uses' =>'KlasifikasiController@UjiBernoulliSeleksi2'));

//tes
Route::get('tesfromuser', array('as' => 'tesfromuser', 'uses' =>'KlasifikasiController@ShowTestFromUser'));
Route::post('hasiltesuser', array('as' => 'hasil-test-from-user', 'uses' =>'KlasifikasiController@ResultTestFromUser'));
Route::get('fromapi', array('as' => 'search-tweet-keyword', 'uses' =>'KlasifikasiController@SearchFromAPI'));
Route::post('searchfromapi', array('as' => 'search-tweet-from-api', 'uses' =>'KlasifikasiController@SearchKeyword'));
//evaluasi
Route::get('pengaruhclassifier', array('as' => 'classifier', 'uses' =>'EvaluasiController@pengaruhclassifier'));
Route::get('pengaruhseleksi', array('as' => 'seleksi', 'uses' =>'EvaluasiController@pengaruhseleksi'));
Route::get('pengaruhdatatraining', array('as' => 'pengaruh-data-training', 'uses' =>'EvaluasiController@PengaruhDatatraining'));

Route::get('exceldatatraining', array('as' => 'excel', 'uses' =>'ExcelController@getExportDataTraining'));
Route::get('exceldatauji', array('as' => 'excel', 'uses' =>'ExcelController@getExportDataUji'));



