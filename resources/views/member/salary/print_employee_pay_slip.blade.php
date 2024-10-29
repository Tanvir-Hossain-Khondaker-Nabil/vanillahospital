<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> {{ str_replace("<br/>", "", $report_title) }} </title>
    <style>
        body {
            margin: 20px;
        }
        p {
            margin: 9px 0;
        }
        .boder {
            border: 1px solid black;
            padding: 10px 20px 20px 35px;
            border-radius: 10px;
        }
        h3 {
            margin: 0;
            font-weight: 900;
        }
        .padding_left {
            padding-left: 20px;
        }
        .header_td_left {
            padding-right: 40px;
            border-right: 2px solid rgba(0, 0, 0, 0.313);
        }
        .header_td_right {
            padding-left: 40px;
            vertical-align: text-bottom;
        }
        table {
            text-align: left;
            width: 100%;
            vertical-align: text-bottom;
        }
        .secondTable td,
        th {
            padding: 5px 0;
            vertical-align: text-bottom;
        }
        .thridTable td,
        th {
            padding: 5px 0;
            vertical-align: text-bottom;
        }
        .secondTable {
            margin-top: 10px;

        }
        html,
        body {
            font-size: 9px;
        }
        strong.top span{
            font-weight: normal;
            padding-left: 10px;
        }
        strong.top {
            display: inline-block;
            width: 60%;
            float: right;
        }
        .bottop_table_area span{
            font-weight: normal;
            float: right;
        }
        .bottop_table_area{

            padding-left: 40px;
            padding-top: 2px;
        }
        .table_border{
            border: 2px solid black;
        }
        .table_border tr>td:not(:last-child){
            border-right: 2px solid black;
        }
        .table_border tr>td{
            padding:5px;
        }
        .table_border tr>td:not(:first-child){
            text-align: right;
        }
        .table_border thead th:not(:last-child){
            border-right: 2px solid black;
        }
        .table_border thead th{
            border-bottom: 2px solid black;
        }
        .table_border thead th{
            text-align: center;
        }
        .table_border tfoot th:not(:last-child){
            border-right: 2px solid black;
        }
        .table_border tfoot th{
            text-align: right;
            border-top: 2px solid black;
            padding-right: 4px;
        }
        .bottom_table tr th:not(:last-child){
            border-right: 2px solid black;

        }
        .bottom_table tr td:last-child{
            border-right: none;
        }
        .bottom_table tr td{
            border-right: 2px solid black;
            border-top: 2px solid black;
        }
        .bottom_table{
            margin-top: 70px;
            width: 70%;
            text-align: center;
            margin-left: auto;
            border-radius: 10px;
            border: 2px solid black;
        }
        .last_table tr th:last-child{
            border-bottom: 2px solid black;
        }
        .last_table{
            width: 90%;
            margin-top: 30px;
            margin-left: auto;
        }
    </style>
</head>
<body>
<!-- main table -->
<table cellpadding="0" cellspacing="0">
    <tbody>
    <tr>

        @for($i=0;$i<2;$i++)


            <td width="50%" class="{{ $i==0? 'header_td_left' : 'header_td_right' }}">
                <!-- header table left -->
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td colspan="2" class="boder">
                            <h3>
                                VANILLATHUNDER, UNIPESSOAL LDA <br />
                                NIF:89098586
                            </h3>
                            <br />
                            <h3>
                                Rua RAINHA DONA ESTEFANA A <br />
                                2675-347 Odiv.las
                            </h3>
                        </td>

                        @php
                            $month = array_search($emp_salary->en_month, get_months());
                            $salaryMonth = \Carbon\Carbon::create($emp_salary->en_year, $month+1, 1, 0,0,0);
                        @endphp
                        <td>
                            <div class="padding_left">
                                <h3>RECIBO DE VENCIMENTO</h3>
                                <p>Normal</p>
                                <p>ORJGINAL</p>
                                <p>De {{ $salaryMonth->format('jS F, Y') }}</p>
                                <p>ate {{ $salaryMonth->endOfMonth()->format('jS F, Y') }}</p>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table
                    class="secondTable"
                    width="100%"
                    cellpadding="0"
                    cellspacing="0"
                >
                    <tbody>
                    <tr>
                        <th width="20%">Name:</th>
                        <td width="80%">{{ $emp_salary->emp_name }}</td>
                    </tr>
                    <tr>
                        <th  style="width:20%;">N <sup>t</sup> Contribuinte:</th>
                        <td  style="width:80%;">
                            {{ $emp_salary->employee->nid }}
                            <strong class="top"> N <sup>z</sup> Mecanegrafico <span> {{ $emp_salary->employee->bank_account }} </span> </strong>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">N <sup>t</sup> Beneficiarlo:</th>
                        <td width="80%"> {{ $emp_salary->employee->bank_account }} </td>
                    </tr>
                    </tbody>
                </table>
                <table class="thridTable" width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <th width="20%">Categoria/Profissbo:</th>
                        <td width="50%">
                            {{ $emp_salary->emp_designation }}
                        </td>
                        <td width="30%" rowspan="4">
                            <h3 class="bottop_table_area">Venclmento:<span>{{ create_money_format($emp_salary->base_salary )}}</span></h3>
                            <h3 class="bottop_table_area">Salario Hora:<span>5.77c</span></h3>
                            <h3 class="bottop_table_area">Horas Semana:<span>40</span></h3>
                            <h3 class="bottop_table_area">Dias do Mes:<span>{{ $emp_salary->work_day }}</span></h3>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Tipo de Processamento:</th>
                        <td width="50%">
                            Normalizado
                        </td>

                    </tr>
                    <tr>
                        <th width="20%">Base  do Processamento:</th>
                        <td width="50%">
                            Gerado automaticamente
                        </td>

                    </tr>
                    <tr>
                        <th width="20%">Companhia de Segures:</th>
                        <td width="50%">
                            {{ $emp_salary->employee->insurance_company }} <br> {{ $emp_salary->employee->insurance_number }}
                        </td>

                    </tr>

                    </tbody>
                </table>
                <table class="table_border" width="100%" cellpadding="0" cellspacing="0">
                    @php
                        $taxamount = $emp_salary->tax;
                        $tax = $emp_salary->final_tax_amount;
                    @endphp
                    <thead>
                    <tr>

                    <th width="50%">DESCRICAO</th>
                    <th width="8%">QTD</th>
                    <th width="10%">V.UNIT.</th>
                    <th width="16%">ABONOS</th>
                    <th width="16%">BESCOUTOS</th>
                    </tr>
                    </thead>
                    <tbody>
{{--                    <tr>--}}
{{--                        <td>Verncimento Base</td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                        <td>{{ create_money_format($emp_salary->net_payable )}} &euro;</td>--}}
{{--                        <td></td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td>Verncimento Base</td>
                        <td>{{ $emp_salary->total_present }}</td>
                        <td>{{ create_money_format($emp_salary->net_payable/$emp_salary->total_present )}} &euro;</td>
                        <td>{{ create_money_format($emp_salary->net_payable )}} &euro;</td>
                        <td></td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td>Ajudas de Custo Nacional em valor (NAO TRIBUTADO)</td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                        <td>95.87$</td>--}}
{{--                        <td></td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>IRS ( Incidencia 1000$: Taxa IRS 26.5%: Parcela a <br> abater 169.09$ )</td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                        <td>95.00$</td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td>Sequranca Social ({{$taxamount}}%)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ create_money_format($tax) }} &euro;</td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td>IRS. Taxa allativa (Veecimanta): 9.5% </td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                        <td></td>--}}
{{--                    </tr>--}}
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3"> Total</th>
                        <th>{{ create_money_format($emp_salary->net_payable) }} &euro;</th>
                        <th>{{ create_money_format($tax) }} &euro;</th>
                    </tr>
                    </tfoot>
                </table>
                <table width="60%" class="bottom_table" cellpadding="0" cellspacing="0">
                    <tr>
                        <th>Total Abonos</th>
                        <th>Total Descontos </th>
                        <th>Total a Receber</th>
                    </tr>
                    <tr>
                        <td>{{ create_money_format($emp_salary->net_payable) }} &euro;</td>
                        <td>{{ create_money_format($tax) }} &euro;</td>
                        <td>{{ create_money_format($emp_salary->receive_amount) }} &euro;</td>
                    </tr>
                </table>
                <p>O Valor de {{ create_money_format($emp_salary->receive_amount) }} &euro; foi pago por Numerario <br> Deciaro que recebi aquantia constante neste recibo no vaior de: mil, vinte e dois euros e oitenta e sete <br> centimos.</p>
                <table class="last_table" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="10%">Assinatura:</th>
                        <th></th>
                    </tr>
                </table>
                <p style="text-align:right;margin-top: 10px;">Emitido por vanillathunder - https://www.vanillathunder.pt</p>
            </td>
        @endfor
    </tr>
    </tbody>
</table>
</body>
</html>
