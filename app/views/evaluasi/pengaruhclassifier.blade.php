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
			<canvas id="chart" width="200" height="200"/>
		</div>
	</center>
	

	
	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><Td><?php echo 100*$akurasi_m;?> %</td></tr>
	<tr><Td>Precision</td><td>{{$precision_m*100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_m*100,2)}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure_m*100,2)}} %</td></tr>
	</table>
	</div>
	<div class="col-lg-2"></div>
	<div class="col-lg-4">
	<center>
		<center><b>Klasifikasi Model Bernoulli</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart2" width="200" height="200"/>
		</div>
	</center>
	

	

	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><td><?php echo 100*$akurasi_b;?> %</td></tr>
	<tr><Td>Precision</td><Td>{{$precision_b*100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_b*100,2)}} %</td></tr>
	<tr><Td>F1</td><td>{{round($f_measure_b*100,2)}} %</Td></tr>
	</table>
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
img.src = 'images/horizontal.png';

var img2 = new Image();
img2.style.background = '#f00';
img2.src = 'images/tumblr.jpg';

img.onload = function() {
  var data = [
      {
          value: <?php echo 100*$akurasi_m;?>,
          color: ctx.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_m = (1 - $akurasi_m) * 100; echo $false_m;?>,
          color: ctx.createPattern(img, 'repeat'),
          // highlight: "#FFC870",
          label: "False Classified"
      }
  ];

    var pieMulti = new Chart(ctx).Pie(data);
};

img2.onload = function() {
  var data = [
      {
          value: <?php echo 100*$akurasi_b;?>,
          color: ctx2.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_b = (1 - $akurasi_b) * 100; echo $false_b;?>,
          color: ctx2.createPattern(img, 'repeat'),
          // color: "#FDB45C",
          // highlight: "#FFC870",
          label: "False Classified"
      }
  ];

    var pieBernou = new Chart(ctx2).Pie(data);
};
</script>

@endsection