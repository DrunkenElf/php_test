<?php
require __DIR__ . '/../bootstrap.php';

$curencies = get_all_currency();
add_update_currency($curencies);
echo "iteration";

