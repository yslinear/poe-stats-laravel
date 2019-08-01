<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Profile-{{ $account }}</title>

    <!-- Styles -->
    <style>
    </style>
</head>
@include('includes.head')

<body>
    @include('includes.navbar')
    <div class="container-fluid">
        <h4 class="mx-1 my-1">{{ $account }}</h1>
            <div class="row">
                <div id="bubble_chart" style="width:100%; height:500px;"></div>
            </div>
    </div>

</body>

</html>

<script>
    $(document).ready(function(){
        // console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);
        $.getJSON("/api", {account:"{{ $account }}", timezone:Intl.DateTimeFormat().resolvedOptions().timeZone},
        function (result) {
        console.log(JSON.stringify(result));
        google.charts.load("current", {packages:["corechart"]});
        google.charts.load('current', {'packages':['line']});
        google.charts.load('current', {'packages':['scatter']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('number', 'week');
                    data.addColumn('timeofday', 'online');
                    // dataTable.addColumn({"label":"online",type: 'timeofday', role: 'interval'});
                    data.addColumn('timeofday', 'offline');
                    for(i=0;i<result.length;i++)
                    {
                        data.addRows([result[i]]);
                        console.log(JSON.stringify(result[i]));
                    }
                    var options = {
                        interpolateNulls: true,
                        legend: 'none',
                        title: 'online time',interpolateNulls : true,
                        series: {
                                0: {color: 'green', pointSize: 4,},
                                1: {color: 'Lightgray', pointSize: 2},
                            },
                        hAxis: {title: 'week', viewWindow:{ min:1,max:7},format: '#',
                            ticks: [{v:1, f:'Mon'}, {v:2, f:'Tue'}, {v:3, f:'Wed'},{v:4, f:'Thu'},
                                    {v:5, f:'Fri'}, {v:6, f:'Sat'}, {v:7, f:'Sun'}]
                        },
                        vAxis: {title: '', format:'hh:mm a'},
                    };
                    var chart = new google.visualization.ScatterChart(document.getElementById('bubble_chart'));
                    chart.draw(data, options);
                }
        });
    });
</script>
