
@extends('layout.skripsi')
@section('head')
<script src="chart/chart.js"></script> 
@endsection
@section('judul')
	<b>EVALUASI 2 - PENGARUH SELEKSI FITUR</b>
@endsection

@section('content')
<b>Deskripsi</b>
<br><p>
Evaluasi dilakukan dengan melakukan klasifikasi menggunakan dua metode klasifikasi NBC yakni Multinomial dan Bernoulli. 
Data training yang digunakan adalah 200 data training untuk kelas spam dan kelas valid, nilai critical value yang digunakan untuk seleksi fitur adalah 10.83. 
Pengujian untuk masing-masing metode klasifikasi akan dilakukan dua kali, yakni menggunakan fitur hasil praproses dan fitur hasil seleksi. 
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
		<center><b>FITUR PRAPROSES</b></center></br>
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
	<div class="col-lg-2">
	</div>
	<div class="col-lg-4">
	<center>
		<center><b>FITUR SELEKSI</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart2" width="200" height="200"/>
		</div>
	</center>
	

	

	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><td><?php echo 100*$akurasi_m_s;?> %</td></tr>
	<tr><Td>Precision</td><Td>{{$precision_m_s*100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_m_s*100,2)}} %</td></tr>
	<tr><Td>F1</td><td>{{round($f_measure_m_s*100,2)}} %</Td></tr>
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
		<center><b>FITUR PRAPROSES</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart3" width="200" height="200"/>
		</div>
	</center>
	

	
	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><Td><?php echo 100*$akurasi_b;?> %</td></tr>
	<tr><Td>Precision</td><td>{{$precision_b*100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_b*100,2)}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure_b*100,2)}} %</td></tr>
	</table>
	</div>
	<div class="col-lg-2">
	</div>
	<div class="col-lg-4">
	<center>
		<center><b>FITUR SELEKSI</b></center></br>
		<div id="canvas-holder">
			<canvas id="chart4" width="200" height="200"/>
		</div>
	</center>
	

	

	<td colspan="3"><center><b>Performa</b></center></td>
	<table class="table table-bordered table-hover table-striped col-lg-4">
	<tr><Td>Akurasi</td><td><?php echo 100*$akurasi_b_s;?> %</td></tr>
	<tr><Td>Precision</td><Td>{{$precision_b_s*100}} %</td></tr>
	<tr><Td>Recall</td><td>{{round($recall_b_s*100,2)}} %</td></tr>
	<tr><Td>F1</td><td>{{round($f_measure_b_s*100,2)}} %</Td></tr>
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
var ctx3 = document.getElementById("chart3").getContext("2d");
var ctx4 = document.getElementById("chart4").getContext("2d");

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
          value: <?php echo 100*$akurasi_m_s;?>,
          color: ctx2.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_m_s = (1 - $akurasi_m_s) * 100; echo $false_m_s;?>,
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
          value: <?php echo 100*$akurasi_b;?>,
          color: ctx3.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_b = (1 - $akurasi_b) * 100; echo $false_b;?>,
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
          value: <?php echo 100*$akurasi_b_s;?>,
          color: ctx4.createPattern(img2, 'repeat'),
          //highlight: "#FF5A5E",
          label: "True Classified"
      },
      {
          value: <?php $false_b_s = (1 - $akurasi_b_s) * 100; echo $false_b_s;?>,
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