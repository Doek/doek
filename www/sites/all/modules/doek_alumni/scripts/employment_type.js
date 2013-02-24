$(function () {
        //Design af IT.
	var d1 = [[2007,11], [2008, 16], [2009, 15], [2010, 15], [2011, 15]];
        //Drift af IT
	var d2 = [[2007, 5], [2008, 3], [2009, 3], [2010, 3], [2011, 1]];
        //HR.
	var d3 = [[2007, 2], [2008, 1], [2009, 1], [2010, 1],  [2011, 1]];
        //IT Support
	var d4 = [[2007, 1], [2008, 0], [2009, 2], [2010, 2],  [2011, 2]];
        //Projektledelse
	var d5 = [[2007, 27], [2008, 30], [2009, 25], [2010, 25],  [2011, 25]];
        //Ledelse
	var d6 = [[2007, 12], [2008, 13], [2009, 16], [2010, 16],  [2011, 16]];
        //Rådgivning
	var d7 = [[2007, 14], [2008, 9], [2009, 8], [2010, 8],  [2011, 8]];
        //Salg
	var d8 = [[2007, 6], [2008, 5], [2009, 4], [2010, 4],  [2011, 3]];
        //Udvikling
	var d9 = [[2007, 14], [2008, 12], [2009, 10], [2010, 10],  [2011, 10]];
        //Undervisning
	var d10 = [[2007, 2], [2008, 1], [2009, 1], [2010, 1],  [2011, 1]];
        //Økonomi
	var d11 = [[2007, 3], [2008, 4], [2009, 8], [2010, 8],  [2011, 5]];
        //Andet
	var d12 = [[2007, 3], [2008, 5], [2009, 6], [2010, 6],  [2011, 4]];

	var plot = $.plot($("#placeholder3"), 
		[ 
			{data: d1, label: "Design af IT"},
			{data: d2, label: "Drift af IT"},
			{data: d3, label: "HR"},
			{data: d4, label: "IT Support"},
			{data: d5, label: "Projektledelse"},
			{data: d6, label: "Ledelse"},
			{data: d7, label: "Rådgivning"},
			{data: d8, label: "Salg og Marketing"},
			{data: d9, label: "Udvikling"},
			{data: d10, label: "Undervisning"},
			{data: d11, label: "Økonomi og finans"},
			{data: d12, label: "Andet"},

		],
		{
			series: {
				lines: {show: true},
				points: {show: true},
			},
			grid: { hoverable: true, clickable: true, borderWidth: 0 },
			xaxis: {ticks: [2007, 2008, 2009, 2010, 2011], min: 2007, max: 2011, tickDecimals: 0},
			yaxes: [{position: "left", min: 0, max: 35, ticks: 20, tickDecimals: 0, tickFormatter: procentFormatter,  show: true, }],
			legend: { 
				show: true,
				noColumns: 3,
				backgroundOpacity: 0,
				container: "#placeholder3_label",
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

	$("#placeholder3").bind("plothover", function (event, pos, item) {
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
