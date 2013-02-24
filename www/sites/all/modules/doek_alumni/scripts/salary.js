$(function () {
        //Løn uden pension.
	var d1 = [[2007,41737], [2008, 45990], [2009, 47144], [2010, 50898], [2011, 53880]];
        //Løn inkl pension.
	var d2 = [[2007, 44922], [2008, 49550], [2009, 51102], [2010, 55193], [2011, 58354]];
        //Stigning i procent.
	var d3 = [[2008,10.19],[2009, 2.51], [2010, 7.96],  [2011, 5.86]];
        //Inflation.
	var d4 = [[2008,1.70],[2009, 3.40], [2010, 1.30],  [2011, 2.30]];    

	var plot = $.plot($("#placeholder"), 
		[ {data: d1, label: "Løn ex. pen", yaxis: 1}, {data: d2, label: "Løn incl. pen", yaxis: 1}, {data: d3, label: "Stigning i %", yaxis: 2}, {data: d4, label: "Inflation i %", yaxis: 2} ],
		{
			series: {
				lines: {show: true},
				points: {show: true}
			},
			grid: { hoverable: true, clickable: true, borderWidth: 0 },
			xaxis: {ticks: [2007, 2008, 2009, 2010, 2011], min: 2007, max: 2011, tickDecimals: 0, label: "Årstal"},
			yaxes: [ 
				{position: "left", min: 0, max: 60000, ticks: 20, tickDecimals: 0,  show: true}, 
				{position: "right", min: 0, max: 30, tickDecimals: 0, tickFormatter: procentFormatter, show: true, alignTicksWithAxis: 1 }
				],
			legend: { 
				position: 'nw',
				show: true,
				backgroundOpacity: 0,
				}
		}
	);

	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
		    position: 'absolute',
		    display: 'none',
		    top: y + 5,
		    left: x + 5,
		    border: '1px solid #fdd',
		    padding: '2px',
		    'background-color': '#fee',
		    opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;

	$("#placeholder").bind("plothover", function (event, pos, item) {
		$("#x").text(pos.x.toFixed(2));
		$("#y").text(pos.y.toFixed(2));
		    if (item) {
			if (previousPoint != item.dataIndex) {
			    previousPoint = item.dataIndex;
			    
			    $("#tooltip").remove();
			    var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2);
			    
			    showTooltip(item.pageX, item.pageY,
				        x + ", " + y);
			}
		    }
		    else {
			$("#tooltip").remove();
			previousPoint = null;            
		    }
	});

	function procentFormatter(v, axis) {
		return v.toFixed(axis.tickDecimals) +"%";
	}

});
