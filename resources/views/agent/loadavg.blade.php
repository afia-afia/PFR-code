
<p>Current load: {{ $current_load }}</p>

<canvas id="load-chart" width='400' height='300'></canvas>
<script>
    window.addEventListener('load', function() {
        window.monitorLoadChart(document.getElementById('load-chart'));
    });
</script>
