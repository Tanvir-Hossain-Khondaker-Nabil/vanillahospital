<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 6/14/2023
 * Time: 12:16 PM
 */

?>

<!-- Modal -->
<div id="addwarehouse0" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Warehouse Qty Load</h4>


                <input type="hidden" id="warehouse_load_qty_0" value="0">
                <input type="hidden" id="warehouse_load_item_0" value="0">
            </div>
            <div class="modal-body">
                <h5 class="modal-title">Product Name: <span  class=" text-bold" id="item_load_item_0"></span></h5>
                <h5 class="modal-title">Total Qty: <span class=" text-bold" id="item_load_qty_0"></span></h5>
                <table class="table table-striped">
                   <thead>
                       <tr>
                           <th> Warehouse Name</th>
                           <th> Qty</th>
                       </tr>
                   </thead>

                    <tbody id="warehouse_store0">

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" > <button id="multiple-row-0" class="btn btn-info multiple-row" data-target="0" type="button"> Add Row</button></td>
                    </tr>
                    </tfoot>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success confirm_assign" data-target="0" > Confirm Assign </button>
            </div>
        </div>

    </div>
</div>
