@extends('layout.skripsi')
@section('head')
<script src="chart/chart.js"></script> 
@endsection
@section('judul')
    <b>EVALUASI 1 - PENGARUH CLASSIFIER</b>
@endsection

@section('content')
<b>Deskripsi</b>
<br><p>
Evaluasi dilakukan dengan menggunakan dua metode klasifikasi Naive Bayes Classifier yakni Multinomial dan Bernoulli. 
Data training yang digunakan adalah 200 tweet valid dan 200 tweet spam.   
Perbandingan performa akan dilakukan untuk masing-masing classifier.
</p>

<div class="row">
<div class="col-lg-8">
<div class="panel panel-primary">
<div class="panel-heading">
    <h3 class="panel-title">MODEL KLASIFIKASI</h3>
</div>
<div class="panel-body">
<div class="row">
    <div class="col-lg-4">
    <center>
        <center><b>Klasifikasi Model Multinomial</b></center></br>
        <div id="canvas-holder">
            <canvas id="chart-area" width="200" height="200"/>
        </div>
    </center>
    

    
    <td colspan="3"><center><b>Performa</b></center></td>

    </div>
    <div class="col-lg-2"></div>
    <div class="col-lg-4">
    <center>
        <center><b>Klasifikasi Model Bernoulli</b></center></br>
        <div id="canvas-holder">
            <canvas id="chart-area2" width="200" height="200"/>
        </div>
    </center>
    

    

    <td colspan="3"><center><b>Performa</b></center></td>

    <canvas id="chart"></canvas>
    <canvas id="chart2"></canvas>
    </div>
</div>
</div>
</div>
</div>
</div>


<script>
    // Get the context of the canvas element we want to select
var ctx = document.getElementById("chart").getContext("2d");
var ctx2 = document.getElementById("chart2").getContext("2d");

var img = new Image();
img.style.background = '#f00';
img.src = 'http://media.tumblr.com/tumblr_lg781aiOXe1qb7k74.jpg';

var img2 = new Image();
img2.style.background = '#f00';
img2.src = 'http://www.pdbuchan.com/aquabutton/step4.jpg';

img.onload = function() {
  var data = [
      {
          value: 300,
          color: ctx.createPattern(img, 'repeat'),
          //highlight: "#FF5A5E",
          label: "Red"
      },
      {
          value: 100,
          color: ctx.createPattern(img2, 'repeat'),
          // highlight: "#FFC870",
          label: "False Classified"
      }
  ];

    var myNewChart = new Chart(ctx).Pie(data);
};

img2.onload = function() {
  var data = [
      {
          value: 300,
          color: ctx2.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "Red"
      },
      {
          value: 200,
          color: "#FDB45C",
          highlight: "#FFC870",
          label: "Yellow"
      }
  ];

    var myNewChart1 = new Chart(ctx2).Pie(data);
};
</script>

@endsection