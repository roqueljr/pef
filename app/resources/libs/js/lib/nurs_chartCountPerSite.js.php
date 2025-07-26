<script>
(async function() {
    var predefinedColors = [
        'rgba(255, 99, 132, 0.7)', // Color for Site A
        'rgba(54, 162, 235, 0.7)', // Color for Site B
        'rgba(255, 206, 86, 0.7)', // Color for Site C
        'rgba(255, 0, 0, 0.7)', // Color for Site D
        'rgba(0, 255, 0, 0.7)', // Color for Site E
        'rgba(0, 0, 255, 0.7)', // Color for Site F
        'rgba(128, 0, 128, 0.7)', // Color for Site G
        'rgba(255, 165, 0, 0.7)', // Color for Site H
        'rgba(0, 128, 128, 0.7)', // Color for Site I
        'rgba(128, 128, 0, 0.7)', // Color for Site J
        'rgba(128, 0, 0, 0.7)', // Color for Site K
        'rgba(0, 128, 0, 0.7)', // Color for Site L
        'rgba(0, 0, 128, 0.7)', // Color for Site M
        'rgba(255, 69, 0, 0.7)', // Color for Site N
        'rgba(128, 128, 128, 0.7)', // Color for Site O
        'rgba(0, 255, 255, 0.7)', // Color for Site P
        'rgba(255, 255, 0, 0.7)', // Color for Site Q
        'rgba(255, 0, 255, 0.7)', // Color for Site R
        'rgba(0, 255, 128, 0.7)', // Color for Site S
        'rgba(255, 128, 0, 0.7)' // Color for Site T
    ];

    function getRandomColor() {
        var color = predefinedColors[colorIndex];
        colorIndex = (colorIndex + 1) % predefinedColors.length;
        return color;
    }

    var colorIndex = 0;
    var pieData = [];
    const data = await Fetch.post('/0/app/api/v1/nurs_prop_per_site.php', {
        ctxt: 'get'
    });
    const trees = data.data;
    const labels = trees.map(tree => tree.nurs_site.split(" ")[0]);
    const counts = trees.map(tree => tree.grandTotal);

    labels.forEach((label, index) => {
        const color = getRandomColor();
        pieData.push({
            value: counts[index],
            color: color,
            highlight: color,
            label: label
        });
    });

    var pieOptions = {
        //sigments
        segmentShowStroke: true,
        segmentStrokeColor: '#fff',
        segmentStrokeWidth: 1,
        //dougnot
        percentageInnerCutout: 50,
        //animations
        animationSteps: 100,
        animationEasing: 'easeOutBounce',
        animateRotate: true,
        animateScale: false,
        responsive: true,
        maintainAspectRatio: false,
        highlight: true,
        hover: true,
    }

    const canvass = document.getElementById('nurs_pieChart');
    if (canvass) {
        const ctx = canvass.getContext('2d');
        const pieChart = new Chart(ctx);
        pieChart.Doughnut(pieData, pieOptions)
    }

})();
</script>