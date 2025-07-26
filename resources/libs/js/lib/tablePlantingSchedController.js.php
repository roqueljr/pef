<script>
document.getElementById('end')?.addEventListener('input', function() {
    const start = document.getElementById('start');
    const end = document.getElementById('end');

    const startDate = new Date(start.value);
    const endDate = new Date(end.value);

    if (endDate < startDate) {
        resModal('danger', "End date cannot be earlier than start date.");
        end.value = "";
    }
});

document.getElementById('saveSchedForm')?.addEventListener('submit', async function(event) {
    event.preventDefault();


    const form = new FormData(this);

    const act = form.get('act');
    const id = form.get('fid');

    // for (let [key, value] of form.entries()) {
    //     console.log(`${key}: ${value}`);
    // }

    try {
        const data = await fetch(`/0/app/api/v1/plantingSched.php?act=${act}&id=${id}`, {
            method: "POST",
            body: form
        })

        if (!data.ok) throw new Error('Network is not ok!');

        const res = await data.json();

        console.log(res);

        if (!res.state) {
            resModal('danger', res.msg);
            return;
        }

        resModal('success', res.msg);
        $('#addSched').on('hidden.bs.modal', function() {
            sendPost('planting_schedules');
        });
    } catch (err) {
        console.err(err);
        resModal('danger', err);
    }
})

function resModal(type, msg) {
    $(document).ready(function() {
        const modal = $('#addSchedModal');
        const title = modal.find('.modal-title');
        modal.removeClass('modal-success modal-danger');

        if (type === 'success') {
            modal.addClass('modal-success');
        } else if (type === 'danger') {
            modal.addClass('modal-danger');
        }

        if (msg) {
            title.text(msg);
        }

        modal.modal('show');
    });
}

document.getElementById("addSchedBtn")?.addEventListener('click', function() {
    //console.log('addschedBtn is click');
    document.getElementById('saveSchedForm').reset();
    document.querySelector(".addSchedModalTitle").innerText = 'Add Planting schedule';
    document.getElementById('act').value = 'insert';
    document.getElementById('fid').value = '';
})


function editSched(element, id) {
    const tr = element.closest('tr');
    const tds = tr.querySelectorAll('td');
    const values = Array.from(tds).map(td => td.textContent.trim());

    const el = (id) => document.getElementById(id);

    document.querySelector(".addSchedModalTitle").innerText = 'Update Planting schedule';

    el('fid').value = id;
    el('act').value = 'update';
    el('start').value = values[0];
    el('end').value = values[1];
    el('parcel').value = values[2];
    el('ha').value = values[3];
    el('native').value = values[4];
    el('fruit').value = values[5];
    el('status').value = values[7];

    $('#addSched').modal('show');
}

document.getElementById('downloadCsvBtn')?.addEventListener('click', function() {
    const table = document.querySelector('#example1');
    const rows = Array.from(table.querySelectorAll('tr'))
        .filter(row => !row.classList.contains('no-export'));

    const data = rows.map(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        return cells
            .filter(cell => !cell.classList.contains('no-export'))
            .map(cell => cell.textContent.trim());
    });

    const csv = Papa.unparse(data);

    // Trigger download
    const blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8;'
    });
    const url = URL.createObjectURL(blob);

    const now = new Date();
    const pad = (num) => String(num).padStart(2, '0');
    const timestamp =
        now.getFullYear() +
        pad(now.getMonth() + 1) +
        pad(now.getDate()) + '_' +
        pad(now.getHours()) +
        pad(now.getMinutes()) +
        pad(now.getSeconds());
    const a = document.createElement('a');
    a.href = url;
    a.download = `planting_schedules_${timestamp}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
});
</script>