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
Jumlah Token Valid{{$jumlah_token_valid}} <br>
Jumlah Token Spam {{$jumlah_token_spam}} <br>

Pembagi Valid {{$jumlah_token_valid+$jumlah_term_valid}} <br>
Pembagi Spam {{$jumlah_token_spam+$jumlah_term_spam}} <br>

Jumlah Tweet Valid {{$jumlah_valid}} <br>
Jumlah Tweet Spam {{$jumlah_spam}} <br>

<br>
<CENTER><b>HASIL TRAINING TOKEN PRAPROSES</b></CENTER>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		        <th>Kemunculan di Valid</th>
 		        <th>Kemunculan di Spam</th>
 		        <th>Probabilitas Valid M</th>
 		        <th>Probabilitas Spam M</th>
 		        <th>Terjadi di Valid</th>
 		        <th>Terjadi di Spam</th>
 		        <th>Probabilitas Valid B</th>
 		        <th>Probabilitas Spam B</th>
 		        
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
 		        <td>{{$hasil_training[$i]['kemunculan_valid']}}</td>
 		        <td>{{$hasil_training[$i]['kemunculan_spam']}}</td>
 		        <td>{{$hasil_training[$i]['prob_valid_m']}}</td>
 		        <td>{{$hasil_training[$i]['prob_spam_m']}}</td>
 		        <td>{{$hasil_training[$i]['happened_valid']}}</td>
 		        <td>{{$hasil_training[$i]['happened_spam']}}</td>
 		        <td>{{$hasil_training[$i]['prob_valid_b']}}</td>
 		        <td>{{$hasil_training[$i]['prob_spam_b']}}</td>
 		        
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>	
<br><br>



@endsection