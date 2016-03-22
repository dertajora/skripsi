
<!doctype html>
<html>
	<head>
		<title>Pie Chart</title>

		<script src="chart/chart.js"></script>
	</head>
	<body>
		<table>
			<tr><td><div id="canvas-holder">
			<canvas id="chart-area" width="300" height="300"/>
		</div></td><td><div id="canvas-holder">
			<canvas id="chart-area1" width="300" height="300"/>
		</div></td></tr>
		</table>
		


	<script>

		var pieData = [
				{
					value: 300,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Red"
				},
				{
					value: 50,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Green"
				},
				{
					value: 100,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Yellow"
				},
				{
					value: 40,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Grey"
				}

			];

			

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
				var ctx = document.getElementById("chart-area1").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
			};



	</script>
	</body>
</html>
