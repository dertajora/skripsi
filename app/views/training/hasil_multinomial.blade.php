@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL TRAINING MULTINOMIAL NBC</b>
@endsection

@section('content')

<div class="col-lg-8">
<center><b>HASIL TRAINING FITUR HASIL PRAPROSES</b></center>
<div class="table-responsive">

<table class="table table-bordered table-hover table-striped col-lg-4">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Probabilitas Valid</th>
 		        <th>Probabilitas Spam</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token = count($token_praproses);
			for ($j=0; $j < $jumlah_token; $j++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$j+1}}</td>
 		        <td>{{$token_praproses[$j]['fitur']}}</td>
 		        <td>{{$token_praproses[$j]['prob_valid_m']}}</td>
 		        <td>{{$token_praproses[$j]['prob_spam_m']}}</td>
 		        
 		     
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>

 <center><b>HASIL TRAINING FITUR HASIL SELEKSI</b></center>
<table class="table table-bordered table-hover table-striped col-lg-4">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Probabilitas Valid</th>
 		        <th>Probabilitas Spam</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token_seleksi = count($token_seleksi);
			for ($i=0; $i < $jumlah_token_seleksi; $i++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$i+1}}</td>
 		        <td>{{$token_seleksi[$i]['fitur']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_valid_m']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_spam_m']}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
 </div>


@endsection