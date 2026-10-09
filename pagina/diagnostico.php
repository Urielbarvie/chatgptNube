<?php
header('Content-Type: text/plain; charset=utf-8');
echo "PHP: " . PHP_VERSION . "\n";
echo "php.ini: " . (php_ini_loaded_file() ?: 'Ninguno') . "\n";
echo "Configuraciones adicionales: " . (php_ini_scanned_files() ?: 'Ninguna') . "\n";
echo "Extensiones: " . ini_get('extension_dir') . "\n";
echo "cURL: " . (extension_loaded('curl') ? 'OK' : 'NO cargado') . "\n";