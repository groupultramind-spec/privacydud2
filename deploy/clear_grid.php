<?php
$file = 'd:/Dudinha/site_data.json';
$data = json_decode(file_get_contents($file), true);
unset($data['grid']);
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "Cleared grid";
