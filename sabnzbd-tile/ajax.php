<?php
include('/usr/local/emhttp/plugins/sabnzbd-tile/includes/config.php');
header('Content-Type: application/json');

if (empty($cfg['APIKEY'])) {
    echo json_encode(['error' => 'not_configured']);
    exit;
}

$host = rtrim($cfg['HOST'], '/');
$key  = $cfg['APIKEY'];

function sab_get($host, $key, $mode, $limit = 5) {
    $url = "$host/api?mode=$mode&apikey=$key&output=json&limit=$limit";
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true]]);
    $res = @file_get_contents($url, false, $ctx);
    if ($res === false) return null;
    return json_decode($res, true);
}

$qdata = sab_get($host, $key, 'queue', 3);
$hdata = sab_get($host, $key, 'history', 4);

if ($qdata === null && $hdata === null) {
    echo json_encode(['error' => 'connect_failed']);
    exit;
}

$out = [
    'speed'   => '',
    'eta'     => '',
    'paused'  => false,
    'queue'   => [],
    'history' => [],
];

if ($qdata && isset($qdata['queue'])) {
    $q = $qdata['queue'];
    $out['speed']  = trim($q['speed'] ?? '');
    $out['eta']    = $q['timeleft'] ?? '';
    $out['paused'] = (bool)($q['paused'] ?? false);

    foreach (($q['slots'] ?? []) as $slot) {
        $pct = (int)($slot['percentage'] ?? 0);
        $out['queue'][] = [
            'name'     => $slot['filename'] ?? '',
            'status'   => $slot['status'] ?? 'Downloading',
            'pct'      => $pct,
            'size'     => $slot['size'] ?? '',
            'sizeleft' => $slot['sizeleft'] ?? '',
            'timeleft' => $slot['timeleft'] ?? '',
            'cat'      => $slot['cat'] ?? '',
        ];
    }
}

if ($hdata && isset($hdata['history'])) {
    foreach (($hdata['history']['slots'] ?? []) as $slot) {
        $out['history'][] = [
            'name'   => $slot['name'] ?? '',
            'status' => $slot['status'] ?? '',
            'size'   => $slot['size'] ?? '',
            'cat'    => $slot['category'] ?? '',
            'fail'   => $slot['fail_message'] ?? '',
        ];
    }
}

echo json_encode($out);
