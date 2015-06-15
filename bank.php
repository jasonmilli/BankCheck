<?php
function errorHandler($no, $str, $file, $line) {
    if ($no == 2) echo "Error opening file, line $line in $file, $str\n";
    else print_r(array($no, $str, $file, $line));
    exit();
}
function setLine($algorithm, $weights) {
    $line = array('algorithm' => $algorithm);
    for ($i = 0; $i < 6; $i++) $line["sc$i"] = $weights[$i];
    for ($i = 0; $i < 8; $i++) $line["an$i"] = $weights[$i + 6];
    return $line;
}
set_error_handler('errorHandler');
function check($bank, $sortcode, $number) {
    $weights = explode("\n", file_get_contents('http://www.vocalink.com/media/700423/valacdos.txt'));
    $sortcodes = array();
    $columns = array('from', 'to', 'algorithm', 'sc0', 'sc1', 'sc2', 'sc3', 'sc4', 'sc5', 'an0', 'an1', 'an2', 'an3', 'an4', 'an5', 'an6', 'an7', 'exception');
    foreach ($weights as $weight) {
        $weight = explode(" ", $weight);
        $sc = array();
        $column = 0;
        foreach ($weight as $w) {
            if (strlen($w)) {
                $sc[$columns[$column]] = $w;
                $column++;
            }
        }
        if (count($sc)) $sortcodes[] = $sc;
    }
    $substitutes = explode("\n", file_get_contents('http://www.vocalink.com/media/300584/scsubtab.txt'));
    $subs = array();
    foreach ($substitutes as $substitute) {
        $substitute = explode(" ", $substitute);
        if (count($substitute) > 1) $subs[$substitute[0]] = $substitute[1];
    }
    $whitelist = array('Allied Irish','Bank of England','Bank of Ireland','Bank of Scotland','Barclays','Bradford and Bingley Building Society','Citibank','Clydesdale','Co-Operative Bank','Coutts','First Trust','Halifax','Hoares Bank','HSBC','Lloyds','NatWest','Nationwide Building Society','Northern','Orwell Union Ltd','Royal Bank of Scotland','Santander','Secure Trust','TSB','Ulster Bank','Virgin Bank','Woolwich','Yorkshire Bank');
    /*if (!isset($argv[1])) {
        echo "No Bank provided\n";
        exit();
    }
    $bank = $argv[1];*/
    if (!in_array($bank, $whitelist)) {
        echo "Bank not in whitelist\n";
        exit();
    }
    /*if (!isset($argv[2])) {
        echo "Sortcode not provided\n";
        exit();
    }
    $sortcode = $argv[2];*/
    if (strlen($sortcode) != 6 || $sortcode != (string) ($sortcode + 0)) {
        echo "Sortcode needs to be 6 digits\n";
        exit();
    }
    /*if (!isset($argv[3])) {
        echo "Account number not provided\n";
        exit();
    }
    $number = $argv[3];*/
    if (strlen($number) < 6 || strlen($number) > 10 || $number != (string) ($number + 0)) {
        echo "Account number not in correct format\n";
        exit();
    }
    if (strlen($number) == 10 && $bank == 'NatWest') $number = substr($number, 2);
    if (strlen($number) == 10) $number = substr($number, 0, 8);
    if (strlen($number) == 9) {
        $sortcode = substr($sortcode, 0, 5).$number[0];
        $number = substr($number, 1);
    }
    $number = sprintf('%08d', $number);
    $valid = true;
    $exception5 = true;
    foreach ($sortcodes as $line) {
        if ($line['from'] > $sortcode || $line['to'] < $sortcode) continue;
        if (isset($line['exception'])) $exception = $line['exception'];
        else $exception = 0;
        if ($exception == 2) {
            if ($number[0] != 0 && $number[6] != 9) $line = setLine($line['algorithm'], array(0,0,1,2,5,3,6,4,8,7,10,9,3,1));
            if ($number[0] != 0 && $number[6] == 9) $line = setLine($line['algorithm'], array(0,0,0,0,0,0,0,0,8,7,10,9,3,1));
            echo "T {$number[0]} {$number[6]}\n";
            print_r($line);
        }
        if ($exception == 9 && $valid) continue;
        elseif ($exception == 9) $sortcode = '309634';
        if ($exception == 3 && ($number[2] == 6 || $number[2] == 9) && $line['algorithm'] == 'DBLAL') continue;
        if ($exception == 5 && array_key_exists($sortcode, $subs)) $sortcode = $subs[$sortcode];
        if ($exception == 6 && in_array($number[0], array(4,5,6,7,8)) && $number[6] == $number[7]) continue;
        if ($exception == 7 && $number[6] == 9) $line = setLine($line['algorithm'], array(0,0,0,0,0,0,0,0,$line['an2'],$line['an3'],$line['an4'],$line['an5'],$line['an6'],$line['an7']));
        if ($exception == 8) $sortcode = '090126';
        if ($exception == 10 && in_array($number[0].$number[1], array('09', '99')) && $number[6] == 9) {
            $line = setLine($line['algorithm'], array(0,0,0,0,0,0,0,0,$line['an2'],$line['an3'],$line['an4'],$line['an5'],$line['an6'],$line['an7']));
        }
        if ($exception == 11) {
            if ($valid) continue;
            else $valid = true;
        }
        if ($exception == 13) {
            if ($valid) continue;
            else $valid = true;
        }
        $total = 0;
        for ($i = 0; $i < 6; $i++) {
            $product = $line["sc$i"] * $sortcode[$i];
            if ($product > 9 && $line['algorithm'] == 'DBLAL') {
                $product = $product % 9;
                if ($product == 0) $product = 9;
            }
            $total += $product;
            echo "{$line["sc$i"]} {$sortcode[$i]} $product\n";
        }
        for ($i = 0; $i < 8; $i++) {
            $product = $line["an$i"] * $number[$i];
            if ($product > 9 && $line['algorithm'] == 'DBLAL') {
                $product = $product % 9;
                if ($product == 0) $product = 9;
            }
            $total += $product;
            echo "{$line["an$i"]} {$number[$i]} $product\n";
        }
        $mod = 10;
        if ($line['algorithm'] == 'MOD11') $mod = 11;
        if ($exception == 1) $total += 27;
        echo "$mod $total {$line['algorithm']} $exception\n";
        $remainder = $total % $mod;
        if ($remainder != 0) $valid = false;
     /*   if ($exception == 9 && !$valid) {
            $sortcode = '309634';
            $total = 0;
            for ($i = 0; $i < 6; $i++) {
                $product = $line["sc$i"] * $sortcode[$i];
                if ($product > 9) {
                    $product = $product % 9;
                    if ($product == 0) $product = 9;
                }
                $total += $product;
            }
            for ($i = 0; $i < 8; $i++) {
                $product = $line["an$i"] * $number[$i];
                if ($product > 9) {
                    $product = $product % 9;
                    if ($product == 0) $product = 9;
                }
                $total += $product;
            }
            if ($total % 11 == 0) $valid = true;
        }*/
        if ($exception == 1) $total += 27;
        echo "$mod $total {$line['algorithm']} $exception\n";
        $remainder = $total % $mod;
        if ($remainder != 0) $valid = false;
        if ($exception == 4 && $remainder == substr($number, 6)) $valid = true;
        if ($exception == 5) {
            if ($remainder == 1 && $line['algorithm'] == 'MOD11') {
                $exception5 = false;
                continue;
            }
            $line['algorithm'] == 'DBLAL' ? $check = 7 : $check = 6;
            if (($remainder == 0 && $number[$check] == 0) || $mod - $remainder == $number[$check]) continue;
        } else $exception5 = false;
        if ($exception == 14 && !$valid && in_array($number[7], array(0,1,9))) {
            $number = '0'.substr($number,0,7);
            $total = 0;
            for ($i = 0; $i < 8; $i++) $total += $line["an$i"] * $number[$i];
            if ($total % 11 == 0) $valid = true;
        }
    }
    $valid = $valid || $exception5 ? 'Yes' : 'No';
    echo "Bank: $bank\nSortcode: $sortcode\nAccount number: $number\nValid: $valid\n";
}
$tests = array(array('180002','00000190','Y'),array('086090','06774744','Y'),array('089999','66374958','Y'),array('089999','66374959','N'),array('107999','88837491','Y'),array('107999','88837493','N'),array('118765','64371388','N'),array('134020','63849203','Y'),array('202959','63748472','Y'),array('203099','66831036','N'),array('203099','58716970','N'),array('309070','02355688','Y'),array('309070','12345677','Y'),array('309070','99345694','Y'),array('772798','99345694','Y'),array('827101','28748352','Y'),array('938600','42368003','Y'),array('938611','07806039','Y'),array('871427','09123496','Y'),array('074456','11104102','Y'),array('074456','12345112','Y'),array('309070','12345668','Y'),array('938063','15763217','N'),array('938063','15764264','N'),array('938063','15764273','N'),array('070116','34012583','Y'),array('200915','41011166','Y'),array('871427','46238510','Y'),array('872427','46238510','Y'),array('938063','55065200','Y'),array('118765','64371389','Y'),array('820000','73688637','Y'),array('827999','73988638','Y'),array('871427','99123496','Y'));
foreach ($tests as $test) {
    if ($test[0] != '309070') continue;
//    print_r($test);
    check('Barclays', $test[0], $test[1]);
    echo "Should be {$test[2]}\n";
}
