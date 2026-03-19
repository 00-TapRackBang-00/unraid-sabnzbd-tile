<?php
header('Content-Type: application/json');

$plgDir  = '/boot/config/plugins/sabnzbd-tile';
$cfgFile = "$plgDir/sabnzbd-tile.cfg";

$action  = $_POST['action'] ?? 'save';
$newHost = rtrim(trim($_POST['HOST'] ?? ''), '/');
$newKey  = trim($_POST['APIKEY'] ?? '');

if ($action === 'test') {
    if ($newHost && $newKey) {
        $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true]]);
        $res = @file_get_contents("$newHost/api?mode=version&apikey=$newKey&output=json", false, $ctx);
        if ($res !== false) {
            $json = json_decode($res, true);
            $ver  = $json['version'] ?? null;
            if ($ver) {
                echo json_encode(['ok' => true, 'message' => "Connected successfully — SABnzbd v$ver"]);
            } else {
                echo json_encode(['ok' => false, 'message' => 'Connected but response was unexpected. Check API key.']);
            }
        } else {
            echo json_encode(['ok' => false, 'message' => 'Connection failed. Check the URL and API key.']);
        }
    } else {
        echo json_encode(['ok' => false, 'message' => 'Enter both fields before testing.']);
    }
} else {
    if ($newHost && $newKey) {
        if (!is_dir($plgDir)) mkdir($plgDir, 0755, true);
        file_put_contents($cfgFile, "HOST=$newHost\nAPIKEY=$newKey\n");
        echo json_encode(['ok' => true, 'message' => 'Settings saved. Dashboard will refresh on next poll.']);
    } else {
        echo json_encode(['ok' => false, 'message' => 'Both fields are required.']);
    }
}
