<?php
include('/usr/local/emhttp/plugins/sabnzbd-tile/includes/config.php');
header('Content-Type: application/json');

if (empty($cfg['APIKEY'])) {
    echo json_encode(['error' => 'not_configured']);
    exit;
}

$host = rtrim($cfg['HOST'], '/');
$key  = $cfg['APIKEY'];
$ctx  = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true]]);

$qres = @file_get_contents("$host/api?mode=queue&apikey=$key&output=json&limit=1", false, $ctx);
if ($qres === false) {
    echo json_encode(['error' => 'connect_failed']);
    exit;
}

$out = ['speed' => '', 'eta' => '', 'paused' => false, 'queue' => [], 'last' => ''];

$qdata = json_decode($qres, true);
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

$hres = @file_get_contents("$host/api?mode=history&apikey=$key&output=json&limit=1", false, $ctx);
if ($hres !== false) {
    $hdata = json_decode($hres, true);
    if ($hdata && isset($hdata['history']['slots'][0])) {
        $out['last'] = $hdata['history']['slots'][0]['name'] ?? '';
    }
}

echo json_encode($out);
