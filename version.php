<?php
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'block_coursetabs'; // Nome del componente
$plugin->version   = 2024112200;       // Data (YYYYMMDDXX)
$plugin->requires  = 2020061500;       // Versione minima di Moodle
$plugin->maturity  = MATURITY_STABLE;  // Stabilità del plugin
$plugin->release   = '1.0';            // Versione del plugin

// Dipendenza dal tema Universe
$plugin->dependencies = [
    'theme_universe' => ANY_VERSION,
];
