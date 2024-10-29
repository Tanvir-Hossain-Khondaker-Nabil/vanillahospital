<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 11:40 AM
 */
?>

<!-- jQuery 3 -->
<script src="{{ asset('public/adminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('public/adminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!-- DataTables -->
<script src="{{ asset('public/adminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>


<!-- daterangepicker -->
<script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
</script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('public/adminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- SlimScroll -->
<script src="{{ asset('public/adminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('public/adminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" type="text/javascript"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

<script>
    bootbox.setDefaults({
        locale: "{{ Config::get('app.locale') }}",
    });
    bootbox.addLocale("bn",{
    OK : 'OK',
    CANCEL : 'বাতিল করুন',
    CONFIRM : 'নিশ্চিত করুন'
});
</script>


<!-- public/adminLTE App -->
<script src="{{ asset('public/adminLTE/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>


<!-- public/adminLTE for demo purposes -->
<script src="{{ asset('public/adminLTE/dist/js/demo.js') }}"></script>
<script src="{{ asset('public/adminLTE/jquery.printPage.js') }}"></script>
<script src="{{ asset('public/adminLTE/scoll_tabs.js') }}"></script>


@stack('scripts')


<script>
    mouseWheelOff();

    var pos_url = "{{ route('member.sales.pos_create') }}";
    var stock_url = "{{ route('member.report.stocks') }}";

    document.addEventListener('keydown', (e) => {

        if (e.which == 78 && e.altKey) {

            e.preventDefault();
            // Add your code here
            window.open(pos_url, '_blank');
        }
        if (e.which == 83 && e.altKey) {

            e.preventDefault();
            // Add your code here
            window.open(stock_url, "_self");
        }
    });

    $('input[type=number]').attr('min', 0);


    function mouseWheelOff() {
        $('input[type=number]').on('mousewheel', function(e) {
            $(e.target).blur();
        });

        $('.input-number').keypress(function(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
                return false;
            return true;
        });
    }

    function emptyElement(element) {
        var children = element.childNodes;
        while (children.length > 0) {
            var child = children[children.length - 1]; // children[0];
            element.removeChild(child); // or just: child.remove()
        }
    }

    function getImagePreview(e) {
        // console.log('ooooo',e.nextElementSibling.nextElementSibling)
        var element = e.nextElementSibling.nextElementSibling
        emptyElement(element);

        if (e.files.length > 0) {
            var fileLen = e.files.length;

            for (var i = 0; i < fileLen; i++) {
                var src = URL.createObjectURL(e.files[i]);

                if(e.files[i].type == "image/jpeg" || e.files[i].type == "image/png")
                {
                    e.nextElementSibling.value = src;
                    var imageDiv = e.nextElementSibling.nextElementSibling;
                    var newImage = document.createElement('img');
                    newImage.src = src;
                    newImage.width = '100';
                    newImage.height = '100';
                    newImage.style = "margin-right: '10px';margin-top: 5px;width: 100px !important";
                    imageDiv.appendChild(newImage);
                }
            }

        }
    }

    function company() {
        var html = '';
        @if (isset(Auth::user()->company))

            var logo = "{{ emptyCheck(Auth::user()->company->company_logo_path) }}";
            var company = "{{ emptyCheck(Auth::user()->company->company_name) }}";
            var address =
                "{{ emptyCheck(str_replace(["\n", "\r"], ["\\n", "\\r"], Auth::user()->company->address)) }}";
            var city = "{{ emptyCheck(Auth::user()->company->city) }}";
            var country = "{{ emptyCheck(Auth::user()->company->country->countryName) }}";
            var phone = "{{ emptyCheck(Auth::user()->company->country->phone) }}";
            var email = "{{ emptyCheck(Auth::user()->company->country->email) }}";

            html = "<div> <div style='text-align: right !important; float: left; width:25%' >" +
                "<img width='130px !important' src='" + logo + "' alt='" + company +
                "'/> </div> <div  style='text-align: center !important; float: left; padding-left: 13%; width:50%' >" +
                "<h3>" + company + "</h3>" +
                "<p>" + address + " <br/> " + city + ", " + country + "</p>" +
                "<p>" + phone + "</p>" +
                "<p>" + email + "</p>" +
                "</div></div>";
        @endif

        return html;
    }

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Oops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big (2M max).'
        }
    });

    $('#print').click(function() {
        var header = company();
        var printContents = $('#custom-print').html();
        var originalContents = $('body').html();

        $('body').html(header + printContents);
        window.print();
        $('body').html(originalContents);
    });

    $('.sidebar-toggle').click(function() {
        $.ajax({
            url: "{{ route('left_sidebar_toggle') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {

            }
        });
    });


    var $body = $('body');

    $body.on('click', '.delete-confirm', function() {
        var $this = $(this);
        bootbox.confirm("{{__('common.are_you_sure')}} ", function(result) {

            if (result) {
                $.ajax({
                    url: $this.data('target'),
                    type: "DELETE",
                    dataType: "json",
                    success: function(data) {

                        var originalURL = $this.data('target');

                        // Use regular expression to remove the trailing segment
                        var convertedURL = originalURL.replace(/\/\d+$/, '');

                        // Update the content of the <span> element with the converted URL
                            console.log(data);
                            if(data.data.url){
                            var convertedURL = data.data.url.replace(/\/\d+$/, '');
                            bootbox.alert("{{__('common.delete_successfully_completed')}}",
                                function() {
                                    location.replace(convertedURL);
                                });

                        }else{
                            bootbox.alert("{{__('common.delete_successfully_completed')}}",
                                function() {
                                    location.replace(convertedURL);
                                });

                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        var response = xhr.responseJSON;
                        var deleteMessage = "";

                        if (typeof response.data.message !== 'undefined')
                            deleteMessage = response.data.message

                        if (deleteMessage == "" || deleteMessage == undefined)
                            bootbox.alert("{{__('common.oop_soory_response_denied')}}");
                        else
                            bootbox.alert(deleteMessage);
                    }
                });
            } else {
                bootbox.alert("{{__('common.oop_soory_response_denied')}}");
            }
        });
    });



    $body.on('click', '.process-confirm', function() {
        var $this = $(this);
        bootbox.confirm("Are you sure? It will take more time to process complete", function(result) {

            if (result) {
                bootbox.alert("Processing Start ..............");
            } else {
                bootbox.alert("{{__('common.oop_soory_response_denied')}}");
            }
        });
    });


    $('#btn-print, .btn-print').printPage();

    $('.content').click(function(e) {
        // $(".dt-button-collection").css('display','none');
    });



    let expireAlertDone = {{ session('expired_days') ? 1 : 0 }};
    let expireTxt = "Your Access Expired Soon. {{ session('expired_days') }} days Remaining. Please Pay Soon.";

    if (expireAlertDone === 1) {
        bootbox.alert(expireTxt);
    }



    function showLeadModal() {
        // alert('kk')
        $('#showLeadModal').modal('show')
        // $('#showLabelModal').modal('show');
    }

    let leadStatus = '';
    function leadStatusChange(el) {
        leadStatus = $(el).val();
    }


    function changeStatus(el, type = '') {

        let id = $(el).data('id');
        let presentLeadStatus = $(el).data('leadStatus');
        let status = $(el).val();
        let url = '';
        let data = '';
        if (type) {


            url = "{{ route('member.users.editTaskStatus') }}";
            data = {
                'id': id,
                'status': status,
            }

            ChangeTaskLeadStatus();
        } else {

            $('#LeadStatusChangeModal').modal('show');

            $('#save-lead-comment').on('click',function(){

              let comment =  CKEDITOR.instances['lead_comment'].getData();

            url = "{{ route('member.editLeadStatus') }}";
            data = {
                'lead_id': id,
                'lead_status': leadStatus,
                'comment': comment,
            }
                ChangeTaskLeadStatus();
            });

        }

        // return;
        function ChangeTaskLeadStatus() {
            bootbox.confirm("Are you sure? ", function(result) {

                if (result) {
                    $.ajax({
                        url: url,
                        method: 'get',
                        data: data,
                        success: function(data) {
                            if (data.status == 200) {
                                bootbox.alert("Status updated successfully",
                                    function() {
                                        location.reload();
                                    });
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            var response = xhr.responseJSON;
                            let deleteMessage = response.data.message

                            if (deleteMessage == "" || deleteMessage == undefined)
                                bootbox.alert("OOP! Sorry Response Denied");
                            else
                                bootbox.alert(deleteMessage);
                        }
                    });
                } else {
                    bootbox.alert("OOP! Sorry Response Denied");
                }
            });
        }
    }
        function showSateByCountryId(e,type='') {
        // console.log('type is', type)
            //  alert(e.value);
            let state = '<option value=""> Please Select</option>';
            let id = e.value;
            $.ajax({
                type: "get",
                url: "{{ route('member.employee.showStateByCountryId') }}",
                data: {
                    'country_id': id,
                },
                success: function(data) {

                    if (data.data.length > 0) {
                        $.each(data.data, function(key, value) {

                            state += `<option value="${value.id }">
                        ${value.name}</option>`

                        });


                    }
                    if(type == ''){
                        $('#division_id').html(state);
                    }else{
                        console.log('state',state)
                        $('.division_id').html(state);
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function showCityByStateId(e,type='') {
            //  alert(e.value);

            let city = '<option value=""> Please Select</option>';
            let id = e.value;
            $.ajax({
                type: "get",
                url: "{{ route('member.employee.showCityByStateId') }}",
                data: {
                    'division_id': id,
                },
                success: function(data) {
                    // console.log('response is', data);

                    if (data.data.length > 0) {
                        $.each(data.data, function(key, value) {

                            city += `<option value="${value.id }">
                        ${value.name}</option>`

                        });

                    }
                    if(type == ''){
                        $('#district_id').html(city);
                    }else{

                        $('.district_id').html(city);
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
         function showAreaByCityId(e,type='') {
            //  alert(e.value);

            let area = '<option value=""> Please Select</option>';
            let id = e.value;
            $.ajax({
                type: "get",
                url: "{{ route('member.employee.showAreaByCityId') }}",
                data: {
                    'district_id': id,
                },
                success: function(data) {
                    // console.log('response is', data);

                    if (data.data.length > 0) {
                        $.each(data.data, function(key, value) {

                            area += `<option value="${value.id }">
                        ${value.name}</option>`

                        });
                    }
                    if(type == ''){
                        $('#area_id').html(area);
                    }else{

                        $('.area_id').html(area);
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

         function selectDepartment(e) {

            let list = '<option value=""> Please Select</option>';
            let id = e.value;
            $.ajax({
                type: "get",
                url: "{{ route('member.employee.showDesignationByDeptId') }}",
                data: {
                    'department_id': id,
                },
                success: function(data) {
                    if (data.data.length > 0) {
                        $.each(data.data, function(key, value) {

                            list += `<option value="${value.id }">
                        ${value.name}</option>`

                        });
                    }
                    $('#designation_id').html(list);
                    $('#designation_id').select2();

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
</script>
