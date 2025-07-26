<script>
viewBarChart();

async function dominatTrees() {
    const obj = {
        ctxt: 'getAll',
        lmt: 10
    }

    try {
        const data = await fetch('/0/app/api/v1/tableTreeSp.php', {
            method: 'POST',
            body: JSON.stringify(obj)
        })

        if (!data.ok) throw new Error('Network is not ok!');

        const res = await data.json();

        if (!res.state) {
            console.log(res.msg);
            return;
        }

        return res.data;
    } catch (err) {
        console.error(err);
    }
}

async function viewBarChart() {
    const Utils = {
        CHART_COLORS: {
            red: 'rgb(255, 99, 132)',
            blue: 'rgb(54, 162, 235)'
        }
    };

    const trees = await dominatTrees();
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

    const canvass = document.getElementById('barChart');
    if (canvass) {
        const ctx = canvass.getContext('2d');
        const barChart = new Chart(ctx);
        barChart?.Bar(data, barChartOptions);
    }
}

async function editSp(element, id) {
    $('#spsModal').remove();
    const td = element;
    const tr = td.closest('tr');

    const tds = tr.querySelectorAll('td');
    const labels = ['Common name', 'Scientific name', 'Family name'];

    // Create table rows from data
    let tableRows = '';
    let ctnt = [];

    tds.forEach((td, index) => {
        const tdv = td.textContent;

        if (index === 0) {
            ctnt.oname = tdv;
        }

        if (index < 3) {
            ctnt.push(tdv);
            tableRows += `
            <tr>
                <td>${labels[index]}</td>
                <td contentEditable="true">${tdv}</td>
            </tr>`;
        }
    });

    // Full table HTML
    const speciesTable = `
        <table class="table table-bordered table-striped" id="spTable">
            <thead style="position:sticky;top:-18px">
                <tr class="success">
                    <th>Name</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    // Modal container with table in body
    const modalContainer = `
        <div class="modal fade custom-modal" tabindex="-1" role="dialog" id="spsModal"
        aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title addSchedModalTitle" id="gridSystemModalLabel">
                            Species Information
                        </h4>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        ${speciesTable}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left" id="save">
                            Save
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Append modal and show
    $('body').append(modalContainer);
    $('#spsModal').modal('show');

    const editedForm = document.querySelectorAll('td[contenteditable="true"]');
    editedForm.forEach((item, index) => {
        item.addEventListener('input', function() {
            ctnt[index] = item.textContent;

            console.log(ctnt);
        })
    });

    $('#save').on('click', async function() {
        const cfrm = confirm('Confirm to save changes');

        if (!cfrm) return;

        const obj = {
            ctxt: 'upd',
            oname: ctnt.oname,
            cname: ctnt[0],
            sname: ctnt[1],
            fname: ctnt[2],
            class: ctnt[3]
        }

        try {
            const data = await fetch('/0/app/api/v1/tableTreeSp.php', {
                method: 'POST',
                body: JSON.stringify(obj)
            })

            if (!data.ok) throw new Error('Network is not ok!');

            const res = await data.json();

            console.log(res);

            if (!res.state) {
                console.log(res.msg);
                return;
            }

            alert(res.msg);
            $('#spsModal').on('hidden.bs.modal', function() {
                sendPost('table_dominantTreeSpecies');
            });
        } catch (err) {
            console.error(err);
        }
    });

}
</script>