<script>
(async function() {
    const api = '/0/app/api/v1/nurs_activity.php';
    let raiseInit = false;
    let haulingInit = false;
    let transInit = false;

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("href");

        if (target === '#seedlingraise' && !raiseInit) {
            seedlingraise();
            raiseInit = true;
        }

        if (target === '#hauling' && !haulingInit) {
            hauling();
            haulingInit = true;
        }

        if (target === '#trans' && !transInit) {
            trans();
            transInit = true;
        }
    });

    $(document).ready(function() {
        seedlingraise();
        raiseInit = true;
    });


    async function seedlingraise() {
        const res = await Fetch.post(api, {
            ctxt: 'seedlingraise'
        });
        const data = res.data

        var labels = data.map(item => {
            const date = new Date(item.year, item.month - 1);
            const monthName = date.toLocaleString('default', {
                month: 'short'
            });
            return `${monthName} ${item.year}`;
        });

        var values = data.map(item => item.gTotal);

        var datasets = {
            labels: labels,
            datasets: [{
                label: "Data Points",
                data: values,
                fill: true,
                fillColor: "rgba(60,141,188,0.2)",
                strokeColor: "rgba(60,141,188,1)",
                pointColor: "#ffffff",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#FFFF00",
                pointHighlightStroke: "rgba(60,141,188,1)"
            }]
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
            datasetFill: true,

            showTooltips: true,

            maintainAspectRatio: false,
            responsive: true
        }

        addchart('nurs_raise', datasets, options);
    }

    async function hauling() {
        const res = await Fetch.post(api, {
            ctxt: 'hauling'
        });
        const data = res.data

        var labels = data.map(item => {
            const date = new Date(item.year, item.month - 1);
            const monthName = date.toLocaleString('default', {
                month: 'short'
            });
            return `${monthName} ${item.year}`;
        });

        var values = data.map(item => item.gTotal);

        var datasets = {
            labels: labels,
            datasets: [{
                label: "Data Points",
                data: values,
                fill: true,
                fillColor: "rgba(60,141,188,0.2)",
                strokeColor: "rgba(60,141,188,1)",
                pointColor: "#ffffff",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#FFFF00",
                pointHighlightStroke: "rgba(60,141,188,1)"
            }]
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
            datasetFill: true,

            showTooltips: true,

            maintainAspectRatio: false,
            responsive: true
        }

        addchart('nurs_hauling', datasets, options);
    }

    async function trans() {
        const res = await Fetch.post(api, {
            ctxt: 'trans'
        });
        const data = res.data

        var labels = data.map(item => {
            const date = new Date(item.year, item.month - 1);
            const monthName = date.toLocaleString('default', {
                month: 'short'
            });
            return `${monthName} ${item.year}`;
        });

        var values = data.map(item => item.gTotal);

        var datasets = {
            labels: labels,
            datasets: [{
                label: "Data Points",
                data: values,
                fill: true,
                fillColor: "rgba(60,141,188,0.2)",
                strokeColor: "rgba(60,141,188,1)",
                pointColor: "#ffffff",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#FFFF00",
                pointHighlightStroke: "rgba(60,141,188,1)"
            }]
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
            datasetFill: true,

            showTooltips: true,

            maintainAspectRatio: false,
            responsive: true
        }

        addchart('nurs_trans', datasets, options);
    }

    function addchart(cId, datasets, options) {
        const canvass = document.getElementById(cId);
        if (canvass) {
            const ctx = canvass.getContext('2d');
            const lineChart = new Chart(ctx);
            lineChart.Line(datasets, options);
        }
    }
})();
</script>