@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL SELEKSI FITUR</b>
@endsection

@section('content')


<div class="col-lg-6">
<center><b>FITUR HASIL PRAPROSES</b></center>
<div class="table-responsive">

<table class="table table-bordered table-hover table-striped col-lg-4">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token = count($token);
			for ($j=0; $j < $jumlah_token; $j++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$j+1}}</td>
 		        <td>{{$token[$j]}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>

 <center><b>FITUR LOLOS SELEKSI</b></center>
<table class="table table-bordered table-hover table-striped col-lg-4">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		     <!--    <th>Prob. Valid M</th>
 		        <th>Prob. Spam M</th>
 		        <th>Prob. Valid B</th>
 		        <th>Prob. Spam B</th> -->
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
 		        <!-- <td>{{$token_seleksi[$i]['prob_valid_m']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_spam_m']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_valid_b']}}</td>
 		        <td>{{$token_seleksi[$i]['prob_spam_b']}}</td> -->
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
 </div>
 <div class="col-lg-6">
@endsection