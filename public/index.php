<?php

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/index.php';
require_login();
echo "<script>console.log('Debug Objects: " . sizeof($currencies) . "' );</script>";
if ($currencies == null || sizeof($currencies[0]) == 0) {
    echo "null";
    add();
    echo "<script>console.log('Debug Objects: " . sizeof($currencies) . "' );</script>";

    $currencies = get_currencies();
    reload();
}


if (isset($_POST['parser'])) {
    $cmd2 = 'c:\\xampp\\php\\php.exe "c:\\xampp\\htdocs\\test\\src\\libs\\background.php"';

    echo "<script>console.log('start process1 " . pclose(popen("start /B " . $cmd2, "w")) . "' );</script>";
    echo "<script>console.log('start process2 " . exec("whoami") . "');</script>";
}
?>

<?php view('header', ['title' => 'Dashboard']) ?>
<p>Welcome <?= current_user() ?> <a href="logout.php">Logout</a></p>
<br>
<form action="index.php" method="post">
    <input type="submit" class="button" name="parser" value="parser"/>
</form>
<br>

<div class="col-md-6 well">
    <h3 class="text-primary">Converter</h3>
    <hr style="border-top:1px dotted #000;"/>
    <form method="GET" action="">
        <div class="form-inline">
            <label>RUB: </label>
            <input class="form-control text-right" type="number"
                   value="<?php echo isset($_GET['txt_digit']) ? $_GET['txt_digit'] : '' ?>" name="txt_digit"/>
            <label>Select Currency: </label>
            <select name="currency" class="form-control">
                <option value="">Select an option</option>
                <?php
                foreach ($currencies[0] as $currency) { ?>
                    <option value="<?= $currency->name ?>"
                        <?php echo isset($_GET['currency']) && $_GET['currency'] == $currency->name ? 'selected' : ''; ?>>
                        <?= $currency->name ?>
                    </option>

                <?php } ?>

            </select>
            <br/><br/>
            <center>
                <button type="submit" name="btn_submit" class="btn btn-primary form-control" style="width:30%;">
                    Convert
                </button>
            </center>
            <br/>
            <center>
                <label class='text-success' style='font-size:25px;'>
                    <?php echo $result ?>
                </label>
            </center>
        </div>
    </form>
</div>


<!--<table>
    <tbody>
    <?php
/*    echo sizeof($currencies[0]);
    //print_r($currencies);
    //var_export($currencies);
    foreach ($currencies[0] as $currency) {

        */ ?>
        <tr>
            <td><?php /*= $currency->abbr */ ?></td>
            <td><?php /*= $currency->quantity */ ?></td>
            <td><?php /*= $currency->name */ ?></td>
            <td><?php /*= $currency->value; */ ?></td>
        </tr>
    <?php /*} */ ?>
    </tbody>
</table>-->
<?php view('footer') ?>
