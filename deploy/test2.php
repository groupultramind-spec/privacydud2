<?php
$username = 'dudinhaprivacy';
$ch = curl_init("https://www.instagram.com/{$username}/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1');
$html = curl_exec($ch);
curl_close($ch);

if ($html) {
    preg_match('/<meta property="og:title" content="(.*?)"/', $html, $titleMatch);
    preg_match('/<meta property="og:image" content="(.*?)"/', $html, $imgMatch);
    if (isset($titleMatch[1]) && isset($imgMatch[1])) {
        echo "Found: " . $titleMatch[1] . "\n";
        echo "Image: " . $imgMatch[1] . "\n";
    } else {
        echo "Failed to parse. HTML length: " . strlen($html) . "\n";
        // echo substr($html, 0, 500);
    }
} else {
    echo "Curl failed.\n";
}
