@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>MENGHAPUS STOPWORD</b>
@endsection

@section('content')


<div class="col-lg-6">
<center><b>TOKEN AWAL</b></center>
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

 <center><b>TOKEN HASIL NORMALISASI</b></center>
<table class="table table-bordered table-hover table-striped col-lg-4">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token_baru = count($token_baru);
			for ($i=0; $i < $jumlah_token_baru; $i++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$i+1}}</td>
 		        <td>{{$token_baru[$i]}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
 </div>
 <div class="col-lg-6">
@endsection