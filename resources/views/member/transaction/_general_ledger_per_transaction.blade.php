<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/1/2019
 * Time: 10:30 AM
 */
?>


<div id="general-ledger" class="modal fadeIn" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h3 class="modal-title"> View the General Ledger Postings for this Payment</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive table-striped">
                    <thead class="text-center">
                        <tr>
                            <th colspan="4">General Ledger Transaction Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Transaction Code</th>
                            <th>Transaction Date</th>
                            <th colspan="2"> Transaction From</th>
                            <th colspan="2"> Entry By</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td colspan="2"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <th>Jounral Date</th>
                            <th>Account Code</th>
                            <th>Account Name</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <!-- Date range picker -->
    <script type="text/javascript">
        $(window).on('load',function(){
            $('#general-ledger').modal('show');
        });
    </script>
@endpush
