<script>
async function spView(id) {

    const spData = await sp(id);

    // Create table rows from data
    let tableRows = '';
    let indivTreeCount = 0;

    //console.log(spData);

    spData.forEach((item, index) => {
        const common = item['cname'];
        const count = parseFloat(item['gTotal']);
        const sn = item['sname'] ? item['sname'] : '';

        tableRows += `
            <tr>
                <td>${index + 1}</td>
                <td>${common}</td>
                <td><i>${sn}</i></td>
                <td>${count}</td>
            </tr>
        `;

        indivTreeCount += count;
    });

    // Full table HTML
    const speciesTable = `
        <table class="table table-bordered table-striped" id="spTable">
            <thead style="position:sticky;top:-18px">
                <tr class="success">
                    <th></th>
                    <th>Common name</th>
                    <th>Species</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    const mId = 'speciesModal';
    const mTitle = `
        Species List
        <span id="spCount">[${indivTreeCount}]</span>
        <span id="spTotal"></span>`
    const actions = `
         <button type="button" class="btn btn-primary pull-left" id="edit">
           Edit
        </button>
        <button type="button" class="btn btn-primary pull-left" id="save">
            Save
        </button>
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
        </button>`;

    modal.crt(mId, mTitle, speciesTable, actions);

    let isEditing = false;
    let spForm = [];
    let total = 0;

    $('#edit').on('click', function() {

        if (!isEditing) {
            $('#spTable tbody td')
                .attr('contenteditable', 'true')
                .addClass('editable');
            $(this).text('Stop');
        } else {
            $('#spTable tbody td')
                .removeAttr('contenteditable')
                .removeClass('editable');
            $(this).text('Edit');

            const spMap = {};
            total = 0;

            $('#spTable tbody tr').each(function(index) {
                const common = $(this).find('td:eq(1)').text().trim();
                const sn = $(this).find('td:eq(2)').text().trim();
                const count = parseFloat($(this).find('td:eq(3)').text().trim()) || 0;

                if (!spMap[common]) {
                    spMap[common] = {
                        sn: sn,
                        count: count
                    };
                } else {
                    spMap[common].count += count;
                }

                total += count
            });

            // Convert spMap to desired array format
            spForm = Object.keys(spMap).map(common => {
                return {
                    [common]: spMap[common].count,
                    sn: spMap[common].sn
                };
            });

            console.log(spForm)

            $('#spCount').text(`[${spForm.length}]`);
            $('#spTotal').text(` - [${total}]`);

            // Clear existing tbody and insert updated rows
            const $tbody = $('#spTable tbody');
            $tbody.empty();

            Object.keys(spMap).forEach((common, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${common}</td>
                        <td>${spMap[common].sn}</td>
                        <td>${spMap[common].count}</td>
                    </tr>
                `;
                $tbody.append(row);
            });

            //console.log(spForm);
        }

        isEditing = !isEditing;
    });

    $('#save').on('click', async function() {
        if (spForm.length <= 0) {
            alert('No changes found');
            return;
        }

        const obj = {
            context: 'update_sp',
            fid: id,
            sp: spForm,
            total: total
        }

        try {
            const data = await fetch('/0/app/api/v1/tablePlantedTrees.php', {
                method: "POST",
                body: JSON.stringify(obj)
            })

            if (!data.ok) throw new Error('Network is not ok!');

            const res = await data.json();

            console.log(res);

            if (!res.state) {
                alert(res.msg);
                return;
            }

            alert(res.msg);
            $('#speciesModal').on('hidden.bs.modal', function() {
                sendPost('table_total_planted_trees');
            });
            total = 0;
        } catch (err) {
            console.error(err);
        }
    })
}

async function sp(id) {
    const obj = {
        ctxt: 'get',
        fid: id,
        tb: 'sp'
    }

    try {
        const data = await fetch(`/0/app/api/v1/tablePlantedTrees.php`, {
            method: "POST",
            body: JSON.stringify(obj)
        });

        if (!data.ok) throw new Error('Network is not ok!');

        const res = await data.json();

        if (!res.state) throw new Error(res.msg);

        return res.data;
    } catch (err) {
        console.error(err);
    }
}

let tempData = {};

function addPlatedRecord() {
    const labels = ['Parcel', 'Clan', 'Sitio', 'Plot', 'Recorder', 'Owner', 'Species', 'Planted', 'Date'];
    const type = ['text', 'text', 'text', 'number', 'text', 'text', 'text', 'number', 'date'];

    let tableRows = '';

    labels.forEach((item, index) => {
        let input = `
        <td>
            <input type="${type[index]}" placeholder="Add ${item}..." class="ptform form-control editable-td-input">
        </td>`;

        if (item === 'Planted') {
            input = `
            <td>
                <input id="totalPt" type="${type[index]}" class="ptform form-control editable-td-input" style="background:none" readonly>
            </td>`;
        }

        if (item === 'Parcel') {
            input = `
                <td>
                    <select class="ptform form-control editable-td-input">
                        <option value="" selected disabled>--Select Parcel--</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                </td>
            `;
        }

        if (item === 'Species') {
            input = `
                <td>
                    <a href="#" onclick="addspp()" id="spp">0</a>
                </td>
            `;
        }

        if (item === 'Clan') {
            input = `
                <td>
                    <select class="ptform form-control editable-td-input">
                        <option value="" selected disabled>--Select Clan--</option>
                        <option>Ahom</option>
                        <option>Datal</option>
                        <option>Udto</option>
                    </select>
                </td>
            `;
        }

        tableRows += `
            <tr>
                <td style="width:100px">${item}</td>
                ${input}
            </tr>
        `;
    })

    const table = `
         <table class="table table-bordered table-striped" id="spTable">
            <thead style="position:sticky;top:-18px">
                <tr class="success">
                    <th>name</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    const mId = 'addPt';
    const title = `Add Planting Record`;
    const body = table;
    const actions = `
        <button class="btn btn-primary pull-left saveBtn">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
        </button>
    `;

    modal.crt(mId, title, body, actions);

    $('.saveBtn').on('click', async function() {
        const cfrm = confirm('Confirm to save');
        if (!cfrm) return;

        const inputData = $('.ptform');
        const prm = ['pcl', 'clan', 'sitio', 'plot', 'rcd', 'owner', 'plntd', 'date'];
        let obj = {};

        inputData.each(function(index, element) {
            obj[prm[index]] = $(element).val();
        });

        obj.spp = tempData;
        obj.tb = 'pt';
        obj.ctxt = 'crt';

        console.log(obj);


        try {
            const data = await fetch('/0/app/api/v1/tablePlantedTrees.php', {
                method: 'POST',
                body: JSON.stringify(obj)
            });

            if (!data.ok) throw new Error("Network is not ok!");

            const res = await data.text();

            console.log(res);

            if (!res.state) throw new Error(res.msg);

            alert(json.msg);
        } catch (err) {
            console.error(err);
        }
    })
}

function addspp() {
    let rows = '';
    let totalPt = 0;

    if (tempData && Object.keys(tempData).length > 0) {
        const keys = Object.keys(tempData); // ["Apples", "Oranges"]
        const values = Object.values(tempData); // [10, 5]

        keys.forEach((item, index) => {
            rows += `
                <tr>
                    <td contenteditable="true" data-placeholder="Add name...">
                        ${item}
                    </td>
                    <td contenteditable="true" data-placeholder="Add count...">
                        ${values[index]}
                    </td>
                </tr>
            `;
        })
    } else {
        rows = `
            <tr>
                <td contenteditable="true" data-placeholder="Add name..."></td>
                <td contenteditable="true" data-placeholder="Add count..."></td>
            </tr>
        `;
    }

    const table = `
         <table class="table table-bordered table-striped" id="sppTable">
            <thead style="position:sticky;top:-18px">
                <tr class="success">
                    <th>Common name</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                ${rows}
            </tbody>
        </table>
    `;
    const mId = 'addspp';
    const title = `Add species <span id="sppcount"></span>`;
    const body = table;
    const actions = `
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
        </button>
    `;

    modal.crt(mId, title, body, actions);

    let sppcount = 0;

    $(document).on('keydown', '#sppTable td[contenteditable="true"]', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            const $currentRow = $(this).closest('tr');
            const $tableBody = $('#sppTable tbody');

            const tdValues = $currentRow.find('td').map(function() {
                return $(this).text().trim();
            }).get();

            const allFilled = tdValues.every(val => val !== '');

            // Add new row only if both cells are filled and it's the last row
            if ($currentRow.is(':last-child') && allFilled) {
                const newRow = `
                <tr class="row-number">
                    <td contenteditable="true" data-placeholder="Add name..."></td>
                    <td contenteditable="true" data-placeholder="Add count..."></td>
                </tr>`;

                $tableBody.append(newRow);

                $('#sppcount').text(`[${updateRowCount()}]`)
                $tableBody.find('tr:last-child td:first').focus();
            }
        }
    });

    function updateRowCount() {
        const count = $('#sppTable tbody tr').length;
        return count;
    }

    $(document).on('input', '#sppTable td[contenteditable="true"]', function() {
        const $td = $(this);
        const cellIndex = $td.index();

        // Only apply to the second column (index 1)
        if (cellIndex === 1) {
            const originalText = $td.text();
            const numericText = originalText.replace(/\D/g, ''); // Remove all non-digits
            if (originalText !== numericText) {
                $td.text(numericText);

                // Move cursor to end
                const range = document.createRange();
                const sel = window.getSelection();
                range.selectNodeContents($td[0]);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    });

    $(document).on('blur', '#sppTable tr:last-child td', function() {
        const $lastRow = $('#sppTable tbody tr:last-child');
        const isEmpty = $lastRow.find('td').toArray().every(td => $(td).text().trim() === '');

        if (isEmpty && $('#sppTable tbody tr').length > 1) {
            let count = parseFloat(updateRowCount());
            $('#sppcount').text(`[${count - 1}]`)
            $lastRow.remove();
        }
    });

    function storeTempData() {
        const data = $('#sppTable tbody tr');
        const result = {};

        data.each(function() {
            const tds = $(this).find('td');
            const name = $(tds[0]).text().trim();
            const count = parseInt($(tds[1]).text().trim(), 10);

            if (name && !isNaN(count)) {
                result[name] = count;
            }
        });

        tempData = result;
    }

    $(`#${mId}`).on('hidden.bs.modal', function() {
        storeTempData();
        totalPt = Object.values(tempData).reduce((sum, val) => sum + Number(val), 0);
        // console.log('tempData', tempData);
        // console.log('countTotal', totalPt)
        $('#totalPt').val(totalPt);
        $('#spp').text(updateRowCount());
    });

    $(`#addPt`).on('hidden.bs.modal', function() {
        tempData = {};
        $('#spp').text(updateRowCount());
    });
}

async function editPlatedRecord(element) {
    const td = element.closest('td');
    const tr = td.closest('tr');
    const id = parseFloat(tr.id);

    const spp = await sp(id);

    spp.forEach(item => {
        tempData[item['cname']] = parseFloat(item['gTotal'])
    })

    console.log(tempData);

    const cells = tr.querySelectorAll('td');

    const c = (i) => cells[i].textContent.trim();
    const values = [];

    cells.forEach((cell, i) => {
        values.push(c(i));
    });

    const labels = ['Parcel', 'Clan', 'Sitio', 'Plot', 'Recorder', 'Owner', 'Species', 'Planted', 'Date'];
    const type = ['text', 'text', 'text', 'number', 'text', 'text', 'text', 'number', 'date'];

    let tableRows = '';

    labels.forEach((item, index) => {
        let input = `
        <td>
            <input type="${type[index]}" placeholder="Add ${item}..." class="ptform form-control editable-td-input" value="${values[index]}">
        </td>`;

        if (item === 'Planted') {
            input = `
            <td>
                <input id="totalPt" type="${type[index]}" class="ptform form-control editable-td-input" style="background:none" value="${values[index]}" readonly>
            </td>`;
        }

        if (item === 'Parcel') {
            input = `
                <td>
                    <select id="pcl" class="ptform form-control editable-td-input">
                        <option value="" selected disabled>--Select Parcel--</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                </td>
            `;
        }

        if (item === 'Species') {
            input = `
                <td>
                    <a href="#" onclick="addspp()" id="spp">${values[index]}</a>
                </td>
            `;
        }

        if (item === 'Clan') {
            input = `
                <td>
                    <select id="clan" class="ptform form-control editable-td-input">
                        <option value="" selected disabled>--Select Clan--</option>
                        <option>Ahom</option>
                        <option>Datal</option>
                        <option>Udto</option>
                    </select>
                </td>
            `;
        }

        tableRows += `
            <tr>
                <td style="width:100px">${item}</td>
                ${input}
            </tr>
        `;
    })

    const table = `
         <table class="table table-bordered table-striped" id="editSpTable">
            <thead style="position:sticky;top:-18px">
                <tr class="success">
                    <th>name</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    const mId = 'editPt';
    const title = `Edit Planting Record`;
    const body = table;
    const actions = `
        <button class="btn btn-primary pull-left saveBtn2">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
        </button>
    `;

    modal.crt(mId, title, body, actions);

    $('#pcl').val(c(0));
    $('#clan').val(c(1));

    $('.saveBtn2').on('click', function() {
        const cfrm = confirm('Confirm to save');
        if (!cfrm) return;

        const form = document.querySelectorAll('.ptform');
        let upData = {};

        form.forEach(function(item, index) {
            upData[labels[index]] = item.value;
        })

        console.log(upData)
    })

    function updateRowCount() {
        const count = $('#editSpTable tbody tr').length;
        return count;
    }

    function storeTempData() {
        const data = $('#editSpTable tbody tr');
        const result = {};

        data.each(function() {
            const tds = $(this).find('td');
            const name = $(tds[0]).text().trim();
            const count = parseInt($(tds[1]).text().trim(), 10);

            if (name && !isNaN(count)) {
                result[name] = count;
            }
        });

        tempData = result;
    }

    $(`#addspp`).on('hidden.bs.modal', function() {
        storeTempData();
        totalPt = Object.values(tempData).reduce((sum, val) => sum + Number(val), 0);
        // console.log('tempData', tempData);
        // console.log('countTotal', totalPt)
        $('#totalPt').val(totalPt);
        $('#spp').text(updateRowCount());
    })

    $(`#${mId}`).on('hidden.bs.modal', function() {
        tempData = {};
        $('#spp').text(updateRowCount());
    });
}
</script>