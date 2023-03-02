<?php
$currencies[] = get_currencies();
$result = "";

if (isset($_GET['btn_submit'])) {
    $digit = $_GET['txt_digit'];
    $abbr = $_GET['currency'];

    $data = (object)[];

    foreach ($currencies[0] as $currency) {
        if ($currency->name == $abbr)
            $data = $currency;
    }

    if ($digit != "") {
        $output = (int) $digit * $data->quantity / $data->value;
        $result = number_format($output, decimals: 4);

    }
}

?>
