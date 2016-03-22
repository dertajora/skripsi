@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL KLASIFIKASI</b>
@endsection

@section('subjudul')
    
    <a href="tesfromuser" class="btn btn-sm btn-warning" role="button">KEMBALI</a>
@endsection

@section('content')
<div class="row">
<div class="col-sm-6">
<div class="panel panel-primary">

	<div class="panel-heading">
        <h3 class="panel-title">Tweet Uji</h3>
    </div>
    <div class="panel-body">
       {{$tweet_asli}}
    </div>
</div>
</div>
<div class="col-sm-6">
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Tweet Hasil Praproses</h3>
    </div>
    <div class="panel-body">
       {{$tweet_praproses}}
    </div>
</div>
</div>
</div>
<div class="row">

<div class="col-sm-6">
	<center><B><H3>DATA TRAINING 1</H3></B></center>
                        
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Klasifikasi Model Multinomial</h3>
                            </div>
                            <div class="panel-body">
                                Kelas Tweet = {{$kelas_m_s1}} <br>
                                Probabilitas Valid = {{$probabilitas_valid_m_s1}} <br>
                                Probabilitas Spam = {{$probabilitas_spam_m_s1}}
                            </div>
                        </div>
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Klasifikasi Model Bernoulli</h3>
                            </div>
                            <div class="panel-body">
                                Kelas Tweet = {{$kelas_b_s1}} <br>
                                Probabilitas Valid = {{$probabilitas_valid_b_s1}} <br>
                                Probabilitas Spam = {{$probabilitas_spam_b_s1}}
                            </div>
                        </div>
</div>
<div class="col-sm-6">
	<center><B><H3>DATA TRAINING 2</H3></B></center>
                     
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Klasifikasi Model Multinomial</h3>
                            </div>
                            <div class="panel-body">
                                Kelas Tweet = {{$kelas_m_s2}} <br>
                                Probabilitas Valid = {{$probabilitas_valid_m_s2}} <br>
                                Probabilitas Spam = {{$probabilitas_spam_m_s2}}
                            </div>
                        </div>
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Klasifikasi Model Bernoulli</h3>
                            </div>
                            <div class="panel-body">
                                Kelas Tweet = {{$kelas_b_s2}} <br>
                                Probabilitas Valid = {{$probabilitas_valid_b_s2}} <br>
                                Probabilitas Spam = {{$probabilitas_spam_b_s2}}
                            </div>
                        </div>

</div>
</div>
@endsection