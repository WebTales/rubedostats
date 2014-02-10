<?php

namespace Webtales;

use Github\Client as GithubClient;
use Packagist\Api\Client as PackagistClient;


require_once 'vendor/autoload.php';

$githubClient = new GithubClient();
$packagistClient = new PackagistClient();
$packagistStats = $packagistClient->get('webtales/rubedo')->getDownloads();
echo 'Téléchargements composer : ' . PHP_EOL;
echo "\t" . 'Total : ' . $packagistStats->getTotal() . PHP_EOL;
echo "\t" . 'Monthly : ' . $packagistStats->getMonthly() . PHP_EOL;
echo "\t" . 'Daily : ' . $packagistStats->getDaily() . PHP_EOL;
$releases = $githubClient->api('repo')->releases()->all('webtales', 'rubedo');
$fullTotal = 0;
foreach($releases as $release) {
    if (!$release['prerelease']) {
        echo $release['name'] . PHP_EOL;
        $total = 0;
        foreach($release['assets'] as $asset) {
            echo "\t" . $asset['name'] . ' : ' . $asset['download_count'] . PHP_EOL;
            $total += $asset['download_count'];
        }
        echo "\t" . 'Total : ' . $total . PHP_EOL;
        $fullTotal += $total;
    }
}
echo 'Total : ' . $fullTotal . PHP_EOL;
echo 'Total (avec composer) : ' . ($fullTotal + $packagistStats->getTotal()) . PHP_EOL;
