<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/6/2019
 * Time: 12:39 PM
 */



if (!function_exists('removeSpecialChar')) {
    /**
     * Return human readable string from camel case or snake case string.
     *
     * @param $string
     * @return string
     */
    function removeSpecialChar($string)
    {

        $string = str_replace(' ', '', $string);
        $string = preg_replace('/[^a-zA-Z0-9_ -]/s', '', strtolower($string));

        return $string;
    }
}


if (!function_exists('human_words')) {
    /**
     * Return human readable string from camel case or snake case string.
     *
     * @param $string
     * @return string
     */
    function human_words($string)
    {
//        $string = snake_case($string);
        $string = ucwords(str_replace('_', ' ', $string));

        return $string;
    }
}


if (!function_exists('normal_writing_format')) {
    /**
     * Return human readable string from camel case or snake case string.
     *
     * @param $string
     * @return string
     */
    function normal_writing_format($string)
    {

        $string = str_replace('_', ' ', str_replace('-', ' ', $string));

        return $string;
    }
}


if (!function_exists('api_access_key_generate')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function api_access_key_generate($name)
    {
        $string = md5(date('Ymd') . $name . str_random(5));

        return $string;
    }
}

if (!function_exists('member_code_generate')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function member_code_generate()
    {
        $string = "M" . str_random(1) . date('Ymds');

        return $string;
    }
}


if (!function_exists('storage_asset')) {
    /**
     * Generate an asset storage path for the application.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function storage_asset($path, $secure = null)
    {
        return app('url')->asset('storage/' . $path, $secure);
    }
}

if (!function_exists('verify_token_generate')) {
    /**
     * Return string Verify Token
     *
     * @param $string
     * @return string
     */
    function verify_token_generate($value = '')
    {
        $string = md5($value . str_random(15));

        return $string;
    }
}

if (!function_exists('file_name_generator')) {
    /**
     * Return string FileName
     *
     * @param $string
     * @return string
     */
    function file_name_generator($value = '')
    {
        $value = snake_case($value);
        $string = $value . "_" . date('Ymdhis');
//        $string = $value."_".str_random(10)."_".date('Ymdhis');

        return $string;
    }
}


if (!function_exists('date_month_year_format')) {
    /**
     * Return string Date d-m-Y Format
     *
     * @param $string
     * @return string
     */
    function date_month_year_format($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->format(config('app.date_format'));
    }
}


if (!function_exists('formatted_date_string')) {
    /**
     * Return Written Format in date
     * As like Dec 20, 2019
     *
     * @param $string
     * @return string
     */
    function formatted_date_string($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->toFormattedDateString();
    }
}


if (!function_exists('db_date_format')) {

    /**
     * Return string Y-m-d format Date
     *
     * @param $string
     * @return string
     */

    function db_date_format($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->format('Y-m-d');
    }
}


if (!function_exists('db_date_month_year_format')) {
    /**
     * Return string d-m-Y format Date
     *
     * @param $string
     * @return string
     */
    function db_date_month_year_format($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->format(config('app.date_format'));
    }
}

if (!function_exists('create_money_format')) {
    function create_money_format($value)
    {
        $string = number_format((float)$value, 2, '.', ',');
        return $string;
    }
}

    if (!function_exists('update_opd_barcode_status')) {
        function update_opd_barcode_status($id)
        {
            \App\Models\OutDoorRegistration::authCompany()->where('id',$id)->update([
                'barcode_status'=>1,
            ]);
        }
    }

    if (!function_exists('pathologyFinalReport')) {
        function pathologyFinalReport($id)
        {
         $data =  \App\Models\pathologyFinalReport::authCompany()->with(['outDoorRegistration','subTestGroup'])->where('out_door_patient_test_id',$id)->first();

         return $data;
        }
    }

    if (!function_exists('technologist')) {
        function technologist($id)
        {
            $technologist =  \App\Models\AssignTechnologist::authCompany()->with('technologist')->where('sub_test_group_id',$id)->first();

            return $technologist;
        }
    }

if (!function_exists('create_money_format_with_dr_cr')) {

    function create_money_format_with_dr_cr($value)
    {
        $drCrText = $value < 0 ? " Cr" : " Dr";
        $value = $value < 0 ? $value * (-1) : $value;

        $string = number_format((float)$value, 2, '.', ',');
        $string = $value != 0 ? $string . $drCrText : "";

        return $string;

    }
}


if (!function_exists('create_float_format')) {
    function create_float_format($value, $decimal = 2)
    {
        $string = number_format((float)$value, $decimal, '.', '');

        return $string;

    }
}

if (!function_exists('labels')) {

    function labels()
    {

        return [0 => "Inactive", 1 => "Referral", 2 => "Satisfied", 3 => "Unsatisfied", 4 => "Potential", 5 => "Corporate", 6 => "90% Probability", 7 => "50% Probability", 8 => "Call this week"];
    }
}


if (!function_exists('month_date_year_format')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function month_date_year_format($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->format('m-d-Y');
    }
}

if (!function_exists('month_date_year_format2')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function month_date_year_format2($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->format('m/d/Y');
    }
}

if (!function_exists('date_string_format_with_time')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function date_string_format_with_time($value)
    {
        $string = \Carbon\Carbon::parse($value);

        return $string->toDayDateTimeString();
    }
}

if (!function_exists('create_date_format')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function create_date_format($value, $format = "-")
    {
        $originalDate = $value;
        return date("m" . $format . "d" . $format . "Y", strtotime($originalDate));
    }
}

if (!function_exists('create_time_format')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function create_time_format($value)
    {

        $string = \Carbon\Carbon::parse($value);

        return $string->format('h:i:s A');
    }
}


if (!function_exists('date_string_format')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function date_string_format($value)
    {

        $string = \Carbon\Carbon::parse($value);

        return $string->format('jS F, Y');
    }
}

if (!function_exists('transaction_code_generate')) {
    /**
     * Return Transaction Code
     *
     * @return string
     */
    function transaction_code_generate()
    {
        $string = "TC" . date('Ymdhis') . rand(0, 99);

        return $string;
    }
}


if (!function_exists('count_digit')) {
    /**
     * Return Count
     *
     * @return string
     */
    function count_digit($number)
    {
        return strlen($number);
    }
}

if (!function_exists('format_number_digit')) {
    /**
     * Return Formatted Digit
     *
     * @return string
     */

    function format_number_digit($value)
    {
        $number = strlen($value);
        $string = $value;

        if ($number == 1)
            $string = "00" . $string;
        elseif ($number == 2)
            $string = "0" . $string;
//        elseif ($number==3)
//            $string = "0".$string;

        return $string;
    }
}


if (!function_exists('emptyCheck')) {
    /**
     * Return string
     *
     * @return string
     */

    function emptyCheck($value)
    {
        $string = !empty($value) ? $value : "";
        return $string;
    }
}

    if (!function_exists('blood_group')) {

        function blood_group($index='')
        {
            $array=  ['A+', 'A-', "B+", "B-", "O+", "O-", "AB+", "AB-",];


            if($index !=''){
                $array =  $array[$index];
           }
            return $array;
        }

    }

if (!function_exists('filename')) {
    /**
     * Return string
     *
     * @return string
     */

    function filename($value)
    {
        $string = \Carbon\Carbon::today();

        return str_replace(' ', '_', $value . $string->format('d_m_Y'));
    }
}


if (!function_exists('requestURLPath')) {
    /**
     * Return string
     *
     * @return string
     */

    function requestURLPath($value, $current)
    {
        $string = str_replace($current, '', $value);
        return $string;
    }
}

if (!function_exists('memo_generate')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function memo_generate($str, $id)
    {
        $num = format_number_digit($id);
        $string = $str . $num;

        return $string;
    }
}

if (!function_exists('return_code_generate')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function return_code_generate($type = '')
    {
        $string = $type . "R" . date('Ymdhis') . rand(0, 9);

        return $string;
    }
}


if (!function_exists('sale_code_generate')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function sale_code_generate()
    {
        $string = "SL" . date('Ymdhis') . rand(0, 9);

        return $string;
    }
}

if (!function_exists('previous_year_last_date')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function previous_year_last_date()
    {
        $day = new \Carbon\Carbon('last day of last year');;

        return $day;
    }
}

if (!function_exists('generate_pin')) {
    /**
     * Return
     *
     * @param $string
     * @return string
     */
    function generate_pin($digit = 6)
    {
        $rand = rand(100000, 999999);

        return $rand;
    }
}

// if (! function_exists('decryptText')) {
//     /**
//      * Return
//      *
//      * @param $string
//      * @return string
//      */
//     function decryptText($value)
//     {
//         $text = \Crypt::decrypt($value);

//         return $text;
//     }
// }


if (!function_exists('header_shortname')) {
    /**
     * Return human readable string from camel case or snake case string.
     *
     * @param $string
     * @return string
     */
    function header_shortname($string)
    {

        $string = str_replace('_', ' ', $string);
        $pieces = explode(" ", $string);

        $name = '';
        for ($i = 0; $i < count($pieces); $i++) {
            $first_char = substr($pieces[$i], 0, 1);
            if ($i == 0)
                $name .= "<b>" . $first_char . "</b>";
            else
                $name .= $first_char;
        }

        return $name;
    }
}


if (!function_exists('phone_number_format')) {

    function phone_number_format($data)
    {
        $firstThree = substr($data, 0, 3);
        $firstTwo = substr($data, 0, 2);

        $phone = $data;
        if ($firstThree == "+88") {
            $phone = str_replace('+88', '', $data);
        }

        if ($firstTwo == "88") {
            $phone = str_replace('88', '', $data);
        }


        return "88" . $phone;
    }
}


if (!function_exists('AmountInWords')) {

    function AmountInWords(float $amount)
    {
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();

        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');

        $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

        while ($x < $count_length) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? '' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string [] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . '
        ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . '
        ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            } else $string[] = null;
        }

        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . "
    " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';

        $text = ($implode_to_Rupees ? $implode_to_Rupees . getCurrencyName() : '') . $get_paise;

        return $text . " Only.";
    }
}


if (!function_exists('limit_words')) {

    function limit_words($string, $word_limit = 20)
    {
        $string = strip_tags($string);
        $words = explode(' ', strip_tags($string));
        $return = trim(implode(' ', array_slice($words, 0, $word_limit)));
        if (strlen($return) < strlen($string)) {
            $return .= '...';
        }
        return $return;
    }
}


if (!function_exists('generate_employee_id')) {

    function generate_employee_id($value, $user_id)
    {
        $string = $value . $user_id . date("m") . date("y");

        return $string;

    }
}

if (!function_exists('code_generate')) {

    function code_generate($value, $user_id, $title="")
    {
        $string = $title.$value . $user_id . date("jny") ;

        return $string;

    }
}


if (!function_exists('get_commission_areas')) {

    function get_commission_areas()
    {
        $string = [
//            'country' => 'Country',
            'division' => 'State',
//            'region' => 'Region',
            'district' => 'City',
//            'thana' => 'Thana',
            'area' => 'Area',
        ];

        return $string;

    }
}


if (!function_exists('get_company_types')) {

    function get_company_types()
    {
        $string = [
            'Software' => 'Software',
            'Trading' => 'Trading',
            'Grocery' => 'Grocery',
            'Pharmacy' => 'Pharmacy',
            'E-Commerce' => 'E-Commerce',
        ];

        return $string;

    }
}


if (!function_exists('get_quote_ref')) {

    function get_quote_ref($text = "")
    {
        $text = $text . "/SALES";
        $words = explode(" ", $text);

        $fullText = "";
        foreach ($words as $word) {
            $firstLetter = (count($words) > 1) ? substr($word, 0, 2) : substr($word, 0, 3);
            $fullText .= $fullText ? "/" . strtoupper($firstLetter) : strtoupper($firstLetter);
        }

        return $fullText;
    }
}

if (!function_exists('get_daysOfWeek')) {

    function get_daysOfWeek()
    {
        $daysOfWeek = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        return $daysOfWeek;
    }
}

if (!function_exists('get_salary_system')) {

    function get_salary_system()
    {
        $data = [
            'Daily' => trans('common.daily'),
            'Monthly' => trans('common.monthly'),
            'Hourly' => trans('common.hourly'),
            'Contractual' => trans('common.contractual')
        ];

        return $data;
    }
}


if (!function_exists('get_months')) {

    function get_months()
    {
        $month = [];

        for ($m=1; $m<=12; $m++) {
             $month[] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }

        return $month;
    }
}

if (!function_exists('countWorkingDaysInMonth')) {

    function countWorkingDaysInMonth($year, $month)
    {

        $date = \Carbon\Carbon::parse("1 $month");
        $month = $date->month;

        $start = \Carbon\Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $workingDays = 0;

        $dayNames = \App\Models\Weekend::authCompany()->get();

        while ($start <= $end) {
            // Check if the current day is not a weekend (Saturday or Sunday)

            foreach ($dayNames as $value) {
                $dayName = $value->name;

                $currentDayName = $start->format('l'); // Get the current day name
                // Convert day name to lowercase to ensure case-insensitive comparison
                if (strtolower($currentDayName) !== strtolower($dayName)) {
                    // You can also add logic here to check for holidays and exclude them

                    $workingDays++;
                }

            }


            $start->addDay(); // Move to the next day
        }

        return $workingDays;
    }
}


if (!function_exists('employeeInfoUnsetByArrayMap')) {

    function employeeInfoUnsetByArrayMap($array)
    {
        $array = array_map(function($department) {
            unset($department['employee_details']);
            unset($department['uc_full_name']);
            unset($department['employee_name_id']);
            return $department;
        }, $array);

        return $array;
    }

}

if (!function_exists('getCurrencySymbol')) {

    function getCurrencySymbol()
    {
        return "€";
    }
}

if (!function_exists('getCurrencyName')) {

    function getCurrencyName()
    {
        return "Euro ";
    }
}


if (!function_exists('find_end_date')) {

    function find_end_date($date, $workingDays, $weekend = true)
    {

        $f_date = \Carbon\Carbon::parse($date)->format("Y-m-d");

        // Get the start date and add days from the request
        $startDate = new \DateTime($f_date);
        $addDays = $workingDays;
        $weekendDays = $weekend ? \App\Models\Weekend::authCompany()->pluck('name')->toArray() : [];

        // Initialize a counter for skipped weekend days
        $skippedWeekendDays = 0;

        // Loop to add days while skipping specified weekend days
        while ($addDays > 1) {
            // Calculate the next date
            $nextDate = clone $startDate;
            $nextDate->add(new \DateInterval('P1D'));

            // Check if the next date is a weekend day to be skipped
            if (in_array($nextDate->format('l'), $weekendDays)) {
                $skippedWeekendDays++;
            } else {
                // Only decrease $addDays if it's not a specified weekend day
                $addDays--;
            }

            // Move to the next day
            $startDate = $nextDate;
        }

        $endDate = clone $startDate;

        return $endDate->format('Y-m-d');
    }
}

if (!function_exists('variants_multiple')) {
    function variants_multiple($a, $b)
    {
        $array = [];

        for ($i=0; $i<count($a); $i++){
            for ($j=0; $j<count($b); $j++){
                $array[] = $b[$j]."-".$a[$i];
            }

            if(count($b) == 0)
            {
                $array[] = $a[$i];
            }
        }

        return $array;
    }


}






if (!function_exists('calculateAge')) {
    function calculateAge($birthdate) {
        $birthDate = new DateTime($birthdate);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y."Y ".$currentDate->diff($birthDate)->m."M ".$currentDate->diff($birthDate)->d."D";
        return $age;
    }


}
if (!function_exists('calculateAgeOnlyNumber')) {
    function calculateAgeOnlyNumber($birthdate) {
        $birthDate = new DateTime($birthdate);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y."#".$currentDate->diff($birthDate)->m."#".$currentDate->diff($birthDate)->d;
        return $age;
    }


}