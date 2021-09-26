<div class="row">
    <div class="col-md-6 pl-3 mb-2">
        <div class="btn-group">
            <button type="button" class="btn btn-dark" wire:click="pdf_make" wire:loading.attr="disabled">
                <span wire:loading wire:target="pdf_make"><span
                        class="spinner-border spinner-border-sm"></span>&nbsp;Loading...</span>
                <span wire:loading.remove wire:target="pdf_make">PDF</span>
            </button>
            <button type="button" class="btn btn-dark" wire:click="table_to_excel" wire:loading.attr="disabled">
                <span wire:loading wire:target="table_to_excel"><span
                        class="spinner-border spinner-border-sm"></span>&nbsp;Loading...</span>
                <span wire:loading.remove wire:target="table_to_excel">EXCEL</span>
            </button>
            <button type="button" class="btn btn-dark" wire:click="export_to_csv" wire:loading.attr="disabled">
                <span wire:loading wire:target="export_to_csv"><span
                        class="spinner-border spinner-border-sm"></span>&nbsp;Loading...</span>
                <span wire:loading.remove wire:target="export_to_csv">CSV</span>
            </button>
            @if ($total > $maxP)
            @if (count(is_countable($users) ? $users : []) < 1 && strlen($search)> 0)
                <button type="button" class="btn btn-primary" wire:click="gotoPage(1)" wire:loading.attr="disabled">
                    <span>Search From Page 1</span>
                </button>
                @elseif ($page > 1 && $page < $last_page) <button type="button" class="btn btn-danger"
                    wire:click="gotoPage(1)" wire:loading.attr="disabled">
                    <span>First Page</span>
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="gotoPage({{ $last_page }})"
                        wire:loading.attr="disabled">
                        <span>Last Page</span>
                    </button>
                    @elseif ($page >= $last_page)
                    <button type="button" class="btn btn-danger" wire:click="gotoPage(1)" wire:loading.attr="disabled">
                        <span>First Page</span>
                    </button>
                    @else
                    <button type="button" class="btn btn-danger" wire:click="gotoPage({{ $last_page }})"
                        wire:loading.attr="disabled">
                        <span>Last Page</span>
                    </button>
                    @endif
                    @else
                    @if (count(is_countable($users) ? $users : []) < 1 && strlen($search)> 0)
                        <button type="button" class="btn btn-primary" wire:click="gotoPage(1)"
                            wire:loading.attr="disabled">
                            <span>Search From Page 1</span>
                        </button>
                        @elseif (count(is_countable($users) ? $users : []) < 1 && $page> 1)
                            <button type="button" class="btn btn-primary" wire:click="gotoPage(1)"
                                wire:loading.attr="disabled">
                                <span>Jump To Page 1</span>
                            </button>
                            @endif
                            @endif
        </div>
    </div>
    <div class="col-md-6 pl-3 mb-2">
    </div>
    <div class="col-md-6 pl-3">
        <div class="float-left form-inline">
            <label>Show</label>
            <select class="form-control mr-1 ml-1 page-input" wire:model="fetch">
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
            <input type="text" wire:model.debounce.1000ms="search" class="form-control ml-1 search-input"
                placeholder="Search" />
        </div>
    </div>
    <div class="col-md-12 mt-3 mb-3">
        <div class="w-100">
            @if (session()->has('fail'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Failed!</strong> {{ session('fail') }}
            </div>
            @endif
        </div>
        <div class="table-responsive">
            <table id="livewire-datatable" class="table table-hover livewire-datatable"
                wire:target="fetch, previousPage, nextPage, gotoPage, search, resort, delete_user"
                wire:loading.class="datatable-loading">
                <thead>
                    <tr id="livewire-datatable-th">
                        @if($sort == 'columns')
                        @foreach ($columns as $column)
                        @if ($order_by[0] == $column['sort'])
                        @if ($order_by[1])
                        <th class="th_hover" wire:click="resort('{{ $column['sort'] }}')">
                            <div class="sorting sorting_asc other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @else
                        <th class="th_hover" wire:click="resort('{{ $column['sort'] }}')">
                            <div class="sorting sorting_desc other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @endif
                        @else
                        <th class="th_hover" wire:click="resort('{{ $column['sort'] }}')">
                            <div class="sorting other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @endif
                        @endforeach
                        @else
                        @foreach ($columns as $column)
                        <th>
                            <div class="other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @endforeach
                        @endif
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        @if($sort == 'columns')
                        @foreach ($columns as $column)
                        @if ($order_by[0] == $column['sort'])
                        @if ($order_by[1])
                        <th>
                            <div class="sorting sorting_asc other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @else
                        <th>
                            <div class="sorting sorting_desc other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @endif
                        @else
                        <th>
                            <div class="sorting other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @endif
                        @endforeach
                        @else
                        @foreach ($columns as $column)
                        <th>
                            <div class="other-rows">{{ strtoupper($column['name']) }}</div>
                        </th>
                        @endforeach
                        @endif
                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($users as $user)
                    <tr id="livewire-datatable-tr-{{ $user->id }}" data-id="{{ $user->id }}">
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
                                        onClick="update_user({{ json_encode($user) }})">Edit</a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                        onClick="delete_user({{ json_encode($user )}})">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td align="center" colspan="9">{{ $load_state }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 pl-3">
        <div class="float-left form-inline">
            <label>Showing {{ number_format($current_page) }} to {{ number_format($set) }} of
                {{ number_format($total) }} entries</label>
        </div>
    </div>
    <div class="col-md-6 pr-3">
        <div class="float-right form-inline">
            @if(count(is_countable($users) ? $users : []) > 0)
            {{ $users->onEachSide(2)->links() }}
            @endif
        </div>
    </div>
</div>

@livewire('datatable-modal' , ['inputs' => $columns])

<script>
function export_pdf(widths, body) {
    var docDefinition = {
        content: [{
            layout: 'lightHorizontalLines', // optional
            table: {
                // headers are automatically repeated if the table spans over multiple pages
                // you can declare how many rows should be treated as headers
                headerRows: 1,
                widths: widths, // ['*', 'auto', 100, '*'],

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

    pdfMake.createPdf(docDefinition).download();
}

function export_excel(body) {
    var tableToExcel = new TableToExcel();
    tableToExcel.render(body, [{
        text: "Livewire-datatable",
        bg: "#333",
        color: "#fff"
    }]);
}

function export_csv(body) {
    var data = [{
            name: 'Test 1',
            age: 13,
            average: 8.2,
            approved: true,
            description: "using 'Content here, content here' "
        },
        {
            name: 'Test 2',
            age: 11,
            average: 8.2,
            approved: true,
            description: "using 'Content here, content here' "
        },
        {
            name: 'Test 4',
            age: 10,
            average: 8.2,
            approved: true,
            description: "using 'Content here, content here' "
        },
    ];

    const options = {
        fieldSeparator: ',',
        quoteStrings: '"',
        decimalSeparator: '.',
        showLabels: true,
        showTitle: true,
        title: 'Livewire-datatable',
        useTextFile: false,
        useBom: true,
        useKeysAsHeaders: true,
        // headers: ['Column 1', 'Column 2', etc...] <-- Won't work with useKeysAsHeaders present!
    };

    const csvExporter = new ExportToCsv(options);

    csvExporter.generateCsv(JSON.parse(body));
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
    var livewireDatatable = document.getElementById("livewire-datatable");
    if (livewireDatatable !== null) {
        var tableContainer = document.getElementById('table-container');
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
            Array.from(livewireDatatable.querySelectorAll(hiddenIndx.join(', '))).map((ele) => {
                ele.style.display = 'none';
                ele.classList.add('cell-hidden');
            });
            if (document.querySelector('tr.has-extra-row') === null)
                showButtonForExtraColumns();
            else
                setTimeout(() => {
                    autoAdjustHiddenCells();
                }, 500);
            closeRowListeners();
        } else
            showButtonForExtraColumns('hide');

        if (shownIndx.length > 0)
            Array.from(livewireDatatable.querySelectorAll(shownIndx.join(', '))).map((ele) => {
                ele.style.removeProperty('display');
                ele.classList.remove('cell-hidden')
            });
    }
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
    var thead_rows = Array.from(document.getElementById('livewire-datatable-th').querySelectorAll(
        'th.cell-hidden'));
    var livewireDatatable = document.getElementById("livewire-datatable");

    if (document.getElementById('extra-row-' + id) === null) {
        btn.innerHTML = '-';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-danger');
        tr.classList.add('has-extra-row')

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
            if (thead_rows[inx].querySelector('.other-rows'))
                b.innerHTML = thead_rows[inx].querySelector('.other-rows').innerHTML + ': ';
            else
                b.innerHTML = thead_rows[inx].innerHTML + ': ';

            li.appendChild(b);
            li.innerHTML = li.innerHTML + ele.innerHTML;

            ul.appendChild(li);
        });

        cell.appendChild(ul);
    } else {
        btn.innerHTML = '+';
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-primary');
        tr.classList.remove('has-extra-row')
        document.getElementById('extra-row-' + id).remove();
    }
}

function autoAdjustHiddenCells() {
    Array.from(document.querySelectorAll('tr.has-extra-row')).map((element, index) => {
        var id = element.getAttribute('data-id');
        var element = document.getElementById('livewire-datatable-tr-' + id);
        var thead_rows = Array.from(document.getElementById('livewire-datatable-th')
            .querySelectorAll(
                'th.cell-hidden'));

        var row = document.getElementById('extra-row-' + id);
        var cell = row.querySelector('td:first-child');
        cell.setAttribute('colspan', element.cells.length);
        cell.innerHTML = "";

        var ul = document.createElement("ul");
        ul.classList.add("list-group");

        Array.from(element.querySelectorAll('td.cell-hidden')).map((ele, inx) => {
            var li = document.createElement("li");
            li.classList.add("list-group-item");

            var b = document.createElement("b");
            if (thead_rows[inx].querySelector('.other-rows'))
                b.innerHTML = thead_rows[inx].querySelector('.other-rows').innerHTML + ': ';
            else
                b.innerHTML = thead_rows[inx].innerHTML + ': ';

            li.appendChild(b);
            li.innerHTML = li.innerHTML + ele.innerHTML;

            ul.appendChild(li);
        });

        cell.appendChild(ul);
    });
}

function closeRowListeners() {
    Array.from(document.querySelectorAll('.paginator-anchors')).map((ele) => {
        ele.removeEventListener("click", closeAllExtraRows);
        ele.addEventListener('click', closeAllExtraRows);
    });
    var search_input = document.querySelector('.search-input');
    search_input.removeEventListener("keyup", closeAllExtraRows);
    search_input.addEventListener('keyup', closeAllExtraRows);

    var page_input = document.querySelector('.page-input');
    page_input.removeEventListener("change", closeAllExtraRows);
    page_input.addEventListener('change', closeAllExtraRows);
}

function closeAllExtraRows(e) {
    Array.from(document.querySelectorAll('.extra-columns')).map((element, index) => {
        var id = element.getAttribute('data-id');
        var tr = document.getElementById('livewire-datatable-tr-' + id);

        element.innerHTML = '+';
        element.classList.remove('btn-danger');
        element.classList.add('btn-primary');
        tr.classList.remove('has-extra-row')
        if (document.getElementById('extra-row-' + id) !== null)
            document.getElementById('extra-row-' + id).remove();
    });
}

function showButtonForExtraColumns(type = "show") {
    Array.from(document.querySelectorAll('.extra-columns')).map((ele, inx) => {
        ele.removeEventListener("click", displayHiddenCells);

        if (type === 'show')
            ele.style.removeProperty('display');
        else
            ele.style.display = 'none';

        ele.addEventListener("click", displayHiddenCells);
    });
}

function init_responsive_table() {
    if (localStorage.getItem("livewire-datatable-cache") !== null) {
        localStorage.removeItem("livewire-datatable-cache");
        localStorage.removeItem("livewire-datatable-length");
    }
    cellVisibility();
}

document.addEventListener("DOMContentLoaded", () => {
    Livewire.on('showPage', page => {
        document.getElementById('card-header').innerHTML =
            'Livewire Datatable - <b>Page:</b> ' + page;
    });
    Livewire.on('docMake', params => {
        if (params['type'] === 'pdf') {
            var widths = [
                'auto', 'auto', 'auto', 'auto',
                'auto', 'auto', 'auto', 'auto'
            ];
            export_pdf(widths, params['body']);
        } else if (params['type'] === 'excel')
            export_excel(params['body']);
        else if (params['type'] === 'csv')
            export_csv(params['body']);
    });
    Livewire.on('cellVisibility', params => {
        cellVisibility();
    });
    init_responsive_table();
    window.addEventListener('popstate', function(event) {
        cellVisibility();
    }, false);
    window.addEventListener('resize', (e) => {
        cellVisibility();
    }, true);
});
</script>