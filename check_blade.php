<?php
$content = file_get_contents('c:\xampp\htdocs\sistema-licencias\resources\views\solicitudes\show.blade.php');
$lines = explode("\n", $content);

foreach ($lines as $i => $line) {
    if (strpos($line, '@php') !== false || strpos($line, '@endphp') !== false || strpos($line, '@endsection') !== false) {
        echo ($i + 1) . ": " . $line . "\n";
    }
}
?>
