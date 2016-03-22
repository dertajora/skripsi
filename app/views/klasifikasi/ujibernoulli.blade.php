@extends('layout.datatable')
@section('head')
<script src="chart/chart.js"></script> 
<script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">

    <script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
    } );
    </script>
@endsection
@section('judul')
	<b>HASIL UJI KLASIFIKASI BERNOULLI</b>
@endsection

@section('content')
<div class="row">
	<div class="col-lg-4">
	<center><b>{{$data}}</b><br><br>
		<b>Akurasi</b>
	
		<div id="canvas-holder">
			<canvas id="chart-area" width="200" height="200"/>
		</div>
	</center>
	

	
	
	<table class="table table-bordered table-hover table-striped col-lg-4">
		<tr>
		<td colspan="3"><center><b>Matriks Confusion</b></center></td>
		
		</tr>	
		<tr>
		<td></td>
		<td>Correct</td>
		<td>Not Correct</td>
		</tr>	
		<tr>
		<td>Selected</td>
		<td>{{$true_positives}}</td>
		<td>{{$false_positives}}</td>
		</tr>	
		<tr>
		<td>Not Selected</td>
		<td>{{$false_negatives}}</td>
		<td>{{$true_negatives}}</td>
		</tr>	

	</table>
	
	<table class="table table-bordered table-hover table-striped col-lg-4">
		<tr>
		<td colspan="3"><center><b>Performa </b></center></td>
		
		</tr>	
	<tr><Td>Akurasi</td><Td>{{round($akurasi*100,2)}} %</td></tr>
	<tr><Td>Precision</td><Td>{{$precision*100}} %</td></tr>
	<tr><Td>Recall</td><Td>{{$recall*100}} %</td></tr>
	<tr><Td>F1</td><Td>{{round($f_measure*100,2)}} %</td></tr>
	</table>
	</div>
</div>


	<br>





<center><b>LAMPIRAN HASIL KLASIFIKASI</b></center>
<div class="table-responsive">

<table id="example" class="display" cellspacing="0" width="100%">
 		<thead>
 		    <tr>
 		        <th>No</th>
 		        <th>Tweet</th>
 		        <th>Kelas Asli</th>
 		        <th>Kelas Sistem</th>
 		        <th>Probabilitas Valid</th>
 		        <th>Probabilitas Spam</th>
 		    </tr>
 		</thead>
 		<tfoot>
            <tr>
                <th>No</th>
 		        <th>Tweet</th>
 		        <th>Kelas Asli</th>
 		        <th>Kelas Sistem</th>
 		        <th>Probabilitas Valid</th>
 		        <th>Probabilitas Spam</th>
            </tr>
        </tfoot>
 		<tbody>
 			<?php 
			$jumlah_tweet = count($hasil_klasifikasi);
			for ($j=0; $j < $jumlah_tweet; $j++) { 
			 	
			  ?>
 		    <tr>
 		        <td>{{$j+1}}</td>
 		        <td>{{$hasil_klasifikasi[$j]['tweet']}}</td>
 		        <td>{{$hasil_klasifikasi[$j]['kelas_asli']}}</td>
 		        <td>{{$hasil_klasifikasi[$j]['kelas_sistem']}}</td>
 		        <td>{{$hasil_klasifikasi[$j]['prob_valid']}}</td>
 		        <td>{{$hasil_klasifikasi[$j]['prob_spam']}}</td>
 		        
 		        
 		     
 		    </tr>
 			<?php } ?>
                                       
 		</tbody>
 </table>


</div>

<script>

		var pieData = [
				{
					value: <?php echo $true_positives+$true_negatives;?>,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "True Classified"
				},
				// {
				// 	value: 50,
				// 	color: "#46BFBD",
				// 	highlight: "#5AD3D1",
				// 	label: "Green"
				// },
				{
					value: <?php echo $false_negatives+$false_positives;?>,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "False Classified"
				},
				// {
				// 	value: 40,
				// 	color: "#949FB1",
				// 	highlight: "#A8B3C5",
				// 	label: "Grey"
				// }

			];

			

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
				myLineChart.generateLegend();
				
			};

</script>

@endsection