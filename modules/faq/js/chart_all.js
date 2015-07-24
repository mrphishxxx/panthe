google.load('visualization', '1', {packages: ['corechart', 'bar']});
google.setOnLoadCallback(drawStuff);

function drawStuff() {
    var data = new google.visualization.arrayToDataTable([
        ['', 'Выполнено'],
        ["Январь", 100],
        ["Февраль", 130],
        ["Март", 120],
        ["Апрель", 200],
        ['Май', 170],
        ['Июнь', 240],
        ['Июль', 200]
    ]);

    var options = {
        legend: {position: 'none'},
        axes: {
            x: {
                0: {side: 'top'} 
            }
        }
    };

    var chart = new google.charts.Bar(document.getElementById('chart_all'));
    $(".preloader-all").hide();
    chart.draw(data, google.charts.Bar.convertOptions(options));
}
; 