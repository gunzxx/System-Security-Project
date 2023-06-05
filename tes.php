<?php
// Periksa apakah ekstensi GD sudah terinstal
if (extension_loaded('gd') && function_exists('gd_info')) {
    echo "Ekstensi GD terinstal.";
} else {
    echo "Ekstensi GD tidak terinstal.";
}
