<?php
/**
 * Plugin Name: Sejarah Peminjaman AMZ
 * Plugin URI: https://github.com/adeism
 * Description: Laporan untuk melihat sejarah peminjaman berdasarkan judul, kode eksemplar buku dll + statistik
 * Version: 1.0.0
 * Author: Ade Ismail Siregar
 * Author URI: https://github.com/adeism
 */

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

// registering plugin menu
$plugin->registerMenu('reporting', 'Sejarah Peminjaman AMZ', __DIR__ . '/index.php');
