function landingAnimate() {
	var barColors = ['#357193', '#31c573', '#000000', '#f16528'];
	var options = {
		animate: 2000,
		scaleColor: false,
		lineWidth: 24,
		lineCap: 'square',
		size: 200,
		trackColor: '#FFF'
	};


	$('.chart').each(function(index) {
		options.barColor = barColors[index];
		$(this).easyPieChart(options);
	});

}
