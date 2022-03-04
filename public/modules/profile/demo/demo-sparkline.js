 var responsiveSparklineTiles = function() {

        $("#sparkline-revenue").sparkline([24,19,26,28,22,19,10,13,10,15,22,26,16,22,20,27,32,31,29,22,19,8,2,5,6,14,6,10,13,8,19,21,26,29,24], {
            type: 'line',
            width: '100%',
            height: 32,
            fillColor: '#add07c',
            lineWidth: 0,
            lineColor: '#add07c',
            chartRangeMin: 0,
            disableInteraction: true,
            spotRadius: 0
        });

        $("#sparkline-commission").sparkline([27,26,21,18,8,12,10,6,15,7,6,2,8,11,13,9,14,18,27,28,26,32,22,19,18,16,19,20,17,24,20,29,26,20,24], {
            type: 'line',
            width: '100%',
            height: 32,
            lineWidth: 2,
            fillColor: 'rgba(38, 166, 91,0.55)', //rgba(217, 30, 24,0.5), rgba(242, 38, 19,0.5) ,rgba(248, 148, 6,0.5),rgba(38, 166, 91,0.5),rgba(135, 211, 124,0.5),rgba(34, 167, 240,0.5)
            lineColor: '#26A65B', //#D91E18,#F22613,#F89406,#26A65B,#87D37C,#22A7F0
            chartRangeMin: 0,
            disableInteraction: true,
            spotRadius: 0
        });


        $("#sparkline-item").sparkline([5,3,9,6,5,9,7,3,5,2,5,7,5,2,5,3,9,6,5,9,7,3,5,2,5,7,5,2], {
            type: 'bar',
            barSpacing: 4,
            barWidth: 2,
            height: 32,
            barColor: '#e1e1e1',
            disableInteraction: true,
            spotRadius: 0
        });

        $("#tiles-sparkline-stats-pageviews").sparkline([2455,1234,776,349,1776,2234,2455], {
            type: 'line',
            lineColor: '#ccc',
            lineWidth: '1',
            fillColor: 'rgba(0, 0, 0, 0.02)',
            height: 96,
            width: '100%',
            minSpotColor: false,
            maxSpotColor: false,
            spotColor: false,
            spotRadius: '2',
            highlightSpotColor: '#999999',
            highlightLineColor: 'transparent'
        });

        $("#tiles-sparkline-stats-totalsales").sparkline([2455,3534,5776,4349,5179,524,1123], {
            type: 'line',
            lineColor: '#ccc',
            lineWidth: '1',
            fillColor: 'rgba(0, 0, 0, 0.02)',
            height: 96,
            width: '100%',
            minSpotColor: false,
            maxSpotColor: false,
            spotColor: false,
            spotRadius: '2',
            highlightSpotColor: '#999999',
            highlightLineColor: 'transparent'
        });

        $("#tiles-sparkline-stats-totalorders").sparkline([255,134,76,120,350,400,98], {
            type: 'line',
            lineColor: '#ccc',
            lineWidth: '1',
            fillColor: 'rgba(0, 0, 0, 0.02)',
            height: 96,
            width: '100%',
            minSpotColor: false,
            maxSpotColor: false,
            spotColor: false,
            spotRadius: '2',
            highlightSpotColor: '#999999',
            highlightLineColor: 'transparent'
        });
    }

    var refreshSparklines;
    $(window).resize(function(e) {
        clearTimeout(refreshSparklines);
        refreshSparklines = setTimeout(responsiveSparklineTiles, 500);
    });

    responsiveSparklineTiles();