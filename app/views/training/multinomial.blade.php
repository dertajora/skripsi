@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL TRAINING MULTINOMIAL</b>
@endsection

@section('content')
<!-- Term dari Data Training -->

Jumlah Term Valid {{$jumlah_term_valid}} <br>
Jumlah Term Spam {{$jumlah_term_spam}} <br>  
Jumlah Token {{$jumlah_token}} <br>

<br>
<CENTER><b>HASIL TRAINING TOKEN PRAPROSES</b></CENTER>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Kemunculan di Valid</th>
 		        <th>Kemunculan di Spam</th>
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
 		        <td>{{$hasil_training[$i]['kemunculan']}}</td>
 		        <td>{{$hasil_training[$i]['kemunculan_spam']}}</td>
 		        <td>{{$hasil_training[$i]['prob_valid']}}</td>
 		        <td>{{$hasil_training[$i]['prob_spam']}}</td>
 		        
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>	
<br><br>
 <CENTER><b>HASIL TRAINING FITUR HASIL SELEKSI</b></CENTER>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Kemunculan di Valid</th>
 		        <th>Kemunculan di Spam</th>
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
 		        <td>{{$hasil_training_seleksi[$i]['kemunculan']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['kemunculan_spam']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['prob_valid']}}</td>
 		        <td>{{$hasil_training_seleksi[$i]['prob_spam']}}</td>
 		        
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>	


@endsection