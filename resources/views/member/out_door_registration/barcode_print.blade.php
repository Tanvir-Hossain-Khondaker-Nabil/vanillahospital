<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }

        .my {
            margin-top: 1px;
            margin-bottom: 1px;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            @php
                $opd_id = $barcode_data->opd_id;
                $check = 0;
                //  $opd_id = 'OPD1719057580_233';
            @endphp
            @foreach ($barcode_data->outdoorPatientTest as $key => $list)
                {{-- {{dd($opd_id)}} --}}
                {{-- <td class="text-center my"  style="display: table-caption; border: 1px solid #333;"> --}}
                @for ($i = 1; $i <= 2; $i++)
                    <td class="text-center" style="border: 1px solid #333;">
                        <p>{{ $list->subTestGroup->title }}</p>
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode_data->id, 'C39', 2, 50) }}"
                            alt="" width="160" height="40">
                        <br>
                        <span style="margin-top: -40px;">Room:{{ $list->subTestGroup->room_no }}</span>
                        @php
                            $check = 1;
                        @endphp
                    </td>
                    @if ($no++ % 3 == 0)
        </tr>
        @if ($check)
             {{update_opd_barcode_status($barcode_data->id)}}
        @endif
        <tr>
            @endif
            @endfor
            @endforeach
        </tr>
    </table>
</body>

</html>
