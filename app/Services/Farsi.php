<?php namespace App\Services;

use Config;

Class Farsi {

    public function convertDigit($txt) {
        return (config('app.dir', 'ltr') == 'rtl' ? self::en2FaDigit($txt) : self::fa2EnDigit($txt));
    }
	
    public function faNormalize($string, $normalizeDigits=false, $html_normalizeDigits = false)
    {
        if(gettype($string)=='array' or gettype($string)=='object') {
            foreach($string as $key => $val) {
                $string[$key] = faNormalize($val, $normalizeDigits);
            }
            return $string;
        }
        $trans = array(
            "ا" => "ا", "أ" => "ا", "آ" => "آ", "ب" => "ب", "پ" => "پ", "ت" => "ت", "ث" => "ث", "ج" => "ج",
            "چ" => "چ", "ح" => "ح", "خ" => "خ", "د" => "د", "ذ" => "ذ", "ر" => "ر", "ز" => "ز", "ژ" => "ژ",
            "س" => "س", "ش" => "ش", "ص" => "ص", "ض" => "ض", "ط" => "ط", "ظ" => "ظ", "ع" => "ع", "غ" => "غ",
            "ف" => "ف", "ق" => "ق", "ک" => "ک", "ك" => "ک", "گ" => "گ", "ل" => "ل", "م" => "م", "ن" => "ن",
            "و" => "و", "ؤ" => "و", "ه" => "ه", "ة" => "ه", "ئ" => "ئ", "ى" => "ی", "ي" => "ی", "ی" => "ی");
        if($normalizeDigits){
            foreach(array(
                        "0" => "۰", "1" => "۱", "2" => "۲", "3" => "۳", "4" => "۴", "5" => "۵", "6" => "۶", "7" => "۷",
                        "8" => "۸", "9" => "۹",
                        "۰" => "0", "٠" => "0", "۱" => "1", '١' => '1', "۲" => "2", "٢" => "2", "۳" => "3", "٣" => "3", "۴" => "4",
                        "۵" => "5", "۶" => "6", "۷" => "7", "۸" => "8", "۹" => "9", "٩" => "9",
                        "٤" => "4", "٥" => "5", "٦" => "6", "٧" => "7", "٨" => "8")
                    as $k => $v){
                $trans[$k] = $v;}
        }
        if($html_normalizeDigits){
            foreach(array(
                        "0" => "&#x660;", "1" => "&#x661;", "2" => "&#x662;", "3" => "&#x663;", "4" => "&#x664;", "5" => "&#x665;", "6" => "&#x666;", "7" => "&#x667;",
                        "8" => "&#x668;", "9" => "&#x669;",
                        "٠" => "&#x660;", "١" => "&#x661;", "٢" => "&#x662;", "٣" => "&#x663;", "٤" => "&#x664;", "٥" => "&#x665;", "٦" => "&#x666;", "٧" => "&#x667;",
                        "٨" => "&#x668;", "٩" => "&#x669;")
                    as $k => $v){
                $trans[$k] = $v;}
        }
        return strtr($string, $trans);
    }

    public function ye($string, $normalizeDigits = false)
    {
        if (gettype($string) == 'array' or gettype($string) == 'object') {
            foreach ($string as $key => $val) {
                $string[$key] = ye_($val, $normalizeDigits);
            }
            return $string;
        }

        $trans = array(
            "ی" => "ي", "ی" => "ي", "ی" => "ي"
        );

        if ($normalizeDigits) {
            foreach (array(
                         "." => ",", "0" => "۰", "1" => "۱", "2" => "۲", "3" => "۳", "4" => "۴", "5" => "۵", "6" => "۶", "7" => "۷",
                         "8" => "۸", "9" => "۹",
                         "٠" => "۰", "١" => "۱", "٢" => "۲", "٣" => "۳", "٤" => "۴", "٥" => "۵", "٦" => "۶", "٧" => "۷",
                         "٨" => "۸", "٩" => "۹")
                     as $k => $v) {
                $trans[$k] = $v;
            }
        }
        return strtr($string, $trans);
    }

    public function faNormalizeNumber($val, $normalizeDigits = true, $htmlNormDigit = false)
    {
        return self::faNormalize(number_format($val, 0, ".", ","), $normalizeDigits, $htmlNormDigit);
    }

    public function faNormalizejDate($val)
    {
        return self::faNormalize(jDate("Y/m/d", 0, 0, strtotime($val)), true);
    }

    public function farsiSort($ar, $order = 'asc', $flags = SORT_STRING)
    {
        foreach ($ar as $k => $v)
            $ar[$k] = faEncode($v);
        if (strtolower($order) == 'desc') {
            arsort($ar, $flags);
        } else {
            asort($ar, $flags);
        }
        foreach ($ar as $k => $v)
            $ar[$k] = faDecode($v);
        return $ar;
    }

    public function arrayQsort(&$array, $column, $order = 'SORT_ASC', $first = 0, $last = -2)
    {
        // $array  - the array to be sorted
        // $column - index (column) on which to sort
        //          can be a string if using an associative array
        // $order  - SORT_ASC (default) for ascending or SORT_DESC for descending
        // $first  - start index (row) for partial array sort
        // $last  - stop  index (row) for partial array sort
        // $keys  - array of key values for hash array sort
        if (is_array($array)) {
            $keys = array_keys($array);

            if ($last == -2) $last = count($array) - 1;
            if ($last > $first) {
                $alpha = $first;
                $omega = $last;
                $key_alpha = $keys[$alpha];
                $key_omega = $keys[$omega];
                $guess = $array[$key_alpha][$column];
                while ($omega >= $alpha) {
                    if ($order == 'SORT_ASC') {
                        while ($array[$key_alpha][$column] < $guess) {
                            $alpha++;
                            $key_alpha = $keys[$alpha];
                        }
                        while ($array[$key_omega][$column] > $guess) {
                            $omega--;
                            $key_omega = $keys[$omega];
                        }
                    } else {
                        while ($array[$key_alpha][$column] > $guess) {
                            $alpha++;
                            $key_alpha = $keys[$alpha];
                        }
                        while ($array[$key_omega][$column] < $guess) {
                            $omega--;
                            $key_omega = $keys[$omega];
                        }
                    }
                    if ($alpha > $omega) break;
                    $temporary = $array[$key_alpha];
                    $array[$key_alpha] = $array[$key_omega];
                    $alpha++;
                    $key_alpha = $keys[$alpha];
                    $array[$key_omega] = $temporary;
                    $omega--;
                    $key_omega = @$keys[$omega];
                }
                arrayQsort($array, $column, $order, $first, $omega);
                arrayQsort($array, $column, $order, $alpha, $last);
            }
        }
        return $array;
    }

    public function farsiDbSort(&$records, $sortby, $order)
    {
        foreach ($records as $k => $v) {
            $records[$k][$sortby] = faEncode($v[$sortby]);
        }
        array_qsort($records, $sortby, ($order == "DESC") ? "SORT_DESC" : "SORT_ASC");
        foreach ($records as $k => $v) {
            $records[$k][$sortby] = faDecode($v[$sortby]);
        }
        return $records;
    }

    public function faDecode($str)
    {
        $_to_farsi = array(
            chr(131) . chr(48) => "ء", chr(131) . chr(49) => "آ", chr(131) . chr(50) => "ئ", chr(131) . chr(51) => "ؤ",
            chr(131) . chr(52) => "ا", chr(131) . chr(53) => "ب", chr(131) . chr(54) => "پ", chr(131) . chr(55) => "ت",
            chr(131) . chr(56) => "ث", chr(131) . chr(57) => "ج", chr(131) . chr(65) => "چ", chr(131) . chr(66) => "ح",
            chr(131) . chr(67) => "خ", chr(131) . chr(68) => "د", chr(131) . chr(69) => "ذ", chr(131) . chr(70) => "ر",
            chr(131) . chr(71) => "ز", chr(131) . chr(72) => "ژ", chr(131) . chr(73) => "س", chr(131) . chr(74) => "ش",
            chr(131) . chr(75) => "ص", chr(131) . chr(76) => "ض", chr(131) . chr(77) => "ط", chr(131) . chr(78) => "ظ",
            chr(131) . chr(79) => "ع", chr(131) . chr(80) => "غ", chr(131) . chr(81) => "ف", chr(131) . chr(82) => "ق",
            chr(131) . chr(83) => "ک", chr(131) . chr(84) => "گ", chr(131) . chr(85) => "ل", chr(131) . chr(86) => "م",
            chr(131) . chr(87) => "ن", chr(131) . chr(88) => "و", chr(131) . chr(89) => "ه", chr(131) . chr(90) => "ي",
            chr(131) . chr(91) => "ی");
        return strtr($str, $_to_farsi);
    }

    public function faEncode($str)
    {
        $_to_safe = array(
            "ء" => chr(131) . chr(48), "آ" => chr(131) . chr(49), "ئ" => chr(131) . chr(50), "ؤ" => chr(131) . chr(51),
            "ا" => chr(131) . chr(52), "ب" => chr(131) . chr(53), "پ" => chr(131) . chr(54), "ت" => chr(131) . chr(55),
            "ث" => chr(131) . chr(56), "ج" => chr(131) . chr(57), "چ" => chr(131) . chr(65), "ح" => chr(131) . chr(66),
            "خ" => chr(131) . chr(67), "د" => chr(131) . chr(68), "ذ" => chr(131) . chr(69), "ر" => chr(131) . chr(70),
            "ز" => chr(131) . chr(71), "ژ" => chr(131) . chr(72), "س" => chr(131) . chr(73), "ش" => chr(131) . chr(74),
            "ص" => chr(131) . chr(75), "ض" => chr(131) . chr(76), "ط" => chr(131) . chr(77), "ظ" => chr(131) . chr(78),
            "ع" => chr(131) . chr(79), "غ" => chr(131) . chr(80), "ف" => chr(131) . chr(81), "ق" => chr(131) . chr(82),
            "ک" => chr(131) . chr(83), "ك" => chr(131) . chr(83), "گ" => chr(131) . chr(84), "ل" => chr(131) . chr(85),
            "م" => chr(131) . chr(86), "ن" => chr(131) . chr(87), "و" => chr(131) . chr(88), "ه" => chr(131) . chr(89),
            "ي" => chr(131) . chr(90), "ی" => chr(131) . chr(91), "ى" => chr(131) . chr(91));
        return strtr($str, $_to_safe);
    }

//==============================================convert farsi===========

    public function avg2Text($inp)
    {
        $average_num = str_replace('.', ',', numpad($inp));

        $average_str = num2Text(intval($inp));

        if ($inp == round($inp)) {
            $average_str .= ' تمام';
        } else {
            $momyz = explode(',', $average_num);
            $momyz = num2Text($momyz[1]);
            $average_str .= " و " . $momyz . " صدم";
        }

        return $average_str;
    }

    public function num2Text($input)
    {

        $dec[1] = 'دهم';
        $dec[2] = 'صدم';
        $dec[3] = 'هزارم';
        $dec[4] = 'ده هزارم';
        $dec[5] = 'صد هزارم';
        $dec[6] = 'میلیونیوم';
        $dec[7] = 'ده میلیونیوم';
        $dec[8] = 'صد میلیونیوم';
        $dec[9] = 'میلیاردیوم';

        $digit = array(
            0 => '',
            1 => 'هزار',
            2 => 'میلیون',
            3 => 'میلیارد',
            4 => 'تریلون'
        );

        $num = array(
            0 => '',
            1 => 'یک',
            2 => 'دو',
            3 => 'سه',
            4 => 'چهار',
            5 => 'پنج',
            6 => 'شش',
            7 => 'هفت',
            8 => 'هشت',
            9 => 'نه',
            10 => 'ده',
            11 => 'یازده',
            12 => 'دوازده',
            13 => 'سیزده',
            14 => 'چهارده',
            15 => 'پانزده',
            16 => 'شانزده',
            17 => 'هفده',
            18 => 'هجده',
            19 => 'نوزده',
            20 => 'بیست',
            30 => 'سی',
            40 => 'چهل',
            50 => 'پنجاه',
            60 => 'شصت',
            70 => 'هفتاد',
            80 => 'هشتاد',
            90 => 'نود',
            100 => 'یکصد',
            200 => 'دویست',
            300 => 'سیصد',
            400 => 'چهارصد',
            500 => 'پانصد',
            600 => 'ششصد',
            700 => 'هفتصد',
            800 => 'هشتصد',
            900 => 'نهصد'
        );

        if (!$input) return 'صفر';

        if (floor($input) != $input) {
            $input = round($input, 5);
            $n = explode('.', $input);

            if ($n[0])
                $p1 = num2Text($n[0]) . 'و ';
            else
                $p1 = '';

            $p2 = num2Text($n[1]);
            $decimal = strlen($n[1]) < 10 ? strlen($n[1]) : 9;

            return $p1 . $p2 . $dec[$decimal];
        }

        $input = intval($input);

        for ($a = strlen($input); $a > 0; $a = $a - 3) {
            if ($a < 3)
                $dig1 = substr($input, 0, $a);
            else
                $dig1 = substr($input, $a - 3, 3);

            if (isset($num[$dig1])) {
                $final[] = $num[intval($dig1)];
            } else {
                $n[1] = floor($dig1 / 100) * 100;
                $n[2] = floor(fmod($dig1, 100) / 10) * 10;
                $n[3] = fmod($dig1, 10);

                $f = array();

                if ($n[1] != 0) $f[] = $num[$n[1]];

                if (isset($num[$n[2] + $n[3]])) {
                    $f[] = $num[$n[2] + $n[3]];
                } else {
                    if ($n[2] != 0) $f[] = $num[$n[2]];
                    if ($n[3] != 0) $f[] = $num[$n[3]];
                }

                $final[] = count($f) ? implode(' و ', $f) : '';
            }
        }

        $output = array();

        for ($a = count($final) - 1; $a >= 0; $a--) {
            if ($final[$a] != '') $output[] = $final[$a] . ' ' . $digit[$a];
        }

        return $output ? implode(' و ', $output) : '-';
    }

    public function farsiMail($string)
    {
        $trans = array(
            "0" => "&#1776;", "1" => "&#1777;", "2" => "&#1778;", "3" => "&#1779;", "4" => "&#1780;",
            "5" => "&#1781;", "6" => "&#1782;", "7" => "&#1783;", "8" => "&#1784;", "9" => "&#1785;",
            "٠" => "&#1776;", "١" => "&#1777;", "٢" => "&#1778;", "٣" => "&#1779;", "٤" => "&#1780;",
            "٥" => "&#1781;", "٦" => "&#1782;", "٧" => "&#1783;", "٨" => "&#1784;", "٩" => "&#1785;",
            " " => "&nbsp;", "ا" => "&#1575;", "أ" => "&#1575;", "آ" => "&#1570;", "ب" => "&#1576;",
            "پ" => "&#1662;", "ت" => "&#1578;", "ث" => "&#1579;", "ج" => "&#1580;", "چ" => "&#1670;",
            "ح" => "&#1581;", "خ" => "&#1582;", "د" => "&#1583;", "ذ" => "&#1584;", "ر" => "&#1585;",
            "ز" => "&#1586;", "ژ" => "&#1688;", "س" => "&#1587;", "ش" => "&#1588;", "ص" => "&#1589;",
            "ض" => "&#1590;", "ط" => "&#1591;", "ظ" => "&#1592;", "ع" => "&#1593;", "غ" => "&#1594;",
            "ف" => "&#1601;", "ق" => "&#1602;", "ک" => "&#1705;", "ك" => "&#1705;", "گ" => "&#1711;",
            "ل" => "&#1604;", "م" => "&#1605;", "ن" => "&#1606;", "و" => "&#1608;", "ؤ" => "&#1608;",
            "ه" => "&#1607;", "ة" => "&#1607;", "ئ" => "&#1740;ی", "ى" => "&#1740;ی", "ي" => "&#1740;ی",
            "ی" => "&#1740;ی");
        return strtr($string, $trans);
    }

    public function utf8SafeSubstr($string, $length, $start = 0)
    {
        //setting internal encoding to utf-8
        iconv_set_encoding('internal_encoding', 'UTF-8');
        $string = iconv_substr($string, $start, $length);
        $string = iconv_substr($string, 0, iconv_strrpos($string, ' ') + 1);
        return $string;
    }

    public function fa2EnDigit($txt)
    {
        $trans = array(
            "۰" => "0", "٠" => "0", "۱" => "1", '١' => '1', "۲" => "2", "٢" => "2", "۳" => "3", "٣" => "3", "۴" => "4",
            "۵" => "5", "۶" => "6", "۷" => "7", "۸" => "8", "۹" => "9", "٩" => "9",
            "٤" => "4", "٥" => "5", "٦" => "6", "٧" => "7", "٨" => "8");
        return strtr($txt, $trans);
    }

    public function en2FaDigit($txt)
    {
        $trans = array(
            "0" => "۰", "1" => "۱", "2" => "۲", "3" => "۳", "4" => "۴",
            "5" => "۵", "6" => "۶", "7" => "۷", "8" => "۸", "9" => "۹");
        return strtr($txt, $trans);
    }

    public function numPad($inp)
    {
        if (strstr($inp, '.')) {
            $d = explode('.', $inp);
            $d[1] = substr($d[1], 0, 2);
            if (strlen($d[1]) == 1) $d[1] .= '0';
            $inp = $d[0] . "." . $d[1];
        } else {
            $inp .= ".00";
        }

        return $inp;

//  return sprintf("%01.2f", floatval($inp + 0.0000001));
    }

    public function autoDirection($str)
    {
        $res = preg_match("/[ءآئؤابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهيی]/u", $str);

        if ($res) {
            $html = '<span style="direction:rtl; display:inline-block;">' . $str . "</span>";
        } else {
            $html = '<span style="direction:ltr; display:inline-block;">' . $str . "</span>";
        }

        return $html;
    }

    public function jDate($type, $TZhours = 0, $TZminute = 0, $maket = "now")
    {
        //set 1 if you want translate number to farsi or if you don't like set 0
        $transnumber = 0;

        if (preg_match('#\d+(-|/)\d+(-|/)\d+#', $maket)) {
            $maket = strtotime($maket);
        }

        if ($maket == -1) {
            return NULL;
        }
        //if($maket=="943907400" or time() > 12357591234)  return null;

        if ($maket == "now") {
            $year = date("Y");
            $month = date("m");
            $day = date("d");
            list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
            $maket = self::jMkTime(date("h") + $TZhours, date("i") + $TZminute, date("s"), $jmonth, $jday, $jyear);
        } else {
            $maket += $TZhours * 3600 + $TZminute * 60;
            $date = date("Y-m-d", $maket);
            list($year, $month, $day) = preg_split('/-/', $date);

            list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
        }

        $need = $maket;
        $year = date("Y", $need);
        $month = date("m", $need);
        $day = date("d", $need);
        $i = 0;
        $result = '';
        while ($i < strlen($type)) {
            $subtype = substr($type, $i, 1);
            switch ($subtype) {

                case "A":
                    $result1 = date("a", $need);
                    if ($result1 == "pm") @$result .= "بعد از ظهر";
                    else @$result .= "قبل از ظهر";
                    break;

                case "a":
                    $result1 = date("a", $need);
                    if ($result1 == "pm") $result .= "ب.ظ";
                    else @$result .= "ق.ظ";
                    break;

                case "B":
                    $result1 = date("a", $need);
                    if ($result1 == "pm") @$result .= "بعد از ظهر";
                    else @$result .= "صبح";
                    break;

                case "d":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    if ($jday < 10) $result1 = "0" . $jday;
                    else     $result1 = $jday;
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;

                case "D":
                    $result1 = date("D", $need);
                    if ($result1 == "Thu") $result1 = "پ";
                    else if ($result1 == "Sat") $result1 = "ش";
                    else if ($result1 == "Sun") $result1 = "ی";
                    else if ($result1 == "Mon") $result1 = "د";
                    else if ($result1 == "Tue") $result1 = "س";
                    else if ($result1 == "Wed") $result1 = "چ";
                    else if ($result1 == "Thu") $result1 = "پ";
                    else if ($result1 == "Fri") $result1 = "ج";
                    @$result .= $result1;
                    break;
                case"F":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    @$result .= self::monthname($jmonth);
                    break;
                case "g":
                    $result1 = date("g", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "G":
                    $result1 = date("G", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "h":
                    $result1 = date("h", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "H":
                    $result1 = date("H", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "i":
                    $result1 = date("i", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "j":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    $result1 = $jday;
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "l":
                    $result1 = date("l", $need);
                    if ($result1 == "Saturday") $result1 = "شنبه";
                    else if ($result1 == "Sunday") $result1 = "يكشنبه";
                    else if ($result1 == "Monday") $result1 = "دوشنبه";
                    else if ($result1 == "Tuesday") $result1 = "سه شنبه";
                    else if ($result1 == "Wednesday") $result1 = "چهارشنبه";
                    else if ($result1 == "Thursday") $result1 = "پنجشنبه";
                    else if ($result1 == "Friday") $result1 = "جمعه";
                    @$result .= $result1;
                    break;
                case "m":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    if ($jmonth < 10) $result1 = "0" . $jmonth;
                    else    $result1 = $jmonth;
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "M":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    @$result .= self::monthname($jmonth);
                    break;
                case "n":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    $result1 = $jmonth;
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "s":
                    $result1 = date("s", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "S":
                    @$result .= "ام";
                    break;
                case "t":
                    @$result .= self::lastday($month, $day, $year);
                    break;
                case "w":
                    $result1 = date("w", $need);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "y":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    $result1 = substr($jyear, 2, 4);
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                case "Y":
                    list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
                    $result1 = $jyear;
                    if ($transnumber == 1) $result .= self::convertNum2Farsi($result1);
                    else @$result .= $result1;
                    break;
                default:
                    @$result .= $subtype;
            }
            $i++;
        }
        return $result;
    }

    public function jMkTime($hour, $minute, $second, $jmonth, $jday, $jyear)
    {
        list($year, $month, $day) = self::jalali2Gregorian($jyear, $jmonth, $jday);
        $i = mktime($hour, $minute, $second, $month, $day, $year);
        return $i;
    }

    public function mstart($month, $day, $year) ///Find Day Begining Of Month
    {
        list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
        list($year, $month, $day) = self::jalali2Gregorian($jyear, $jmonth, "1");
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        return date("w", $timestamp);
    }

    public function lastday($month, $day, $year) //Find Number Of Days In This Month
    {
        $lastdayen = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
        list($jyear, $jmonth, $jday) = self::gregorian2Jalai($year, $month, $day);
        $lastdatep = $jday;
        $jday = $jday2 = $jDate2 = '';
        while ($jday2 != "1") {
            if ($day < $lastdayen) {
                $day++;
                list($jyear, $jmonth, $jday2) =  self::gregorian2Jalai($year, $month, $day);
                if ($jDate2 == "1") break;
                if ($jDate2 != "1") $lastdatep++;
            } else {
                $day = 0;
                $month++;
                if ($month == 13) {
                    $month = "1";
                    $year++;
                }
            }

        }
        return $lastdatep - 1;
    }

    public function monthname($month) //translate number of month to name of month
    {

        if ($month == "01") return "فروردين";

        if ($month == "02") return "ارديبهشت";

        if ($month == "03") return "خرداد";

        if ($month == "04") return "تير";

        if ($month == "05") return "مرداد";

        if ($month == "06") return "شهريور";

        if ($month == "07") return "مهر";

        if ($month == "08") return "آبان";

        if ($month == "09") return "آذر";

        if ($month == "10") return "دی";

        if ($month == "11") return "بهمن";

        if ($month == "12") return "اسفند";
    }

    public function convertNum2Farsi($srting) ////here convert to  number in persian
    {
        $num0 = "۰";
        $num1 = "۱";
        $num2 = "۲";
        $num3 = "۳";
        $num4 = "۴";
        $num5 = "۵";
        $num6 = "۶";
        $num7 = "۷";
        $num8 = "۸";
        $num9 = "۹";

        $stringtemp = "";
        $len = strlen($srting);
        for ($sub = 0; $sub < $len; $sub++) {
            if (substr($srting, $sub, 1) == "0") $stringtemp .= $num0;
            elseif (substr($srting, $sub, 1) == "1") $stringtemp .= $num1;
            elseif (substr($srting, $sub, 1) == "2") $stringtemp .= $num2;
            elseif (substr($srting, $sub, 1) == "3") $stringtemp .= $num3;
            elseif (substr($srting, $sub, 1) == "4") $stringtemp .= $num4;
            elseif (substr($srting, $sub, 1) == "5") $stringtemp .= $num5;
            elseif (substr($srting, $sub, 1) == "6") $stringtemp .= $num6;
            elseif (substr($srting, $sub, 1) == "7") $stringtemp .= $num7;
            elseif (substr($srting, $sub, 1) == "8") $stringtemp .= $num8;
            elseif (substr($srting, $sub, 1) == "9") $stringtemp .= $num9;
            else $stringtemp .= substr($srting, $sub, 1);

        }
        return $stringtemp;

    }

    ///end conver to number in persian

    public function div($a, $b)
    {
        return (int)($a / $b);
    }

    public function gregorian2Jalai($g_y, $g_m, $g_d)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);


        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $gd = $g_d - 1;

        $g_day_no = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);

        for ($i = 0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
            /* leap and after Feb */
            $g_day_no++;
        $g_day_no += $gd;

        $j_day_no = $g_day_no - 79;

        $j_np = self::div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
        $j_day_no = $j_day_no % 12053;

        $jy = 979 + 33 * $j_np + 4 * self::div($j_day_no, 1461); /* 1461 = 365*4 + 4/4 */

        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += self::div($j_day_no - 1, 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }

        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
            $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;

        return array($jy, $jm, $jd);
    }

    public function jalali2Gregorian($j_y, $j_m, $j_d)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);


        $jy = $j_y - 979;
        $jm = $j_m - 1;
        $jd = $j_d - 1;

        $j_day_no = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
        for ($i = 0; $i < $jm; ++$i)
            $j_day_no += @$j_days_in_month[$i];

        $j_day_no += $jd;

        $g_day_no = $j_day_no + 79;

        $gy = 1600 + 400 * self::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
        $g_day_no = $g_day_no % 146097;

        $leap = true;
        if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
            $g_day_no--;
            $gy += 100 * self::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
            $g_day_no = $g_day_no % 36524;

            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }

        $gy += 4 * self::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;

            $g_day_no--;
            $gy += self::div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }

        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i + 1;
        $gd = $g_day_no + 1;

        return array($gy, $gm, $gd);
    }

    public function j2gDate($inputDate)
    {
        // agha joon in tabe ta sale 1400 tooop kar mikone
        $inputDate = trim($inputDate);
        if (preg_match('/^[-0\:]*$/', $inputDate)) return '0000-00-00';
        if (preg_match('/^\d+$/', $inputDate)) {
            if (preg_match('/^13[2-9]\d{5}$/', $inputDate)) {
                $y = substr($inputDate, 0, 4);
                $m = substr($inputDate, 4, 2);
                $d = substr($inputDate, 6, 2);
            } elseif (preg_match('/^\d{6}$/', $inputDate)) {
                $y = substr($inputDate, 0, 2);
                $m = substr($inputDate, 2, 2);
                $d = substr($inputDate, 4, 2);
            } else {
                $d = substr($inputDate, 0, 2);
                $m = substr($inputDate, 2, 2);
                $y = substr($inputDate, 4);
            }
            if (intval($d) > 31) {
                $t = $d;
                $d = $y;
                $y = $t;
            }
            $inputDate = "$y-$m-$d";
        }
        if (preg_match('/([0-9]+[^0-9]+[0-9]+[^0-9]+[0-9]+)\s([0-9]+(\s*\:\s*[0-9]+(\s*\:\s*[0-9]+)?)?)/x', $inputDate, $ar)) {
            $inputDate = $ar[1];
            $time = preg_split('/[^0-9]+/', $ar[2], 3);
            $time = ' ' . intval(@$time[0]) . ':' . intval(@$time[1]) . ':' . intval(@$time[2]);
        }
        $split = preg_split('/[^0-9]+/', $inputDate, 3);
        $month = @$split[1];
        if (@$split[2] > 31) {
            $year = @$split[2];
            $day = @$split[0];
        } else {
            $year = @$split[0];
            $day = @$split[2];
        }
        $year = ($year > 1300) ? $year : $year + 1300;
        $TEMP = self::jalali2Gregorian(intval($year), intval($month), intval($day));
        $TEMP[1] = $TEMP[1] < 10 ? '0' . $TEMP[1] : $TEMP[1];
        $TEMP[2] = $TEMP[2] < 10 ? '0' . $TEMP[2] : $TEMP[2];
        return "$TEMP[0]-$TEMP[1]-$TEMP[2]" . @$time;
    }

    public function dif($D1, $D2 = "")
    {
        if ($D2 == "") $D2 = strtotime(date('Y/m/d'));
        if ($D1 == "") return 0;
        return floor((strtotime($D1) - $D2) / 86400);
    }

//############################################################

    public function g2jDate($input, $normalizeNumber = false, $seprator = '/')
    {
        if (!$input) return NULL;

        $date = date_parse($input);
        $date['hour'] = $date['hour'] ? $date['hour'] : 0;
        $date['minute'] = $date['minute'] ? $date['minute'] : 0;
        $date['given'] = strtotime($date['year'] . '-' . $date['month'] . '-' . $date['day']);
        $format = "Y" . $seprator . "m" . $seprator ."d" . ($date['hour'] ? " H:i" : "");
        $returnDate = self::jDate($format, $date['hour'], $date['minute'], $date['given']);
        $returnDate = $normalizeNumber ? self::faNormalize($returnDate, true) : $returnDate;
        return $returnDate;
    }

}