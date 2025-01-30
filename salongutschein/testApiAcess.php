<?php
libxml_use_internal_errors(TRUE);

function xml_attribute($object, $attribute)
{
    if (isset($object[$attribute]))
        return (string) $object[$attribute];
}
$objXmlDocument = file_get_contents("https://api.bloom-s.de:780/api/salon/all");
$objXmlDocument = new \SimpleXMLElement($objXmlDocument);
$outputArray = array();
foreach ($objXmlDocument as $key => $value) {
    $outputArray[xml_attribute($value, 'Id')] = $value;
}
$content['salloonsData'] = $outputArray;
echo("<pre>");
print_r($content);
echo("</pre>");

?>
