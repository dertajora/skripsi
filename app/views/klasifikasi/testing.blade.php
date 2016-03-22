@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>TES KLASIFIKASI </b>
@endsection

@section('content')
{{ Form::open(array('route'=>'hasil-test-from-user', 'method' => 'POST','role' => 'form')); }}
<!-- <form role="form" method="POST" route='hasil-test-from-user'> -->

<div class="form-group">
    <label>Pengujian terhadap tweet yang diketikkan langsung oleh pengguna. Outputnya adalah hasil klasifikasi dari tweet tersebut berdasarkan metode Bernoulli dan NBC</label>
    <textarea placeholder="Silahkan masukkan tweet disini..." class="form-control" rows="3" name="tweet" autofocus></textarea> 
</div>
{{Form::submit('Klasifikasikan', array('class' => 'btn btn-sm btn-info', ''));}}

</form>

@endsection