<?php
$cfgFile = '/boot/config/plugins/sabnzbd-tile/sabnzbd-tile.cfg';
$cfg = file_exists($cfgFile) ? (parse_ini_file($cfgFile) ?: []) : [];
$cfg = array_merge(['HOST' => 'http://localhost:8080', 'APIKEY' => ''], $cfg);
