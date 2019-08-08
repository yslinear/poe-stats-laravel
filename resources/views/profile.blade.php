<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Profile-{{ $account }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.2.1/echarts-en.js"
        integrity="sha256-mu5bHUqVuu7g+anbTxmEuwjZwy0WQOAZxw2bkdAijvM=" crossorigin="anonymous"></script>
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
                <div id="time-heatmap" class="charts" style="width: 100%;height:650px;"></div>
            </div>
    </div>
</body>

</html>

{{-- echarts --}}
<script>
    $(document).ready(function () {
        // console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);
        $.ajax({
            method: 'POST',
            url: '/api',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                account: "{{ $account }}",
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
            },

            success: function (result) {
                console.log(JSON.stringify(result));
                var myChart = echarts.init(document.getElementById('time-heatmap'));

                var hours = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12',
                    '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'
                ];
                var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                // var data = [
                //     [0, 0, 1],
                //     [0, 2, 112311],
                //     [1, 1, 1]
                // ];

                data = result.map(function (item) {
                    return [item[0], item[1], item[2] || '-'];
                });

                option = {
                    tooltip: {
                        position: 'top'
                    },
                    animation: false,
                    grid: {
                        height: '50%',
                        y: '10%'
                    },
                    xAxis: {
                        type: 'category',
                        data: days,
                        splitArea: {
                            show: true
                        }
                    },
                    yAxis: {
                        type: 'category',
                        data: hours,
                        splitArea: {
                            show: true
                        }
                    },
                    visualMap: {
                        min: 0,
                        max: 5,
                        calculable: true,
                        orient: 'horizontal',
                        left: 'center',
                        bottom: '15%'
                    },
                    series: [{
                        name: 'Punch Card',
                        type: 'heatmap',
                        data: data,
                        label: {
                            normal: {
                                show: true
                            }
                        },
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                };
                myChart.setOption(option);
            }
        })
    });

</script>
