@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>MENGUBAH DATA TRAINING MENJADI HURUF KECIL</b>
@endsection

@section('content')
<div class="col-lg-10">
 <div class="table-responsive">
 <table class="table table-bordered table-hover table-striped">
 		<thead>
 		    <tr>
 		        <th>ID</th>
 		        <th>Tweet</th>
 		        <th>Kelas</th>
 		       
 		    </tr>
 		</thead>
 		<tbody>
 			<?php $i=1;?>
			@foreach ($data_trainings as $row)
 		    <tr>
 		        <td>{{$i}}</td>
 		        <td>{{strtolower($row->tweet)}}</td>
 		        <td><?php 
 		        $kelas = $row->kelas;
 		        if ($kelas==1) {
 		        	echo "Valid";
 		        }else if($kelas==2){
 		        	echo "Spam";
 		        }
 		        $i++;
 		        ?></td>
 		       
 		    </tr>
            @endforeach                               
 		</tbody>
 </table>
 </div>
</div>
@endsection
