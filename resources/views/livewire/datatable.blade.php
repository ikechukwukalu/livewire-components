<div class="row">
    <div class="col-md-6 pl-3 mb-2">
        <div class="btn-group">
            <button type="button" class="btn btn-dark" onClick="export_pdf()">PDF</button>
            <button type="button" class="btn btn-dark">EXCEL</button>
            <button type="button" class="btn btn-dark">CSV</button>
        </div>
    </div>
    <div class="col-md-6 pl-3 mb-2">
    </div>
    <div class="col-md-6 pl-3">
        <div class="float-left form-inline">
            <label>Show</label>
            <select class="form-control mr-1 ml-1" wire:model="pages_displayed">
                @foreach($page_options as $p)
                <option>{{ $p }}</option>
                @endforeach
            </select>
            <label>entries</label>
        </div>
    </div>
    <div class="col-md-6 pr-3">
        <div class="float-right form-inline">
            <label>Search:</label>
            <input type="text" wire:model="search" class="form-control ml-1" placeholder="Search" />
        </div>
    </div>
    <div class="col-md-12 mt-3 mb-3">
        <div class="table-responsive">
            <table id="livewire-datatable" class="table table-hover livewire-datatable"
                wire:loading.class="datatable-loading">
                <thead>
                    <tr id="livewire-datatable-th">
                        @if($sort == 'columns')
                        <th class="sorting sorting_asc">NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>GENDER</th>
                        <th>COUNTRY</th>
                        <th>STATE</th>
                        <th>CITY</th>
                        <th>ADDRESS</th>
                        <th>ACTION</th>
                        @else
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>GENDER</th>
                        <th>COUNTRY</th>
                        <th>STATE</th>
                        <th>CITY</th>
                        <th>ADDRESS</th>
                        <th>ACTION</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>GENDER</th>
                        <th>COUNTRY</th>
                        <th>STATE</th>
                        <th>CITY</th>
                        <th>ADDRESS</th>
                        <th>ACTION</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($users as $user)
                    <tr id="livewire-datatable-tr-{{ $user->id }}">
                        <td>
                            <div class="first-row">
                                <button data-id="{{ $user->id }}" type="button"
                                    class="btn btn-primary btn-sm extra-columns"
                                    style="display: none">+</button>&nbsp;&nbsp;<span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->email }}</div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->phone }}</div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->gender }}</div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->country }}</div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->state }}</div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->city }}</div>
                        </td>
                        <td>
                            <div class="other-rows">{{ $user->address }}</div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                                    Click Me
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onClick="update_user({{ $user }})">Edit</a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                        onClick="delete_user({{ $user }})">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td align="center" colspan="9">{{ __('No matching records') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 pl-3">
        <div class="float-left form-inline">
            <label>Showing {{ $showing['page'] }} to {{ $showing['set'] }} of {{ $showing['total'] }} entries</label>
        </div>
    </div>
    <div class="col-md-6 pr-3">
        <div class="float-right form-inline">
            {{ $users->onEachSide(2)->links() }}
        </div>
    </div>
</div>
@livewire('datatable-modal')

<script>
Livewire.on('showPage', page => {
    document.getElementById('card-header').innerHTML = 'Livewire Datatable - <b>Page:</b> ' + page;
});
Livewire.on('pdfMake', page => {
    export_pdf();
});

function export_pdf(widths, body) {
    var docDefinition = {
        content: [{
            layout: 'lightHorizontalLines', // optional
            table: {
                // headers are automatically repeated if the table spans over multiple pages
                // you can declare how many rows should be treated as headers
                headerRows: 1,
                widths: widths // ['*', 'auto', 100, '*'],

                body: Array.isArray(body) ? body : []
            }
        }],
        defaultStyle: {
            font: 'Roboto'
        }
    };
    pdfMake.fonts = {
        Roboto: {
            normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf',
            bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Medium.ttf',
            italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Italic.ttf',
            bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-MediumItalic.ttf'
        },
    }

    pdfMake.createPdf(docDefinition).open();
}

function delete_user(user) {
    var obj = JSON.parse(JSON.stringify(user));
    if (confirm('Delete User - ' + obj.name + ' ?')) {
        Livewire.emit('deleteUser', obj.id);
    }
}

function update_user(user) {
    var obj = JSON.parse(JSON.stringify(user));
    Livewire.emit('editUser', obj);
}

function cellVisibility() {
    resetButtonForExtraColumns();
    var tableContainer = document.getElementById('table-container');
    var livewireDatatable = document.getElementById("livewire-datatable");
    var _cells = Array.from(livewireDatatable.rows[0].cells);
    var tableContainerLength = tableContainer.offsetWidth;
    var livewireDatatableLength = 0;
    var livewireDatatableCache = null;
    var hiddenIndx = [];
    var shownIndx = [];
    var allIndx = [];

    if (localStorage.getItem("livewire-datatable-cache") !== null) {
        livewireDatatableCache = localStorage.getItem("livewire-datatable-cache").split(',');
    }

    if (
        livewireDatatableCache !== null
    )
        _cells.map((ele, inx) => {
            var cellIndex = parseInt(inx) + parseInt(1);
            if (ele.style.display === 'none') {
                ele.style.visibility = 'hidden';
                ele.style.removeProperty('display');
                var eleWidth = ele.offsetWidth;
                ele.style.display = 'none';
                ele.style.removeProperty('visibility');
            } else
                var eleWidth = livewireDatatableCache[inx];
            allIndx.push(eleWidth);

            livewireDatatableLength = parseInt(livewireDatatableLength) + parseInt(eleWidth);
            if (livewireDatatableLength >= tableContainerLength) {
                if (cellIndex !== 1) {
                    hiddenIndx.push('th:nth-child(' + cellIndex + ')');
                    hiddenIndx.push('td:nth-child(' + cellIndex + ')');
                }
            } else {
                shownIndx.push('th:nth-child(' + cellIndex + ')');
                shownIndx.push('td:nth-child(' + cellIndex + ')');
            }
        });
    else
        _cells.map((ele, inx) => {
            var cellIndex = parseInt(inx) + parseInt(1);
            if (ele.style.display === 'none') {
                ele.style.visibility = 'hidden';
                ele.style.removeProperty('display');
                var eleWidth = ele.offsetWidth;
                ele.style.display = 'none';
                ele.style.removeProperty('visibility');
            } else
                var eleWidth = ele.offsetWidth;

            allIndx.push(eleWidth);

            livewireDatatableLength = parseInt(livewireDatatableLength) + parseInt(eleWidth);
            if (livewireDatatableLength >= tableContainerLength) {
                if (cellIndex !== 1) {
                    hiddenIndx.push('th:nth-child(' + cellIndex + ')');
                    hiddenIndx.push('td:nth-child(' + cellIndex + ')');
                }
            } else {
                shownIndx.push('th:nth-child(' + cellIndex + ')');
                shownIndx.push('td:nth-child(' + cellIndex + ')');
            }
        });
    tableWidthCache(tableContainerLength, allIndx.join(','));

    if (hiddenIndx.length > 0) {
        showButtonForExtraColumns();
        Array.from(livewireDatatable.querySelectorAll(hiddenIndx.join(', '))).map((ele) => {
            ele.style.display = 'none';
            ele.classList.add('cell-hidden');
        });
    } else
        showButtonForExtraColumns('hide');

    if (shownIndx.length > 0)
        Array.from(livewireDatatable.querySelectorAll(shownIndx.join(', '))).map((ele) => {
            ele.style.removeProperty('display');
            ele.classList.remove('cell-hidden')
        });

}

function tableWidthCache(tableContainerLength, value) {
    if (localStorage.getItem("livewire-datatable-cache") === null) {
        localStorage.setItem("livewire-datatable-length", tableContainerLength);
        localStorage.setItem("livewire-datatable-cache", value);
    } else
    if (tableContainerLength > localStorage.getItem("livewire-datatable-length")) {
        localStorage.removeItem("livewire-datatable-length");
        localStorage.removeItem("livewire-datatable-cache");
        localStorage.setItem("livewire-datatable-length", tableContainerLength);
        localStorage.setItem("livewire-datatable-cache", value);
    }
}

function displayHiddenCells(e) {
    var btn = e.target;
    var id = btn.getAttribute('data-id');
    var tr = document.getElementById('livewire-datatable-tr-' + id);
    var thead_rows = Array.from(document.getElementById('livewire-datatable-th').querySelectorAll('th.cell-hidden'));
    var livewireDatatable = document.getElementById("livewire-datatable");

    if (document.getElementById('extra-row-' + id) === null) {
        btn.innerHTML = '-';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-danger');

        var row = livewireDatatable.insertRow(parseInt(tr.rowIndex) + parseInt(1));
        row.setAttribute('id', 'extra-row-' + id);

        var cell = row.insertCell(0);
        cell.setAttribute('colspan', tr.cells.length);

        var ul = document.createElement("ul");
        ul.classList.add("list-group");

        Array.from(tr.querySelectorAll('td.cell-hidden')).map((ele, inx) => {
            var li = document.createElement("li");
            li.classList.add("list-group-item");

            var b = document.createElement("b");
            b.innerHTML = thead_rows[inx].innerHTML;

            li.appendChild(b);
            li.innerHTML = li.innerHTML + ': ' + ele.innerHTML;

            ul.appendChild(li);
        });

        cell.appendChild(ul);
    } else {
        btn.innerHTML = '+';
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-primary');
        document.getElementById('extra-row-' + id).remove();
    }
}

function showButtonForExtraColumns(type = "show") {
    var extraColumns = Array.from(document.querySelectorAll('.extra-columns'));
    extraColumns.map((ele, inx) => {
        ele.removeEventListener("click", displayHiddenCells);

        if (type === 'show')
            ele.style.removeProperty('display');
        else
            ele.style.display = 'none';

        ele.addEventListener("click", displayHiddenCells);
    });
}

function resetButtonForExtraColumns() {
    var extraColumns = Array.from(document.querySelectorAll('.extra-columns'));
    extraColumns.map((ele, inx) => {
        ele.innerHTML = '+';
        ele.classList.remove('btn-danger');
        ele.classList.add('btn-primary');
        var id = ele.getAttribute('data-id');
        if (document.getElementById('extra-row-' + id) !== null)
            document.getElementById('extra-row-' + id).remove();
    });
}

window.addEventListener('resize', (e) => {
    cellVisibility();
}, true);

document.addEventListener("DOMContentLoaded", () => {
    Livewire.hook('element.updated', (el, component) => {
        console.log(component.el.id);
        if (component.el.id !== 'myModal')
            cellVisibility();
    })
});

if (localStorage.getItem("livewire-datatable-cache") !== null) {
    localStorage.removeItem("livewire-datatable-cache");
    localStorage.removeItem("livewire-datatable-length");
    setTimeout(() => {
        cellVisibility();
    }, 500);
} else
    cellVisibility();
</script>