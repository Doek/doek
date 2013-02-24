$(function () {
        //Deltid
	var d1 = [[2007,1], [2008, 1], [2009, 1], [2010, 1], [2011, 2]];
        //Fuldtid
	var d2 = [[2007, 87], [2008, 88], [2009, 83], [2010, 83], [2011, 89]];
        //Ledig
	var d3 = [[2007, 1], [2008, 1], [2009, 3], [2010, 3], [2011, 1]];
        //Selvstændig
	var d4 = [[2007, 7], [2008, 6], [2009, 6], [2010, 6], [2011, 8]];

	var plot = $.plot($("#placeholder4"), 
		[ {data: d1, label: "Deltidsansat"}, {data: d2, label: "Fuldtidsansat"}, {data: d3, label: "Ledig"}, {data: d4, label: "Selvstændig"} ],
		{
			series: {
				lines: {show: true},
				points: {show: true}
			},
			grid: { hoverable: true, clickable: true, borderWidth: 0 },
			xaxis: {ticks: [2007, 2008, 2009, 2010, 2011], min: 2007, max: 2011, tickDecimals: 0},
			yaxes: [ {min: 0, max: 100, ticks: 20, tickDecimals: 0, tickFormatter: procentFormatter,  show: true}], 
			legend: { 
				show: true,
				noColumns: 2,
				backgroundOpacity: 0,
				container: "#placeholder4_label",
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

	$("#placeholder4").bind("plothover", function (event, pos, item) {
		$("#x").text(pos.x.toFixed(2));
		$("#y").text(pos.y.toFixed(2));
		    if (item) {
			if (previousPoint != item.dataIndex) {
			    previousPoint = item.dataIndex;
			    
			    $("#tooltip").remove();
			    var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2);
			    
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
