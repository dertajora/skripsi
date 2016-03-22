@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>TES KLASIFIKASI </b>
@endsection

@section('content')
{{ Form::open(array('route'=>'search-tweet-from-api', 'method' => 'POST','role' => 'form')); }}
<!-- <form role="form" method="POST" route='hasil-test-from-user'> -->

<div class="form-group">
    <label>Pengujian terhadap tweet yang didapatkan dari proses pencarian tweet secara random menggunakan Twitter API. Untuk melakukan pencarian tweet digunakan kata kunci tententu yang dapat diketikkan pada formulir berikut. Outputnya adalah hasil klasifikasi dari tweet tersebut berdasarkan metode Bernoulli dan NBC</label>
    <input class="form-control" placeholder="Silahkan masukkan kata kunci pencarian, contoh 'jalan rusak' atau 'jalan jalan ke pantai'" name="keyword" autofocus></textarea> 
</div>
{{Form::submit('Cari dan Klasifikasikan', array('class' => 'btn btn-sm btn-info', ''));}}



@endsection