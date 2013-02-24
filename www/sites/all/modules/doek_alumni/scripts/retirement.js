$(function () {
        //Løn uden pension.
	var d1 = [[2007,7.63], [2008, 7.74], [2009, 8.40], [2010, 8.44], [2011, 8.40]];

	var plot = $.plot($("#placeholder2"), 
		[ {data: d1, label: "Pensionsprocent"} ],
		{
			series: {
				lines: {show: true},
				points: {show: true}
			},
			grid: { hoverable: true, clickable: true, borderWidth: 0 },
			xaxis: {ticks: [2007, 2008, 2009, 2010, 2011], min: 2007, max: 2011, tickDecimals: 0},
			yaxes: 	[ 
					{min: 0, max: 9, ticks: 20, tickDecimals: 1, tickFormatter: procentFormatter, show: true }
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

	$("#placeholder2").bind("plothover", function (event, pos, item) {
		$("#x").text(pos.x.toFixed(0));
		$("#y").text(pos.y.toFixed(0));
		    if (item) {
			if (previousPoint != item.dataIndex) {
			    previousPoint = item.dataIndex;
			    
			    $("#tooltip").remove();
			    var x = item.datapoint[0].toFixed(0),
				y = item.datapoint[1].toFixed(0);
			    
			    showTooltip(item.pageX, item.pageY,
				        x + ", " + y + "%");
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
