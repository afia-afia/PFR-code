<table class='table table-sm'>
<tr><th>Name</th><th>Time untill full</th></tr>
@foreach ($deltas as $delta)
<tr>
  <td>{{ $delta->filesystem() }}</td>
  <td>{{ $delta->timeUntilFullForHumans() }}</td>
</tr>
@endforeach
</table>

<canvas id="diskevolution-chart" width='400' height='300'></canvas>
<script>
    window.addEventListener('load', function() {
        window.loadDiskEvolutionChart(document.getElementById('diskevolution-chart'));
    });
</script>
