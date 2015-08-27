<?php
require_once('common_include.php');
?>

<html>
<head>
    <title><?php echo TITLE ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
        }
        td {
            font-size: 16px !important;
        }
        table {
            margin-top: 5px;
            width: 100%;
        }

        table.tb tr:first-child td {
            padding: 10px;
        }
    </style>
</head>
<body>
<h1><?php echo TITLE ?></h1>

<?php
$html = file_get_contents(SCRAPE_URL);

$last_head_start = strrpos($html, '<head>');
$html = substr($html, $last_head_start);
$first_body_end = strpos($html, '</body>');
$html = substr($html, 0, ($first_body_end + 7));
$html = '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . substr($html, 6);

$html = str_replace(array('Vask/Wash', '/Dry'), array('Vaskemaskiner', 'etromler'), $html);

$dom = new DOMDocument;
$dom->loadHTML($html);
$dom->preserveWhiteSpace = false;

$imgs = $dom->getElementsByTagName("img");
while ($imgs->length > 0) {
    $img = $imgs->item(0);
    $img->parentNode->removeChild($img);
}

$finder = new DomXPath($dom);
$class_name = "tb";
$tables = $finder->query("//*[contains(@class, '$class_name')]");

$washing_machines_table = $tables->item(0);
$drying_machines_table = $tables->item(1);

echo $washing_machines_table->ownerDocument->saveXML($washing_machines_table);
echo "<br>";
echo $drying_machines_table->ownerDocument->saveXML($drying_machines_table);
