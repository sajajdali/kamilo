$(function() {
    //Easy Pie Chart
    //------------------------

    try {
    $('.easypiechart#WLP').easyPieChart({
        barColor: "#16a085",
        trackColor: '#edeef0',
        scaleColor: '#d2d3d6',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        animate:2000,
        size: 120,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });

    $('.easypiechart#Currentweightgain').easyPieChart({
        barColor: "#f85959",
        trackColor: '#edeef0',
        scaleColor: '#d2d3d6',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        animate:2000,
        size: 120,
        onStep: function(from, to, percent) {
            $(this.el).find('.Currentweight').text(Math.round(percent));
        }
    });
     $('.easypiechart#WeightLoss').easyPieChart({
        barColor: "#009d29",
        trackColor: '#edeef0',
        scaleColor: '#d2d3d6',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        animate:2000,
        size: 120,
        onStep: function(from, to, percent) {
            $(this.el).find('.Currentweight').text(Math.round(percent));
        }
    });
$('.easypiechart#WeightAppropriate').easyPieChart({
        barColor: "#40cd65",
        trackColor: '#edeef0',
        scaleColor: '#d2d3d6',
        scaleLength: 5,
        lineCap: 'square',
        lineWidth: 2,
        animate:2000,
        size: 120,
        onStep: function(from, to, percent) {
            $(this.el).find('.Currentweight').text(Math.round(percent));
        }
    });

    }
    catch(error) {}

});