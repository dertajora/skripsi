@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>TOKEN HASIL PRAPROSES</b>
@endsection

@section('content')


<div class="col-lg-6">

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
			$jumlah_token = count($fitur);
			for ($j=0; $j < $jumlah_token; $j++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$j+1}}</td>
 		        <td>{{$fitur[$j]}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>
</div>
</div>
@endsection