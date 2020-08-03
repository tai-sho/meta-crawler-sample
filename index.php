<?php
require 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;

$csv = array_map('str_getcsv', file('input.csv'));
var_dump($csv);
$i = 0;
$file = fopen("output.csv", "a");
foreach($csv as $val) {
  $uri = $val[0];
  $uri = str_replace(' ', '%20', $uri);
  if (!$uri) continue;
  echo $uri, PHP_EOL;
  $html_str = @file_get_contents($uri);
  $html = HtmlDomParser::str_get_html($html_str);
  if (!$html) {
    fputcsv($file, [$uri, null, null]);
    continue;
  }

  fputcsv($file, [
    $uri,
    $html->find('title', 0)->plaintext ?? null,
    $html->find('meta[name=description]', 0)->content ?? null
  ]);

  sleep(0.5);
}
fclose($file);
