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
                <div id="time_chart" style="width:100%; height:500px;"></div>
            </div>
    </div>
</body>

</html>


{{-- echarts --}}
<script>
    $(document).ready(function(){
        // console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);
        $.ajax({
            method: 'POST',
            url: '/api',
            headers:{ 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
            data: {account:"{{ $account }}", timezone:Intl.DateTimeFormat().resolvedOptions().timeZone},
            success:function(result){

            }
        })
    });
</script>
