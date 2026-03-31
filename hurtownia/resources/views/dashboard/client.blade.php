<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h2 class="text-xl font-bold">Witaj, {{ auth()->user()->username }}</h2>
                <p class="mt-2 text-gray-700">Sprawdź swoje zamówienia i dostępne produkty.</p>

                <div class="mt-4">
                    <a href="{{ route('orders.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-md font-semibold hover:bg-green-700">
                        Złóż nowe zamówienie
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold">Twoje zamówienia</h3>
                    <p class="mt-2 text-2xl font-bold text-blue-600">{{ $myOrdersCount }}</p>
                    <a href="{{ route('orders.my') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Zobacz zamówienia
                    </a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold">Zamówienia w realizacji</h3>
                    <p class="mt-2 text-2xl font-bold text-yellow-600">{{ $pendingOrdersCount }}</p>
                    <a href="{{ route('orders.my') }}" class="mt-4 inline-block px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        Sprawdź status
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h2 class="text-xl font-bold mb-4">Dostępne produkty</h2>

                @if($products->isEmpty())
                    <p>Brak produktów w magazynie.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Nazwa</th>
                                <th class="px-4 py-2 border">Opis</th>
                                <th class="px-4 py-2 border">Cena</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $product->name }}</td>
                                    <td class="px-4 py-2 border">{{ $product->description }}</td>
                                    <td class="px-4 py-2 border">{{ $product->price }} zł</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
