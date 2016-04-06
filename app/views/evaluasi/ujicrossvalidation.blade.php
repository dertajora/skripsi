
  

@extends('layout.skripsi')
@section('head')
<script src="chart/chart.js"></script> 
@endsection
@section('subjudul')
    <b>Waktu eksekusi : {{$time}} detik</b>
@endsection

@section('judul')
    <b>EVALUASI 4 - PENGUJIAN K-FOLD CROSS VALIDATION </b>
@endsection

@section('content')
<b>Deskripsi</b>
<br><p>

Pada pengujian ini akan digunakan 600 data training. Metode pengujian yang digunakan adalah K-Fold Cross Validation, yakni merotasi 600 agar setiap data minimal menjadi data uji satu kali.

 
</p>
<div class="row">
<div class="col-lg-10">
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title"><center>KLASIFIKASI MODEL MULTINOMIAL</center></h3>
</div>
<div class="panel-body">
<div class="row">
    <center>
    <div id="canvas-holder">
            <canvas id="buyers" width="600" height="300"></canvas>
    </div>
    <br>
    <br>
    <B>
    AKURASI SISTEM = {{$average_of_multinomial*100}} %</b>
    </center>
   
        

</div>
</div>
</div>




<div class="row">
<div class="col-lg-12">
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title"><center>KLASIFIKASI MODEL BERNOULLI</center></h3>
</div>
<div class="panel-body">
<div class="row">
 <center>
    <div id="canvas-holder">
            <canvas id="bernoulli" width="600" height="300"></canvas>
    </div>
    <br>
    <br>
    <B>
    AKURASI SISTEM = {{$average_of_bernoulli * 100}} %</B>
    </center>

</div>
</div>
</div>
</div>
</div>



<script>
    // line chart data
    var buyerData = {
        labels :  ["Fold 1","Fold 2","Fold 3","Fold 4","Fold 5","Fold 6"],
        datasets : [
        {
            fillColor : "rgba(172,194,132,0.4)",
            strokeColor : "#ACC26D",
            pointColor : "#fff",
            pointStrokeColor : "#9DB86D",
            data : [{{$array[0]}},{{$array[1]}},{{$array[2]}},{{$array[3]}},{{$array[4]}},{{$array[5]}}]
           }
    ]
    }
    // get line chart canvas
    var buyers = document.getElementById('buyers').getContext('2d');
    // draw line chart
    new Chart(buyers).Line(buyerData);

     // line chart data
    var BernoulliData = {
        labels : ["Fold 1","Fold 2","Fold 3","Fold 4","Fold 5","Fold 6"],
        datasets : [
        {
            fillColor : "rgba(172,194,132,0.4)",
            strokeColor : "#ACC26D",
            pointColor : "#fff",
            pointStrokeColor : "#9DB86D",
            data : [{{$array1[0]}},{{$array1[1]}},{{$array1[2]}},{{$array1[3]}},{{$array1[4]}},{{$array1[5]}}]
           }
    ]
    }
    // get line chart canvas
    var bernoulli = document.getElementById('bernoulli').getContext('2d');
    // draw line chart
    new Chart(bernoulli).Line(BernoulliData);
            
</script>
@endsection