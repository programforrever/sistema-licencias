<?php
$ctx = stream_context_create(['http'=>['timeout'=>10]]);
$content = @file_get_contents('http://127.0.0.1:8000/login', false, $ctx);
if ($content === false) {
    $err = error_get_last();
    echo "ERROR: ".($err['message'] ?? 'unknown')."\n";
} else {
    echo "OK len=".strlen($content)."\n";
}
