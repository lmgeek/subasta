<?php use Carbon\Carbon; ?>
<div class="col-md-{{ $width }}">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>{{ $graphTitle }}</h5>
        </div>
        <div class="ibox-content graph_{{ $id }}">
            <canvas id="lineChart_{{ $id }}" style="width: 100%"></canvas>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var data = {
            labels: {!! json_encode($x) !!},
            datasets: [
                    @foreach($values as $k=>$v)
                    {
                    label: '{{ $titles[$k] }}',
                    fillColor: "rgba({{ $colors[$k]['r'] }}, {{ $colors[$k]['g'] }}, {{ $colors[$k]['b'] }},0.2)",
                    strokeColor: "rgba({{ $colors[$k]['r'] }}, {{ $colors[$k]['g'] }}, {{ $colors[$k]['b'] }},1)",
                    pointColor: "rgba({{ $colors[$k]['r'] }}, {{ $colors[$k]['g'] }}, {{ $colors[$k]['b'] }},1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba({{ $colors[$k]['r'] }}, {{ $colors[$k]['g'] }}, {{ $colors[$k]['b'] }},1)",
                    data: {!!  json_encode($v) !!}
                },
                @endforeach
            ]
        };
        var options = {
            multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>"
        };

        var ctx = document.getElementById("lineChart_{{ $id }}").getContext("2d");
        ctx.canvas.width  = $('.graph_{{ $id }}').width();
        var myLineChart_{{ $id }} = new Chart(ctx).Line(data,options);
    });
</script>

<script src="{{ asset('/js/plugins/chartJs/Chart.min.js') }}"></script>
