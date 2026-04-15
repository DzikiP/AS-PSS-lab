@foreach($orders as $order)
<tr>
    <td class="px-4 py-2 border">{{ $order->id }}</td>
    <td class="px-4 py-2 border">{{ $order->status->name }}</td>
    <td class="px-4 py-2 border">{{ $order->created_at->format('Y-m-d H:i') }}</td>
    <td class="px-4 py-2 border">
        <ul class="list-disc pl-5">
            @foreach($order->products as $product)
                <li>{{ $product->name }} ({{ $product->pivot->quantity }})</li>
            @endforeach
        </ul>
    </td>
    <td class="px-4 py-2 border">{{ number_format($order->total_price,2) }} zł</td>
</tr>
@endforeach