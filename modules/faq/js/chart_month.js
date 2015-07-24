
google.load('visualization', '1', {packages: ['corechart', 'line']});
google.setOnLoadCallback(drawBackgroundColor);

function drawBackgroundColor() {
    var data = new google.visualization.DataTable();
    data.addColumn('number', 'X');
    data.addColumn('number', 'Выполнено');

    data.addRows([
        [0, 0], [1, 1], [2, 2], [3, 17], [4, 18], [5, 9],
        [6, 11], [7, 27], [8, 33], [9, 40], [10, 32], [11, 35],
        [12, 30], [13, 40], [14, 42], [15, 47], [16, 44], [17, 48],
        [18, 52], [19, 54], [20, 42], [21, 55], [22, 56], [23, 57],
        [24, 60], [25, 50], [26, 52], [27, 51], [28, 49], [29, 53],
        [30, 55], [31, 60]
    ]);

    var options = {
        hAxis: {
            title: 'Июнь'
        },
        vAxis: {
            title: 'Кол-во заявок'
        },
        legend: { position: 'none' },
        backgroundColor: '#fff'
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart_month'));
    $(".preloader-month").hide();
    chart.draw(data, options);
}
