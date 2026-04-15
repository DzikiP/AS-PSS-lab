<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje zamówienia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex gap-2 flex-wrap">
                <input type="text" id="search" placeholder="Szukaj po ID" class="border px-3 py-1 rounded flex-1">
                
                <select id="status" class="border px-3 py-1 rounded">
                    <option value="">Wszystkie statusy</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto p-6">
                <table class="min-w-full table-fixed border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">
                            <button onclick="changeSort('id')">
                                ID <span id="sort-id"></span>
                            </button>
                        </th>

                        <th class="px-4 py-2 border">
                            Status
                        </th>

                        <th class="px-4 py-2 border">
                            <button onclick="changeSort('created_at')">
                                Data <span id="sort-created_at"></span>
                            </button>
                        </th>

                        <th class="px-4 py-2 border">Produkty</th>
                        <th class="px-4 py-2 border">Suma</th>
                    </tr>
                </thead>

                    <tbody id="orders-table">
                        @include('orders.partials.orders_table')
                    </tbody>
                </table>

                <div class="mt-4" id="pagination">
                    @include('orders.partials.pagination')
                </div>

                <div class="mt-4">
                    <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Złóż nowe zamówienie
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
let state = {
    page: 1,
    search: '',
    status: '',
    sort: 'id',
    direction: 'asc'
};

function debounce(fn, delay = 400) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

function loadOrders() {

    let params = new URLSearchParams({
        page: state.page,
        search: state.search,
        status: state.status,
        sort: state.sort,
        direction: state.direction
    });

    fetch("{{ route('orders.my') }}?" + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('orders-table').innerHTML = data.table;
        document.getElementById('pagination').innerHTML = data.pagination;

        updateSortIcons();
    });
}

function changeSort(column) {
    if (state.sort === column) {
        state.direction = state.direction === 'asc' ? 'desc' : 'asc';
    } else {
        state.sort = column;
        state.direction = 'asc';
    }

    state.page = 1;
    loadOrders();
}

document.addEventListener('click', function(e) {
    if (e.target.closest('#pagination a')) {
        e.preventDefault();

        let url = new URL(e.target.closest('a').href);
        state.page = url.searchParams.get('page');

        loadOrders();
    }
});

const handleFilters = debounce(() => {
    state.search = document.getElementById('search').value;
    state.status = document.getElementById('status').value;
    state.page = 1;

    loadOrders();
}, 400);

document.getElementById('search').addEventListener('input', handleFilters);
document.getElementById('status').addEventListener('change', handleFilters);

function updateSortIcons() {
    document.getElementById('sort-id').innerHTML = '';
    document.getElementById('sort-created_at').innerHTML = '';

    let arrow = state.direction === 'asc' ? '↑' : '↓';
    document.getElementById('sort-' + state.sort).innerHTML = arrow;
}

loadOrders();

</script>

</x-app-layout>