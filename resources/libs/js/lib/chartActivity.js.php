<script>
let mChartInitialized = false;
let pChartInitialized = false;

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    var target = $(e.target).attr("href"); // e.g., #planting or #monitoring

    if (target === '#planting' && !pChartInitialized) {
        plantingChart();
        pChartInitialized = true;
    }

    if (target === '#monitoring' && !mChartInitialized) {
        monitoringChart();
        mChartInitialized = true;
    }
});

$(document).ready(function() {
    plantingChart();
    pChartInitialized = true;
});

async function activity(tb) {
    const obj = {
        ctxt: 'get',
        tb: tb
    }

    try {
        const data = await fetch('/0/app/api/v1/tableActivity.php', {
            method: 'POST',
            body: JSON.stringify(obj)
        })

        if (!data.ok) throw new Error('Network is not ok!');

        const res = await data.json();

        //console.log(res)

        if (!res.state) {
            console.log(res.msg);
            return;
        }

        return res.data;
    } catch (err) {
        console.error(err);
    }
}


async function plantingChart() {

    let data = await activity('planting');

    var labels = data.map(item => {
        const date = new Date(item.year, item.month - 1); // month is 0-indexed
        const monthName = date.toLocaleString('default', {
            month: 'short'
        }); // "Jan", "Feb", etc.
        return `${monthName} ${item.year}`;
    });

    var values = data.map(data => data.gTotal);
    var statusColors = data.map(d => d.status === 'Done' ? '#ffffff' : '#ff0000');

    var datasets = {
        labels: labels,
        datasets: [{
            label: 'Data Points',
            data: values,
            fill: false, // No fill under the line
            pointDot: true,
            strokeColor: "rgba(60,141,188,1)",
            pointColor: "#ffffff",
            pointHighlightFill: "#FFFF00",
        }]
    }

    var options = {
        showScale: true,
        scaleShowGridLines: false,
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve: true,
        bezierCurveTension: 0.3,

        pointDot: true,
        pointDotRadius: 3,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,

        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: false,

        showTooltips: true,

        maintainAspectRatio: false,
        responsive: true
    }

    addchart('pChart', datasets, options);
}

async function monitoringChart() {
    let data = await activity('monitoring');

    const labels = data.map(lbl => lbl.species);
    const svalues = data.map(v => v.sTotal);
    const mvalues = data.map(v => v.mTotal);

    const mrate = data.map(mr => {
        const stotal = parseFloat(mr.sTotal);
        const mtotal = parseFloat(mr.mTotal);
        const total = stotal + mtotal;
        return total > 0 ? Math.ceil((mr.mTotal / total) * 100) : 0;
    });

    const srate = data.map(sr => {
        const stotal = parseFloat(sr.sTotal);
        const mtotal = parseFloat(sr.mTotal);
        const total = stotal + mtotal;
        return total > 0 ? Math.ceil((sr.sTotal / total) * 100) : 0;
    });

    var lineData = {
        labels: labels,
        datasets: [{
                label: 'Survived',
                data: svalues,
                fillColor: "transparent",
                strokeColor: "rgb(0, 255, 72)",
                pointColor: "rgb(0, 255, 72)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "#337ab7"
            },
            {
                label: 'Mortality',
                data: mvalues,
                fillColor: "transparent",
                strokeColor: "rgb(255, 0, 0)",
                pointColor: "rgb(255, 0, 0)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "#337ab7"
            }
        ]
    };

    var options = {
        showScale: true,
        scaleShowGridLines: false,
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve: true,
        bezierCurveTension: 0.3,

        pointDot: true,
        pointDotRadius: 3,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,

        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: false,

        showTooltips: true,
        multiTooltipTemplate: "<% if (datasetLabel === 'Survival Rate' || datasetLabel === 'Mortality Rate') { %> <%= datasetLabel %>: <%= value %>%<% } else { %> <%= datasetLabel %>: <%= value %><% } %>",

        maintainAspectRatio: false,
        responsive: true,
    }

    console.log('mChart', data);

    addchart('mChart', lineData, options)
}



function addchart(cId, datasets, options) {
    const canvass = document.getElementById(cId);
    if (canvass) {
        const ctx = canvass.getContext('2d');
        const lineChart = new Chart(ctx);
        lineChart.Line(datasets, options);
    }
}
</script>