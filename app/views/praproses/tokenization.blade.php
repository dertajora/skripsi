@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>TOKENIZATION</b>
@endsection

@section('content')

<?php 

$vocab_unique = array_unique($token);
$vocab_reindexed = array_values($vocab_unique);
?>
</pre>
<div class="col-lg-6">
 <div class="table-responsive">

<center><b>TERM DARI DATA TRAINING</b></center>
<table class="table table-bordered table-hover table-striped width='300'">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Term</th>
 		    </tr>
 		</thead>
 		<tbody>
 			<?php 
			$jumlah_term = count($token);
			for ($i=0; $i < $jumlah_term; $i++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$i+1}}</td>
 		        <td>{{$token[$i]}}</td>
 		        
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table><br><br>
 <center><b>TOKEN DARI DATA TRAINING</b></center>
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
 
</div>
</div>
@endsection