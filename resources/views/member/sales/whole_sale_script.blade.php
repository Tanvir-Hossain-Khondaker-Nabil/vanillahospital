<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/11/2023
 * Time: 4:11 PM
 */

?>


@push('scripts')

<script type="text/javascript">


    $(function () {

        function total_price_calculate()
        {
            price_calculate();

            var total_bill = $('#sub_total').val();
            // var shipping_charge = parseFloat($('#shipping_charge').val());
            var labor_cost = parseFloat($('#labor_cost').val());
            var transport_cost = parseFloat($('#transport_cost').val());
            var discount_type = $('#discount_type').val();
            var discount = $('#discount').val();
            var discount_amount = 0;

            var paid_amount = $('#paid_amount').val();

            paid_amount = paid_amount == '' ? 0 : paid_amount;

            total_bill = parseFloat(total_bill);
            paid_amount = parseFloat(paid_amount);
            discount = parseFloat(discount);

            var total_amount = total_bill+labor_cost+transport_cost;


            if(discount_type=="fixed")
                discount_amount = discount;
            else
                discount_amount = total_bill*discount/100;

            var amount_to_pay = total_amount-discount_amount;
            var due = amount_to_pay-paid_amount;

            if(due>0)
                $('#customer_id').attr('required', true);
            else
                $('#customer_id').attr('required', false);

            $('#total_amount').val(total_amount);
            $('#amount_to_pay').val(amount_to_pay);
            $('#due_amount').val(due);
        }
    });


</script>

@endpush
