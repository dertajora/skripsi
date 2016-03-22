@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>SELEKSI FITUR</b>
@endsection

@section('content')


<div class="col-lg-10">
	<center><b>TOKEN HASIL PRAPROSES</b></center>
<div class="table-responsive">


<Br>
 <!-- <center><b>PERHITUNGAN NILAI CHI TOKEN</b></center> -->
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Jumlah A</th>
 		        <th>Jumlah B</th>
 		        <th>Jumlah C</th>
 		        <th>Jumlah D</th>
 		        <th>Nilai Chi</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token_matematis = count($token_matematis);
			for ($i=0; $i < $jumlah_token_matematis; $i++) { 
			 	
			  ?>
 		    <tr>
 		    	
 		        <td>{{$i+1}}</td>
 		        <td>{{$token_matematis[$i]['token']}}</td>
 		        <td>{{$token_matematis[$i]['jumlah_A']}}</td>
 		        <td>{{$token_matematis[$i]['jumlah_B']}}</td>
 		        <td>{{$token_matematis[$i]['jumlah_C']}}</td>
 		        <td>{{$token_matematis[$i]['jumlah_D']}}</td>
 		        <td>{{$token_matematis[$i]['nilai_chi']}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
 <Br>
 </DIV>
 <div class="col-lg-6">
 <center><b>FITUR LOLOS SELEKSI</b></center>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token_fix = count($token_fix);
			for ($i=0; $i < $jumlah_token_fix; $i++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$i+1}}</td>
 		        <td>{{$token_fix[$i]}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
 </div>
</div>
@endsection