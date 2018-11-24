<?php

function size_recursive($path)
{
    $size = 0;
    if (is_dir($path))
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file)
        {
            $size += $file->getSize();
        }
    }
    else
    {
        $size = filesize($path);
    }

    if ($size / 1048576 > 1) {
        return round($size / 1048576, 1) . ' MB';
    } elseif ($size / 1024 > 1) {
        return round($size / 1024, 1) . ' KB';
    } else {
        return round($size, 1) . ' bytes';
    }

    return $size;
}

function currency($amount)
{
    return config('currency.before') . number_format((float)$amount, 2, '.', '') . config('currency.after');
}

function monthsArray()
{
    return [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
}

function flyAway($url)
{ ?>
    <script>
        window.location = "<?php echo $url; ?>";
    </script>
    <?php
}


function getAlphabets()
{
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return str_split($string);
}

function downloadUrlById($attachment)
{
    return url('attachments/download/' . encryptIt($attachment));
}


function pd($var)
{
    print_r($var);
    die();
}

function ed($val)
{
    echo $val;
    die();
}

function firstOption($title = 'Select')
{
    return "<option value=''>" . $title . "</option>";
}

function rand_color()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function secondsToHumanReadable(/*int*/
    $seconds)/*: string*/
{
    //if you dont need php5 support, just remove the is_int check and make the input argument type int.
    if (!\is_int($seconds)) {
        throw new \InvalidArgumentException('Argument 1 passed to secondsToHumanReadable() must be of the type int, ' . \gettype($seconds) . ' given');
    }
    $dtF = new \DateTime ('@0');
    $dtT = new \DateTime ("@$seconds");
    $ret = '';
    if ($seconds === 0) {
        // special case
        return '0 seconds';
    }
    $diff = $dtF->diff($dtT);
    foreach (array(
                 'y' => 'year',
                 'm' => 'month',
                 'd' => 'day',
                 'h' => 'hour',
                 'i' => 'minute',
                 's' => 'second'
             ) as $time => $timename) {
        if ($diff->$time !== 0) {
            $ret .= $diff->$time . ' ' . $timename;
            if ($diff->$time !== 1 && $diff->$time !== -1) {
                $ret .= 's';
            }
            $ret .= ' ';
        }
    }
    return substr($ret, 0, -1);
}

function isCurrentAction($action)
{
    return Route::currentRouteAction() == 'App\Http\Controllers\\' . $action;
}

function ValidationNotificationFront($errors)
{
    if ($errors->count() > 0): foreach ($errors->all('                             Error!                             :message                         ') as $message) {
        echo $message;
    } endif;
}

function NotificationFront()
{
    if (Session::has('success')): echo '' . Session::get('success') . '';
    elseif (Session::has('info')): echo '' . Session::get('info') . '';
    elseif (Session::has('error')): echo '' . Session::get('error') . ''; endif;
}

function dummyUrl(){
    return asset('images/logo.svg');
}

function limitWords($words, $limit, $append = ' ...') {
    // Add 1 to the specified limit becuase arrays start at 0
    $limit = $limit+1;
    // Store each individual word as an array element
    // Up to the limit
    $words = explode(' ', $words, $limit);
    // Shorten the array by 1 because that final element will be the sum of all the words after the limit
    array_pop($words);
    // Implode the array for output, and append an ellipse
    $words = implode(' ', $words) . $append;
    // Return the result
    return $words;
}

function readFromCSV($path, $demiliter = ",")
{
    $header = null;
    $data = array();

    $destinationPath = $path;

    if (($handle = fopen($destinationPath, 'r')) !== false) {
        while (($row = fgetcsv($handle, 0, $demiliter)) !== FALSE) {
            if (!$header) {
                $header = $row;
            } else {
                if (count($row) > count($header)) {
                    array_pop($row);
                }
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);
    }

    return $data;
}

function calculate_price($price, $shipping_price)
{
    if ($price <= 5)
    {
        return number_format(($price + $shipping_price + 0.7) / 0.85, 2);
    } else if ($price <= 10)
    {
        return number_format(($price + $shipping_price + 0.85) / 0.85, 2);
    } else if ($price <= 20)
    {
        return number_format(($price + $shipping_price + 1.5) / 0.85, 2);
    } else if ($price <= 30)
    {
        return number_format(($price + $shipping_price + 3) / 0.85, 2);
    } else if ($price <= 40)
    {
        return number_format(($price + $shipping_price + 4) / 0.85, 2);
    } else if ($price <= 50)
    {
        return number_format(($price + $shipping_price + 5) / 0.85, 2);
    } else if ($price <= 60)
    {
        return number_format(($price + $shipping_price + 6) / 0.85, 2);
    } else if ($price <= 100)
    {
        return number_format(($price + $shipping_price + 8) / 0.85, 2);
    } else if ($price <= 150)
    {
        return number_format(($price + $shipping_price + 10) / 0.85, 2);
    } else {
        return number_format(($price + $shipping_price + 11) / 0.85, 2);
    }
}

function calculate_shipping_price($weight)
{
    $weight = number_format($weight, 2);
    if ($weight < 0.325)
    {
        return 2.98;
    } else if ($weight < 0.375)
    {
        return 3.09;
    } else if ($weight < 0.4375)
    {
        return 3.21;
    } else if ($weight < 0.5)
    {
        return 3.28;
    } else if ($weight < 0.5625)
    {
        return 3.76;
    } else if ($weight < 0.625)
    {
        return 3.96;
    } else if ($weight < 0.8125)
    {
        return 4.07;
    } else if ($weight < 1)
    {
        return 4.45;
    } else if ($weight <= 9)
    {
        return 8;
    } else if ($weight <= 10)
    {
        return 13;
    } else if ($weight <= 15)
    {
        return 14;
    } else if ($weight <= 20)
    {
        return 16;
    } else {
        return 18;
    }
}

function calculate_price_shopify($wholesale_price)
{
    if ($wholesale_price <= 10)
    {
        return $wholesale_price + 0.5;
    } elseif ($wholesale_price <= 20)
    {
        return $wholesale_price + 1;
    } elseif ($wholesale_price <= 200)
    {
        return $wholesale_price + 1.5;
    }
}