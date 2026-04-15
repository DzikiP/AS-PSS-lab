<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nowe zamówienie
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 shadow rounded overflow-x-auto">

            <div class="mb-4 flex gap-2">
                <input type="text"
                       id="search"
                       placeholder="Szukaj produktu..."
                       class="border rounded px-3 py-1 flex-1">
            </div>

            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf

                <table class="w-full border-collapse table-fixed">
                    <thead>
                        <tr class="border-b">
                            <th class="w-1/2 text-left py-2">Produkt</th>
                            <th class="w-1/6 text-left py-2">Cena</th>
                            <th class="w-1/6 text-left py-2">Ilość</th>
                            <th class="w-1/6 text-left py-2">J. m.</th>
                            <th class="w-1/6 text-left py-2">Suma</th>
                        </tr>
                    </thead>

                    <tbody id="products-container">
                        <x-order-products
                            :products="$products"
                            mode="edit"
                        />
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right font-bold py-2">
                                Łącznie:
                            </td>
                            <td id="totalPrice" class="font-bold py-2">
                                0 zł
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Podsumuj zamówienie
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        let state = {
            search: ''
        };

        function debounce(fn, delay = 300) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            };
        }

        function loadProducts() {
            fetch(`/orders/create?search=${state.search}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('products-container').innerHTML = data.products;

                rebindQuantities();
            });
        }

        const handleSearch = debounce(() => {
            state.search = document.getElementById('search').value;
            loadProducts();
        }, 300);

        document.getElementById('search')
            .addEventListener('input', handleSearch);

        function updateSums() {
            const inputs = document.querySelectorAll('.quantity');
            let total = 0;

            inputs.forEach(input => {
                const price = parseFloat(input.dataset.price);
                const qty = parseInt(input.value) || 0;

                const sum = price * qty;

                input.closest('tr')
                    .querySelector('.sum')
                    .innerText = sum.toFixed(2) + ' zł';

                total += sum;
            });

            document.getElementById('totalPrice')
                .innerText = total.toFixed(2) + ' zł';
        }

        function rebindQuantities() {
            const inputs = document.querySelectorAll('.quantity');

            inputs.forEach(input => {
                input.addEventListener('input', updateSums);
            });

            updateSums();
        }

        rebindQuantities();
    </script>
</x-app-layout>