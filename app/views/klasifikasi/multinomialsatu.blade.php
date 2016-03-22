@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL KLASIFIKASI MULTINOMIAL</b>
@endsection

@section('content')
<br>

<!-- Term dari Data Training -->
Data uji = {{$data_uji}}
<br>Hasil praproses : {{$tweet}}
<br>Probabilitas Valid {{$probabilitas_valid}}
<br>Probabilitas Spam {{$probabilitas_spam}}

<br>Kelas <B>{{$kelas}}</B><BR><bR>

<!-- Hasil Training</br>
<pre>
<?php print_r($hasil_training);?>
</pre> --><br>

@endsection