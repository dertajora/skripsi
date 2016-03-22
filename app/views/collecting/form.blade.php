@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>FORM PENGUMPULAN DATA</b>
@endsection
@section('content')
<?php if (isset($sukses)): ?>
    <div class="alert alert-success">
        <strong>Penambahan data tweet baru berhasil !</strong> 
    </div>
<?php endif ?>

<?php if (isset($gagal)): ?>
   <div class="alert alert-danger">
        <strong>Mohon maaf data tidak ditemukan, silahkan coba kembali.</strong>
    </div>
<?php endif ?>


{{ Form::open(array('route'=>'request-tweet', 'method' => 'POST','role' => 'form')); }}
<!-- <form role="form" method="POST" route='hasil-test-from-user'> -->
<div class="col-lg-6">
<div class="form-group">
    <label>Keyword Pencarian</label>
    <input class="form-control" name="keyword" placeholder="Masukkan keyword....">
</div>

<div class="form-group">
    <label>Jumlah Tweet</label>
    <input class="form-control" name="jumlah" placeholder="Masukkan jumlah tweet....">
</div>
{{Form::submit('Cari Tweet', array('class' => 'btn btn-sm btn-info', ''));}}
</div>

@endsection