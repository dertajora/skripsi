@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL KLASIFIKASI BERNOULLI</b>
@endsection

@section('content')
<!-- Term dari Data Training -->
Data uji = {{$data_uji}}
<br>Probabilitas Valid {{$probabilitas_valid}}
<br>Probabilitas Spam {{$probabilitas_spam}}

<br>Kelas <b>{{$kelas}}</b><Br><br>

Hasil Training</br>
<pre>
<?php print_r($hasil_training);?>
</pre><br>

@endsection