<script>
(async function() {
    const Utils = {
        CHART_COLORS: {
            red: 'rgb(255, 99, 132)',
            blue: 'rgb(54, 162, 235)'
        }
    };

    const fetchdata = await Fetch.post('/0/app/api/v1/nurs_per_species.php', {
        ctxt: 'get'
    });
    const trees = fetchdata.data;
    const labels = trees.map(tree => tree.cname);
    const counts = trees.map(tree => tree.spTotal);

    const tooltipLabels = trees.map(tree => `${tree.sname}`);

    const data = {
        labels: labels,
        datasets: [{
            label: 'Tree Count',
            fillColor: Utils.CHART_COLORS.red,
            strokeColor: 'rgba(220,220,220,1)',
            highlightFill: 'rgba(255,99,132,0.75)',
            highlightStroke: 'rgba(220,220,220,1)',
            data: counts
        }]
    };

    var barChartOptions = {
        scaleBeginAtZero: true,
        scaleShowGridLines: true,
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        barShowStroke: true,
        barStrokeWidth: 2,
        barValueSpacing: 5,
        barDatasetSpacing: 1,
        responsive: true,
        maintainAspectRatio: true,
        datasetFill: true,
        showTooltips: true,
        tooltipTemplate: function(data) {
            const index = labels.indexOf(data.label);
            const rtlabel = tooltipLabels[index];
            const tlabel = rtlabel === 'null' ? 'TBI' : rtlabel;
            return `${tlabel}: ${data.value} trees`;
        }
    }

    const canvass = document.getElementById('nurs_barChart');
    if (canvass) {
        const ctx = canvass.getContext('2d');
        const barChart = new Chart(ctx);
        barChart?.Bar(data, barChartOptions);
    }
})();
</script>