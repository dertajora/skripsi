

@extends('layout.skripsi')
@section('head')
<script src="chart/chart.js"></script> 
@endsection
@section('judul')
	<b>EVALUASI 3 - UJI DATA TRAINING </b>
@endsection

@section('content')
<b>Deskripsi</b>
<br><p>

Pada pengujian ini akan digunakan jumlah data training yang berbeda, yakni 400 data tweet (200 spam dan 200 valid) dan 600 tweet (300 spam dan 300 valid).   
Evaluasi dilakukan dengan cara mengukur performa hasil klasifikasi menggunakan dua metode klasifikasi NBC yakni Multinomial dan Bernoulli dengan menggunakan fitur hasil training dari kedua data training tersebut.  
</p>
<div class="row">
<div class="col-lg-10">
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">KLASIFIKASI MODEL MULTINOMIAL</h3>
</div>
<div class="panel-body">
<div class="row">
	<div class="col-lg-4">
	<center>
		<center><b>DATA TRAINING 1</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart-area1" width="200" height="200"/>
		</div>
	</center>
	

	
	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><Td><?php echo 100*$akurasi_m_1;?> %</td></tr>
	<tr><Td>Precision</td><td>{{$precision_m_1 * 100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_m_1 * 100,2)}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure_m_1 * 100,2)}} %</td></tr>
	</table>
	</div>
	<div class="col-lg-2">
	</div>
	<div class="col-lg-4">
	<center>
		<center><b>DATA TRAINING 2</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart-area2" width="200" height="200"/>
		</div>
	</center>
	

	

	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><Td><?php echo 100*$akurasi_m_2;?> %</td></tr>
	<tr><Td>Precision</td><td>{{$precision_m_2 * 100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_m_2 * 100,2)}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure_m_2 * 100,2)}} %</td></tr>
	</table>
	</div>

</div>
</div>
</div>




<div class="row">
<div class="col-lg-12">
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">KLASIFIKASI MODEL BERNOULLI</h3>
</div>
<div class="panel-body">
<div class="row">
	<div class="col-lg-4">
	<center>
		<center><b>DATA TRAINING 1</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart-area3" width="200" height="200"/>
		</div>
	</center>
	

	
	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><Td><?php echo 100*$akurasi_b_1;?> %</td></tr>
	<tr><Td>Precision</td><td>{{$precision_b_1 * 100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_b_1 * 100,2)}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure_b_1 * 100,2)}} %</td></tr>
	</table>
	</div>
	<div class="col-lg-2">
	</div>
	<div class="col-lg-4">
	<center>
		<center><b>DATA TRAINING 2</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart-area4" width="200" height="200"/>
		</div>
	</center>
	

	

	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><Td><?php echo 100*$akurasi_b_2;?> %</td></tr>
	<tr><Td>Precision</td><td>{{$precision_b_2 * 100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_b_2 * 100,2)}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure_b_2 * 100,2)}} %</td></tr>
	</table>
	</div>

</div>
</div>
</div>
</div>
</div>



<script>
    // Get the context of the canvas element we want to select
var ctx = document.getElementById("chart-area1").getContext("2d");
var ctx2 = document.getElementById("chart-area2").getContext("2d");
var ctx3 = document.getElementById("chart-area3").getContext("2d");
var ctx4 = document.getElementById("chart-area4").getContext("2d");

var img = new Image();
img.style.background = '#f00';
img.src = 'images/horizontal.png';

var img2 = new Image();
img2.style.background = '#f00';
img2.src = 'images/tumblr.jpg';

var img3 = new Image();
img3.style.background = '#f00';
img3.src = 'images/tumblr.jpg';

var img4 = new Image();
img4.style.background = '#f00';
img4.src = 'images/tumblr.jpg';

img.onload = function() {
  var data = [
      {
          value: <?php echo 100*$akurasi_m_1;?>,
          color: ctx.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_m = (1 - $akurasi_m_1) * 100; echo $false_m;?>,
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
          value: <?php echo 100*$akurasi_m_2;?>,
          color: ctx2.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_m_s = (1 - $akurasi_m_2) * 100; echo $false_m_s;?>,
          color: ctx2.createPattern(img, 'repeat'),
          // color: "#FDB45C",
          // highlight: "#FFC870",
          label: "False Classified"
      }
  ];

    var pieMultiS = new Chart(ctx2).Pie(data);
};

img3.onload = function() {
  var data = [
      {
          value: <?php echo 100*$akurasi_b_1;?>,
          color: ctx3.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_b = (1 - $akurasi_b_1) * 100; echo $false_b;?>,
          color: ctx3.createPattern(img, 'repeat'),
          // color: "#FDB45C",
          // highlight: "#FFC870",
          label: "False Classified"
      }
  ];

    var pieBernoulli = new Chart(ctx3).Pie(data);
};

img4.onload = function() {
  var data = [
      {
          value: <?php echo 100*$akurasi_b_2;?>,
          color: ctx4.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_b_s = (1 - $akurasi_b_2) * 100; echo $false_b_s;?>,
          color: ctx4.createPattern(img, 'repeat'),
          // color: "#FDB45C",
          // highlight: "#FFC870",
          label: "False Classified"
      }
  ];

    var pieBernoulliS = new Chart(ctx4).Pie(data);
};
</script>
@endsection