<?php

header('Content-Type: application/json');

require_once __DIR__ . '/YTDownloader.php';

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (!$url) {
    echo json_encode(['error' => 'URL was not provided.']);
    exit;
}

try 
{
    $ytd = new YTDownloader();
    $ytd->loadInfoFromUrl($url);

    echo json_encode([
        'formats' => $ytd->getFormats(),
        'adaptiveFormats' => $ytd->getAdaptiveFormats()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} 
catch (Exception $e)
{
    echo json_encode(['error' => $e->getMessage()]);
}