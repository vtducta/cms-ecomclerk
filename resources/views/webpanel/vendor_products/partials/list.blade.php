<?php $i = 1; ?>
@foreach($vendorProducts as $vendorProduct)
    <tr class="deleteBox">
        <td>{{ $vendorProduct->product_title}}</td>
        <td>{{ $vendorProduct->vendor_item_number }}</td>
        <td>{{ $vendorProduct->upc }}</td>
        <td>{{ $vendorProduct->vendor_cost }}</td>
        <td>{{ $vendorProduct->case_quantity }}</td>
        <td>{{ $vendorProduct->weight }}</td>
        <td>{{ $vendorProduct->category }}</td>

    </tr>
    <?php $i++; ?>
@endforeach