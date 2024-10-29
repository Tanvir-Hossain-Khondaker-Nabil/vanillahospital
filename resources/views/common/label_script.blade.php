<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/12/2023
 * Time: 5:33 PM
 */
?>

@push('scripts')
    <script type="text/javascript">

        let hit_label = ''

        function manageLabel(label) {
            hit_label = label;
            $('#label-type').val(hit_label)
            // alert(label)
            $('#showLabelModal').modal('show');
            showLabel();
        }

        $('.color-tag').click(function() {
            // alert('ooo')
            // alert($(this).attr('data-color'));
            let bg_color = $(this).attr('data-color');
            $('#custom-color').val(bg_color);
            $('#bg-color').val(bg_color);

        })

        // $('.label-edit-delete').on("click", function(){
        //     alert('ooo')
        //     // alert($(this).attr('data-color'));
        //     let id = $(this).attr('data-id');
        //     let label_name = $(this).text();

        //     console.log('check',id,label_name)

        // })

        let selected_id = '';

        function selectEditDeleteLabel(el) {
            selected_id = $(el).attr('data-id');

            // console.log('check',id,label_name)
            $('#label-name').val($(el).text());
            $('#delete-label-button').removeClass('d-none');

        }

        function deleteLabel() {
            //   alert(selected_id)
            $.ajax({
                url: "{{ route('member.destroyLabel') }}",
                method: 'put',
                data: {
                    'id': selected_id,
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('#label-name').val('')
                        selected_id = ''
                        showLabel();
                    }
                }
            })
        }

        $("#label-name").on("input", function(e) {

            if ($(this).val().length > 0) {
                $("#reqire-text").css({
                    "display": "none"
                });
            } else {
                $('#reqire-text').removeAttr("style");
            }
        });



        function saveLabel() {
            let label_type = $('#label-type').val();
            let bg_color = $('#bg-color').val();
            let name = $('#label-name').val();
            console.log('member/task/')
            // return
            if (name.length > 0) {

                let url = "";
                let type = "";
                let data = "";

                if (selected_id) {

                    $.ajax({
                        url: "{{ route('member.updateLabel') }}",
                        method: 'get',
                        data: {
                            'id': selected_id,
                            'name': name,
                            'bg_color': bg_color,
                        },
                        success: function(data) {
                            if (data.status == 200) {
                                $('#label-name').val('')
                                $('#delete-label-button').addClass('d-none');
                                selected_id = ''
                                showLabel();
                            }
                        }
                    })
                } else {

                    $.ajax({
                        url: "{{ route('member.label.store') }}",
                        method: 'POST',
                        data: {
                            'name': name,
                            'bg_color': bg_color,
                            'label_type': label_type,
                        },
                        success: function(data) {
                            if (data.status == 200) {
                                $('#label-name').val('')
                                showLabel();
                            }
                        }
                    })
                    // console.log(url,url2)
                }


            } else {
                $('#reqire-text').removeAttr("style");
            }

        }

        function showLabel() {
            let label_div = '';
            $.ajax({
                url: "{{ route('member.label.index') }}",
                method: 'get',
                data: {
                    'label_type': hit_label,
                },
                success: function(data) {
                    if (data.status == 200) {
                        $.each(data.data, function(index, val) {
                            label_div += `<span data-id="${val.id}"
                                data-color="#29c2c2" onclick="selectEditDeleteLabel(this)" class="mt-2 badge large p-2 mr-1 clickable"
                                style="background-color: ${val.bg_color}">${val.name}
                            </span>`;
                        })
                    }
                    $('#show-label-div').html(label_div);
                }
            })
        }
    </script>
    @endpush
