<?php
$c = file_get_contents('d:/Dudinha/real.php');
$c = str_replace('script.js', 'script.js?v=' . time(), $c);
file_put_contents('d:/Dudinha/real.php', $c);
