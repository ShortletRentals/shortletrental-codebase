<div class="dashboard-chart2" id="dashboard-chart3"></div>
<script>
	var last_12_months_list = <?php echo $last_12_months_list; ?>;
	var last_12_months_amount = <?php echo $last_12_months_amount; ?>;

	"use strict";
	// chart 2
	var optionsLine = {
		chart: {
			foreColor: '#000',
			height: 420,
			type: 'line',
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: true,
				top: 4,
				left: 2,
				blur: 4,
				opacity: 0.1,
			}
		},
		stroke: {
			curve: 'smooth',
			width: 3
		},
		colors: ["#000", '#000', '#000'],
		series: [{
			name: "Bookings",
			data: last_12_months_amount
		}/*, {
			name: "Photos",
			data: [3, 33, 21, 42, 19, 32]
		}, {
			name: "Files",
			data: [0, 39, 52, 11, 29, 43]
		}*/],
		title: {
			text: '',
			align: 'left',
			offsetY: 25,
			offsetX: 20
		},
		subtitle: {
			// text: 'Statistics',
			offsetY: 55,
			offsetX: 20
		},
		markers: {
			size: 4,
			strokeWidth: 0,
			hover: {
				size: 7
			}
		},
		grid: {
			show: true,
			borderColor: '#000',
			strokeDashArray: 4,
		},
		tooltip: {
			theme: 'dark',
		},
		labels: last_12_months_list,
		xaxis: {
			tooltip: {
				enabled: false
			}
		},
		legend: {
			position: 'top',
			horizontalAlign: 'right',
			offsetY: -20
		}
	}

	///console.log(optionsLine);
	var chartLine = new ApexCharts(document.querySelector('#dashboard-chart3'), optionsLine);
	chartLine.render();
</script>