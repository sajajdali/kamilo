$(document).ready(function() {



    // Ordered Bars

    var do2 = [];
    var do3 = [];

    for (var i = 1; i < 11; i++) {
        do2.push([i, parseInt(Math.random() * 100)]);
        do3.push([i, parseInt(Math.random() * 100)]);
    }


    var dos = new Array();

    dos.push({
        data:do3,
        label: "Label 3",
        bars: {
            show: true,
            barWidth: 0.15,
            order: 3,
        }
    });
    dos.push({
        data:do2,
        label: "Label 2",
        bars: {
            show: true,
            barWidth: 0.15,
            order: 2,
        }
    });
    

    var variance = $.plot($("#ordered"), dos, {
        series: {
            bars: {
                show: true,
                lineWidth: 1
            }
        },
        grid: {
            labelMargin: 10,
            hoverable: true,
            clickable: true,
            tickColor: 'rgba(0, 0, 0, 0.06)',
            borderWidth: 1,
            borderColor: 'rgba(0, 0, 0, 0.06)'
        },
        colors: [Utility.getBrandColor('info'), Utility.getBrandColor('inverse'), Utility.getBrandColor('midnightblue')],
        tooltip: true,
        tooltipOpts: {
            content: "x: %x, y: %y"
        },
        xaxis: {
            autoscaleMargin: 0.05,
            tickColor: 'rgba(0, 0, 0, 0.06)',
            //ticks: [[1, "Q1"], [2, "Q2"], [3, "Q3"], [4, "Q4"]],
            tickDecimals: 0,
            min: 25,
            max: 35,
            font: {
                color: 'rgba(0, 0, 0, 0.4)'
            }
        },
        yaxis: {
            //ticks: [0, 25, 50, 75, 100, 125, 150],
            font: {
                color: 'rgba(0, 0, 0, 0.4)'
            },
            // tickFormatter: function (val, axis) {
            //     return "$" + val + "K";
            // }
        },
        legend : {
            //labelBoxBorderColor: 'transparent'
        }
    });





  
});
