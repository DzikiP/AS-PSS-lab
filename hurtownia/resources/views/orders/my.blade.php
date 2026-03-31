<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Moje zamówienia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('orders.my') }}" class="mb-4 flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj po ID" class="border px-3 py-1 rounded flex-1">
                
                <select name="status" onchange="this.form.submit()" class="border px-3 py-1 rounded">
                    <option value="">Wszystkie statusy</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @selected(request('status') == $status->id)>{{ $status->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Filtruj</button>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto p-6">
                <table class="min-w-full table-fixed border border-gray-300">
                    <thead class="bg-gray-100">
                        @php
                            $columns = ['id'=>'ID','status'=>'Status','created_at'=>'Data'];
                            $currentSort = request('sort','created_at');
                            $currentDirection = request('direction','desc');
                        @endphp
                        <tr>
                            @foreach($columns as $key=>$label)
                                <th class="px-4 py-2 border w-1/6">
                                    @if($key==='status')
                                        {{ $label }}
                                    @else
                                        <a href="{{ route('orders.my', array_merge(request()->query(), ['sort'=>$key,'direction'=>($currentSort===$key && $currentDirection==='asc')?'desc':'asc'])) }}">
                                            {{ $label }}
                                            @if($currentSort === $key)
                                                {{ $currentDirection==='asc'?'↑':'↓' }}
                                            @endif
                                        </a>
                                    @endif
                                </th>
                            @endforeach
                            <th class="px-4 py-2 border w-1/4">Produkty</th>
                            <th class="px-4 py-2 border w-1/6">Suma</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-4 py-2 border truncate" title="{{ $order->id }}">{{ $order->id }}</td>
                                <td class="px-4 py-2 border truncate" title="{{ $order->status->name }}">{{ $order->status->name }}</td>
                                <td class="px-4 py-2 border truncate" title="{{ $order->created_at }}">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2 border">
                                    <ul class="list-disc pl-5">
                                        @foreach($order->products as $product)
                                            <li class="truncate" title="{{ $product->name }}">{{ $product->name }} ({{ $product->pivot->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border truncate">{{ number_format($order->total_price,2) }} zł</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>

                <div class="mt-4">
                    <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Złóż nowe zamówienie
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
