@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL TRAINING</b>
@endsection

@section('content')

<div class="col-lg-8">

<div class="table-responsive">



 <center><b>HASIL TRAINING FITUR HASIL SELEKSI</b></center>
<table class="table table-bordered table-hover table-striped col-lg-4">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Probabilitas Valid (M)</th>
 		        <th>Probabilitas Spam (M)</th>
 		        <th>Probabilitas Valid (B)</th>
 		        <th>Probabilitas Spam (B)</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token_seleksi = count($token_seleksi);
			for ($i=0; $i < $jumlah_token_seleksi; $i++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$i+1}}</td>
 		        <td>{{$token_seleksi[$i]['term']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_valid_m']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_spam_m']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_valid_b']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_spam_b']}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
 </div>


@endsection