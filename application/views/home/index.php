<!-- head -->
<?php
$h1 = "Ranger Systems Integrated Operation Portal";
$this->load->view('common/head');
?>
<body>
<!-- header -->
<header>
	<p>Ranger Systems co., ltd.</p>
	<?php
	$this->load->view('common/header');
	?>
	<h1><?=$h1?></h1>
</header>
<!-- body -->
<div class="container">
	<div class="grid1">
		<div id="container1" style="width: 680px; height: 330px; margin: 0"></div>
	</div>
	<div class="grid3">
		<div id="container3" style="width: 330px; height: 330px; margin: 0"></div>
	</div>
	<div class="grid4">
		<div id="container2" style="width: 330px; height: 330px; margin: 0"></div>
	</div>
	<div class="grid5">
		<div id="container4" style="width: 680px; height: 330px; margin: 0"></div>
	</div>
	<div class="grid2">
		<ul>
			<li>お知らせ : 半黒開通時の注意事項<br>日付検索時のエラーについては、BPA運用チームにご一報ください。</li>
			<li>お知らせ : SIM稼働率については現在要求事項をまとめている段階です。</li>
			<li>お知らせ : 各お客様への自動レポート機能をリリースしました。</li>
		</ul>
	</div>
</div>

<!-- footer -->
<?php $this->load->view('common/footer'); ?>
<script>
  Highcharts.chart('container1', {
	chart: {
	  type: 'spline',
	  scrollablePlotArea: {
		minWidth: 600,
		scrollPositionX: 1
	  }
	},
	title: {
	  text: 'IoT Sensor Error Response'
	},
	subtitle: {
	  text: ''
	},
	xAxis: {
	  type: 'datetime',
	  labels: {
		overflow: 'justify'
	  }
	},
	yAxis: {
	  title: {
		text: 'number #'
	  },
	  minorGridLineWidth: 0,
	  gridLineWidth: 0,
	  alternateGridColor: null,
	  plotBands: [{ // Light air
		from: 0.3,
		to: 1.5,
		color: 'rgba(68, 170, 213, 0.1)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }, { // Light breeze
		from: 1.5,
		to: 3.3,
		color: 'rgba(0, 0, 0, 0)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }, { // Gentle breeze
		from: 3.3,
		to: 5.5,
		color: 'rgba(68, 170, 213, 0.1)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }, { // Moderate breeze
		from: 5.5,
		to: 8,
		color: 'rgba(0, 0, 0, 0)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }, { // Fresh breeze
		from: 8,
		to: 11,
		color: 'rgba(68, 170, 213, 0.1)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }, { // Strong breeze
		from: 11,
		to: 14,
		color: 'rgba(0, 0, 0, 0)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }, { // High wind
		from: 14,
		to: 15,
		color: 'rgba(68, 170, 213, 0.1)',
		label: {
		  text: '',
		  style: {
			color: '#606060'
		  }
		}
	  }]
	},
	tooltip: {
	  valueSuffix: ' k'
	},
	plotOptions: {
	  spline: {
		lineWidth: 4,
		states: {
		  hover: {
			lineWidth: 5
		  }
		},
		marker: {
		  enabled: false
		},
		pointInterval: 86400000, // one hour
		pointStart: Date.UTC(2018, 6, 13, 0, 0, 0)
	  }
	},
	series: [{
	  name: 'Sensor mattress',
	  data: [
		3.7, 3.3, 3.9, 5.1, 3.5, 3.8, 4.0, 5.0, 6.1, 3.7, 3.3, 6.4,
		6.9, 6.0, 6.8, 4.4, 4.0, 3.8, 5.0, 4.9, 9.2, 9.6, 9.5, 6.3,
		9.5, 10.8, 14.0, 11.5, 10.0, 10.2, 10.3, 9.4, 8.9, 10.6, 10.5, 11.1,
		10.4, 10.7, 11.3, 10.2, 9.6, 10.2, 11.1, 10.8, 13.0, 12.5, 12.5, 11.3,
		10.1
	  ]

	}, {
	  name: 'Toilet Serching',
	  data: [
		0.2, 0.1, 0.1, 0.1, 0.3, 0.2, 0.3, 0.1, 0.7, 0.3, 0.2, 0.2,
		0.3, 0.1, 0.3, 0.4, 0.3, 0.2, 0.3, 0.2, 0.4, 0.0, 0.9, 0.3,
		0.7, 1.1, 1.8, 1.2, 1.4, 1.2, 0.9, 0.8, 0.9, 0.2, 0.4, 1.2,
		0.3, 2.3, 1.0, 0.7, 1.0, 0.8, 2.0, 1.2, 1.4, 3.7, 2.1, 2.0,
		1.5
	  ]
	}],
	navigation: {
	  menuItemStyle: {
		fontSize: '10px'
	  }
	}
  });
</script>
<script>
  Highcharts.chart('container2', {

	chart: {
	  type: 'bubble',
	  plotBorderWidth: 1,
	  zoomType: 'xy'
	},

	legend: {
	  enabled: false
	},

	title: {
	  text: 'IoT Sensor value'
	},

	subtitle: {
	  text: ''
	},

	xAxis: {
	  gridLineWidth: 1,
	  title: {
		text: 'Cost'
	  },
	  labels: {
		format: '{value} k'
	  },
	  plotLines: [{
		color: 'black',
		dashStyle: 'dot',
		width: 2,
		value: 65,
		label: {
		  rotation: 0,
		  y: 15,
		  style: {
			fontStyle: 'italic'
		  },
		  text: ''
		},
		zIndex: 3
	  }]
	},

	yAxis: {
	  startOnTick: false,
	  endOnTick: false,
	  title: {
		text: 'Business values'
	  },
	  labels: {
		format: '{value} k'
	  },
	  maxPadding: 0.2,
	  plotLines: [{
		color: 'black',
		dashStyle: 'dot',
		width: 2,
		value: 50,
		label: {
		  align: 'right',
		  style: {
			fontStyle: 'italic'
		  },
		  text: '',
		  x: -10
		},
		zIndex: 3
	  }]
	},

	tooltip: {
	  useHTML: true,
	  headerFormat: '<table>',
	  pointFormat: '<tr><th colspan="2"><h3>{point.country}</h3></th></tr>' +
	  '<tr><th>Fat intake:</th><td>{point.x}g</td></tr>' +
	  '<tr><th>Sugar intake:</th><td>{point.y}g</td></tr>' +
	  '<tr><th>Obesity (adults):</th><td>{point.z}%</td></tr>',
	  footerFormat: '</table>',
	  followPointer: true
	},

	plotOptions: {
	  series: {
		dataLabels: {
		  enabled: true,
		  format: '{point.name}'
		}
	  }
	},

	series: [{
	  data: [
		{ x: 95, y: 95, z: 13.8, name: 'BE', country: 'Belgium' },
		{ x: 86.5, y: 102.9, z: 14.7, name: 'DE', country: 'Germany' },
		{ x: 80.8, y: 91.5, z: 15.8, name: 'FI', country: 'Finland' },
		{ x: 80.4, y: 102.5, z: 12, name: 'NL', country: 'Netherlands' },
		{ x: 80.3, y: 86.1, z: 11.8, name: 'SE', country: 'Sweden' },
		{ x: 78.4, y: 70.1, z: 16.6, name: 'ES', country: 'Spain' },
		{ x: 74.2, y: 68.5, z: 14.5, name: 'FR', country: 'France' },
		{ x: 73.5, y: 83.1, z: 10, name: 'NO', country: 'Norway' },
		{ x: 71, y: 93.2, z: 24.7, name: 'JP', country: 'Japan' },
		{ x: 69.2, y: 57.6, z: 10.4, name: 'IT', country: 'Italy' },
		{ x: 68.6, y: 20, z: 16, name: 'RU', country: 'Russia' },
		{ x: 65.5, y: 126.4, z: 35.3, name: 'US', country: 'United States' },
		{ x: 65.4, y: 50.8, z: 28.5, name: 'HU', country: 'Hungary' },
		{ x: 63.4, y: 51.8, z: 15.4, name: 'PT', country: 'Portugal' },
		{ x: 64, y: 82.9, z: 31.3, name: 'CH', country: 'China' }
	  ]
	}]

  });
</script>
<script>
  Highcharts.chart('container3', {
	title: {
	  text: 'Combination chart'
	},
	xAxis: {
	  categories: ['mvno01', 'mvno02', 'mvno03', 'mvno04', 'mvno05', 'mvno06']
	},
	labels: {
	  items: [{
		html: 'SIM Shape',
		style: {
		  left: '50px',
		  top: '18px',
		  color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
		}
	  }]
	},
	series: [{
	  type: 'column',
	  name: 'mini',
	  data: [300, 200, 100, 300, 400]
	}, {
	  type: 'column',
	  name: 'micro',
	  data: [200, 300, 500, 700, 600]
	}, {
	  type: 'column',
	  name: 'nano',
	  data: [400, 300, 300, 900, 0]
	}, {
	  type: 'spline',
	  name: 'Average',
	  data: [300, 267, 300, 633, 333],
	  marker: {
		lineWidth: 2,
		lineColor: Highcharts.getOptions().colors[3],
		fillColor: 'white'
	  }
	}, {
	  type: 'pie',
	  name: 'Total consumption',
	  data: [{
		name: 'mini',
		y: 13,
		color: Highcharts.getOptions().colors[0] // Jane's color
	  }, {
		name: 'micro',
		y: 23,
		color: Highcharts.getOptions().colors[1] // John's color
	  }, {
		name: 'nano',
		y: 19,
		color: Highcharts.getOptions().colors[2] // Joe's color
	  }],
	  center: [50, 40],
	  size: 50,
	  showInLegend: false,
	  dataLabels: {
		enabled: false
	  }
	}]
  });
</script>
<script>
  Highcharts.chart('container4', {

	title: {
	  text: 'EPC data traffic Diagram'
	},

	series: [{
	  keys: ['from', 'to', 'weight'],
	  data: [
		['256k/256k', 'PodA', 5 ],
		['256k/256k', 'PodB', 1 ],
		['256k/256k', 'PodC', 1 ],
		['32k/32k', 'PodZ', 1 ],
		['32k/32k', 'PodA', 1 ],
		['32k/32k', 'PodB', 5 ],
		['32k/32k', 'PodZ', 1 ],
		['512k/512k', 'PodA', 1 ],
		['512k/512k', 'PodB', 1 ],
		['512k/512k', 'PodC', 5 ],
		['512k/512k', 'PodZ', 1 ],
		['1M/1M', 'PodA', 1 ],
		['1M/1M', 'PodB', 1 ],
		['1M/1M', 'PodC', 1 ],
		['1M/1M', 'PodZ', 5 ],
		['PodA', 'PCRF', 2 ],
		['PodA', 'PCEF', 1 ],
		['PodA', 'PGW', 1 ],
		['PodA', 'PGW', 3 ],
		['PodB', 'PCRF', 1 ],
		['PodB', 'PCEF', 3 ],
		['PodB', 'Radius', 3 ],
		['PodB', 'PGW', 3 ],
		['PodB', 'PGW', 1 ],
		['PodC', 'PCEF', 1 ],
		['PodC', 'PGW', 3 ],
		['PodC', 'PGW', 1 ],
		['PodZ', 'PCRF', 1 ],
		['PodZ', 'PCEF', 1 ],
		['PodZ', 'PGW', 2 ],
		['PodZ', 'PGW', 7 ],
		['PGW', 'Body', 5 ],
		['PGW', 'header', 1 ],
		['PGW', 'Other', 3 ],
		['PCRF', 'Body', 5 ],
		['PCRF', 'header', 1 ],
		['PCRF', 'Other', 3 ],
		['PCEF', 'Body', 5 ],
		['PCEF', 'header', 1 ],
		['PCEF', 'Other', 3 ],
		['Radius', 'Body', 5 ],
		['Radius', 'header', 1 ],
		['Radius', 'Other', 3 ],
		['PGW', 'Body', 5 ],
		['PGW', 'header', 1 ],
		['PGW', 'Other', 3 ]
	  ],
	  type: 'sankey',
	  name: 'Sankey demo series'
	}]

  });
</script>
</body>
</html>