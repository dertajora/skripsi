@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>REMOVE USERNAME DAN HASHTAG</b>
@endsection

@section('content')


<?php

$vocab_unique = array_unique($token);
$vocab_reindexed = array_values($vocab_unique);
?>


<div class="col-lg-6">
	<center><b>TOKEN DARI DATA TRAINING</b></center>
<div class="table-responsive">

<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_token = count($vocab_reindexed);
			for ($j=0; $j < $jumlah_token; $j++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$j+1}}</td>
 		        <td>{{$vocab_reindexed[$j]}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>

 <center><b>TOKEN SETELAH PENGHAPUSAN USERNAME DAN HASHTAG</b></center>
<table class="table table-bordered table-hover table-striped width='300'">
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
</div>
@endsection