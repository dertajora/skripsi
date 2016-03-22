@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>DATA TRAINING</b>
@endsection

@section('content')
<BR><BR>
<div class="col-lg-10">
 <div class="table-responsive">
 <table class="table table-bordered table-hover table-striped">
 		<thead>
 		    <tr>
 		        <th>ID</th>
 		        <th>Tweet</th>
 		        <th>Kelas Asli</th>
 		        <th>Kelas Hasil</th>
 		       
 		    </tr>
 		</thead>
 		<tbody>
 			<?php $i=1;?>
			@foreach ($data_trainings as $row)
 		    <tr>
 		        <td>{{$i}}</td>
 		        <td>{{$row->tweet}}</td>
 		        <td><?php 
 		        $kelas = $row->kelas_asli;
 		        if ($kelas==1) {
 		        	echo "Valid";
 		        }else if($kelas==2){
 		        	echo "Spam";
 		        }
 		        $i++;
 		        ?></td>
 		        <td>{{$row->kelas_hasil}}</td>
 		       
 		    </tr>
            @endforeach                               
 		</tbody>
 </table>
 </div>
</div>

@endsection
