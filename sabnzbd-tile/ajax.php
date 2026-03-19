<?php
include('/usr/local/emhttp/plugins/sabnzbd-tile/includes/config.php');
header('Content-Type: application/json');

if (empty($cfg['APIKEY'])) {
    echo json_encode(['error' => 'not_configured']);
    exit;
}

$host = rtrim($cfg['HOST'], '/');
$key  = $cfg['APIKEY'];

$url = "$host/api?mode=queue&apikey=$key&output=json&limit=1";
$ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true]]);
$res = @file_get_contents($url, false, $ctx);

if ($res === false) {
    echo json_encode(['error' => 'connect_failed']);
    exit;
}

$qdata = json_decode($res, true);
$out = ['speed' => '', 'eta' => '', 'paused' => false, 'queue' => []];

if ($qdata && isset($qdata['queue'])) {
    $q = $qdata['queue'];
    $out['speed']  = trim($q['speed'] ?? '');
    $out['eta']    = $q['timeleft'] ?? '';
    $out['paused'] = (bool)($q['paused'] ?? false);

    foreach (($q['slots'] ?? []) as $slot) {
        $out['queue'][] = [
            'name'   => $slot['filename'] ?? '',
            'status' => $slot['status'] ?? 'Downloading',
            'pct'    => (int)($slot['percentage'] ?? 0),
        ];
    }
}

echo json_encode($out);
