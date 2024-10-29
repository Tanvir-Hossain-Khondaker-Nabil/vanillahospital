
$(function () {


    $("#division_id").on('change',function(e){
        e.preventDefault();
        var url = "{{ route('search.select_district') }}";
        var id = $(this).val();

        var form_data = {
            '_token' : "{{ csrf_token() }}",
            'id' : id
        };
        $.ajax({
            type        : 'POST',
            url         : url, // the url where we want to POST
            data        : form_data,
            dataType    : 'json',
            encode      : true
        }).done(function(data) {
            console.log(data);
            if(data.status == "success")
            {
                var html = "<option value=''>Select City </option>";

                $.each( data.modals, function( key, value ) {
                    html += "<option value='"+key+"'>"+value+"</option>";
                });

                $('#district_id').html(html);
            }else{
//                        console.log(data);
                bootbox.alert("No data Found!! ");
            }
        });
    });


    $(".select-region").on('change',function(e){
        e.preventDefault();
        var url = "{{ route('search.select_region') }}";
        var id = $(this).val();

        var form_data = {
            '_token' : "{{ csrf_token() }}",
            'id' : id
        };
        $.ajax({
            type        : 'POST',
            url         : url, // the url where we want to POST
            data        : form_data,
            dataType    : 'json',
            encode      : true
        }).done(function(data) {

            if(data.status == "success")
            {
                var html = "<option value=''>Select Region </option>";

                $.each( data.modals, function( key, value ) {
                    html += "<option value='"+key+"'>"+value+"</option>";
                });

                $('#region_id').html(html);
            }else{
                bootbox.alert("No data Found!! ");
            }
        });
    });


    $("#district_id").on('change',function(e){
        e.preventDefault();
        var url = "{{ route('search.select_upazilla') }}";
        var id = $(this).val();

        var form_data = {
            '_token' : "{{ csrf_token() }}",
            'id' : id
        };
        $.ajax({
            type        : 'POST',
            url         : url, // the url where we want to POST
            data        : form_data,
            dataType    : 'json',
            encode      : true
        }).done(function(data) {
            if(data.status == "success")
            {
                var html = "<option value=''>Select upazilla </option>";

                $.each( data.modals, function( key, value ) {
                    html += "<option value='"+key+"'>"+value+"</option>";
                });

                $('#upazilla_id').html(html);
            }else{
                bootbox.alert("No data Found!! ");
            }
        });
    });



    $("#upazilla_id").on('change',function(e){
        e.preventDefault();
        var url = "{{ route('search.select_union') }}";
        var id = $(this).val();

        var form_data = {
            '_token' : "{{ csrf_token() }}",
            'id' : id
        };
        $.ajax({
            type        : 'POST',
            url         : url, // the url where we want to POST
            data        : form_data,
            dataType    : 'json',
            encode      : true
        }).done(function(data) {
            if(data.status == "success")
            {
                var html = "<option value=''>Select Union </option>";

                $.each( data.unions, function( key, value ) {
                    html += "<option value='"+key+"'>"+value+"</option>";
                });
                $('#union_id').html(html);
                var html = "<option value=''>Select Area </option>";

                $.each( data.areas, function( key, value ) {
                    html += "<option value='"+key+"'>"+value+"</option>";
                });

                $('#area_id').html(html);
            }else{
                bootbox.alert("No data Found!! ");
            }
        });
    });

});
