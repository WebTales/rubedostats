<?php

namespace Webtales;

use Github\Client as GithubClient;
use Packagist\Api\Client as PackagistClient;

require_once 'vendor/autoload.php';

$githubClient = new GithubClient();
$packagistClient = new PackagistClient();

$packagistStats = $packagistClient->get('webtales/rubedo')->getDownloads();
$releases = $githubClient->api('repo')->releases()->all('webtales', 'rubedo');

$cloneActivityURL = 'https://github.com/WebTales/rubedo/graphs/clone-activity-data';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cloneActivityURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Rubedo stats');
$resultat = @curl_exec ($ch);
curl_close($ch);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>

        <h1>Stats de téléchargement Rubedo</h1>
        <h2>Téléchargements composer</h2>
        <ul>
            <li>Total : <?php echo $packagistStats->getTotal(); ?></li>
            <li>Ce mois : <?php echo $packagistStats->getMonthly(); ?></li>
            <li>Ce jour : <?php echo $packagistStats->getDaily(); ?></li>
        </ul>
		<h2>Git clone</h2>
		<ul>
			<li><?php var_dump($resultat); ?></li>
		</ul>
        <h2>Téléchargements Github</h2>
        <ul>
        <?php
        $fullTotal = 0;
        foreach($releases as $release) {
            if (!$release['prerelease']) {
                $total = 0;
            ?>
            <li>
                <strong><?php echo $release['name']; ?></strong>
                <ul>
                    <?php foreach($release['assets'] as $asset) {
                    $total += $asset['download_count']; ?>
                    <li><?php echo $asset['name']; ?> : <?php echo $asset['download_count']; ?></li>
                    <?php
                    } ?>
                    <li>Total : <?php echo $total; ?></li>
                </ul>
                <?php
                $fullTotal += $total;
            ?></li><?php
            }
        }
        ?>
        </ul>
        <ul>
            <li>Total : <?php echo $fullTotal; ?></li>
            <li>Total (composer inc) : <?php echo ($fullTotal + $packagistStats->getTotal()); ?></li>
        </ul>
    </body>
</html>