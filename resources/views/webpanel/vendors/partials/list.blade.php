<?php $i = 1; ?>
@foreach($vendors as $vendor)
    <tr class="deleteBox">
        <td>{{ $vendor->title}}</td>
        <td>{{ $vendor->minimum_purchase_amount }}</td>
        <td>{{ $vendor->minimum_weight_amount }}</td>
        <td>{{ $vendor->minimum_case_quantity }}</td>

    </tr>
    <?php $i++; ?>
@endforeach