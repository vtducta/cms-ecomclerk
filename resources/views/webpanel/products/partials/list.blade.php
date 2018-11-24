<?php $i = 1; ?>
@foreach($products as $k => $product)
    <tr class="deleteBox">
        <td>
            <a href="{{ sysRoute('products.show', encryptIt($product->id)) }}">
                {{ $product->amazon_title }}
            </a>
        </td>
        <td>{{ $product->asin }}</td>
        <td>{{ $product->number_of_packs }}</td>
        <td>{{ $product->cost }}</td>
        <td>{{ $product->amazon_buy_box_price }}</td>
        <td>{{ number_format($product->net_after_fba,2) }}</td>
        <td>{{ number_format($product->gross_profit_fba,2) }}</td>
        <td>{{ number_format($product->gross_roi,2) }}%</td>
    </tr>
    <?php $i++; ?>
@endforeach