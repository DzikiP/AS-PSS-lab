@foreach($products as $product)
    @php
        $qty = $productsData[$product->id]['quantity'] ?? 0;
        $sum = $qty * $product->price;
    @endphp

    <tr class="border-b">
        <td class="py-2">{{ $product->name }}</td>

        <td class="py-2">
            {{ number_format($product->price, 2) }} zł
        </td>

        <td class="py-2">
            @if($mode === 'edit')
                <input type="number"
                       name="products[{{ $product->id }}]"
                       data-price="{{ $product->price }}"
                       value="0"
                       min="0"
                       class="quantity w-20 border rounded px-2 py-1">
            @else
                {{ $qty }}
            @endif
        </td>

        <td class="py-2">
            {{ $product->unit }}
        </td>

        <td class="py-2">
            @if($mode === 'edit')
                <span class="sum">0 zł</span>
            @else
                {{ number_format($sum, 2) }} zł
            @endif
        </td>

        @if($mode === 'readonly')
            <input type="hidden"
                   name="products[{{ $product->id }}]"
                   value="{{ $qty }}">
        @endif
    </tr>
@endforeach