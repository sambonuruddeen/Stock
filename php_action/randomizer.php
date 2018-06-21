<?php
require_once 'core.php';

$number_of_digits = 13;
echo substr(number_format(time() * mt_rand(),0,'',''),0,$number_of_digits);

