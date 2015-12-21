<?php
$time = microtime(true);
for ($i = 1; $i <= 1000000; $i++) {
    $x = rand();
}
echo microtime(true) - $time;