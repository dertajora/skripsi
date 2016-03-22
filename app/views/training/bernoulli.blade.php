@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL TRAINING BERNOULLI</b>
@endsection

@section('content')
<!-- Term dari Data Training -->

Jumlah tweet valid {{$jumlah_tweet_valid}} <br>
Jumlah tweet spam {{$jumlah_tweet_spam}} <br>
<br>
<CENTER><b>HASIL TRAINING TOKEN PRAPROSES</b></CENTER>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Terjadi di Valid</th>
 		        <th>Terjadi di Spam</th>
 		        <th>Probabilitas Valid</th>
 		        <th>Probabilitas Spam</th>
 		        
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token = count($hasil_training);
			for ($i=0; $i < $jumlah_token; $i++) { 
			 	
			  ?>
 		    <tr>
 		    	
 		        <td>{{$i+1}}</td>
 		        <td>{{$hasil_training[$i]['term']}}</td>
 		        <td>{{$hasil_training[$i]['happened_valid']}}</td>
 		        <td>{{$hasil_training[$i]['happened_spam']}}</td>
 		        <td>{{$hasil_training[$i]['prob_valid']}}</td>
 		        <td>{{$hasil_training[$i]['prob_spam']}}</td>
 		        
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>

 <br>
<CENTER><b>HASIL TRAINING FITUR HASIL SELEKSI</b></CENTER>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Terjadi di Valid</th>
 		        <th>Terjadi di Spam</th>
 		        <th>Probabilitas Valid</th>
 		        <th>Probabilitas Spam</th>
 		        
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token = count($hasil_training_seleksi);
			for ($i=0; $i < $jumlah_token; $i++) { 
			 	
			  ?>
 		    <tr>
 		    	
 		        <td>{{$i+1}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['term']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['happened_valid']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['happened_spam']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['prob_valid']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['prob_spam']}}</td>
 		        
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>	


@endsection