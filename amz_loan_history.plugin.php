<?php
/**
 * Plugin Name: AMZ- Sejarah Peminjaman Buku Praktis
 * Plugin URI: https://github.com/adeism
 * Description: Laporan untuk melihat riwayat peminjaman berdasarkan judul atau kode eksemplar buku.
 * Version: 1.0.0
 * Author: Ade Ismail Siregar
 * Author URI: https://github.com/adeism
 */

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

// registering plugin menu
$plugin->registerMenu('reporting', 'Sejarah Peminjaman AMZ', __DIR__ . '/index.php');