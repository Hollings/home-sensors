<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

        <style type="text/css">
            .chart-container{
                padding:50px;
            }
                
        </style>

    </head>
    <body>
        <div style="text-align:center">  <h3>{{ $current['humidity']->value }}% | {{ $current['temperature']->value}} F</div>
      
                    @foreach($charts as $title=>$chart)
 
       <div class="chart-container"> 
        <h1>{{ $title }}</h1>
        {!! $chart->container() !!} 
        </div>

        @endforeach



<!-- Chart.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        @foreach($charts as $title => $chart)

        {!! $chart->script() !!}
        @endforeach
    </body>
</html>
