<?php
require '../vendor/autoload.php';

use Machine3R\XmlBuilder\XmlBuilder;

/**
 * @see https://www.w3schools.com/xml/schema_example.asp
 */
$xsd = './schemas/shiporder.xsd';
$xsd = './schemas/shiporder.divided.xsd';
$xsd = './schemas/shiporder.named-types.xsd';

$shiporder = new XmlBuilder('shiporder');
$shiporder
	->attr('xmlns:xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance")
	->attr('xmlns:xsi:noNamespaceSchemaLocation',"$xsd")
	->attr('orderid', 889923)
	->orderperson('John Smith')->end()
	->shipto()
		->name('Ola Nordmann')->end()
		->address('Langgt 23')->end()
		->city('4000 Stavanger')->end()
		->country('Norway')->end()
	->end()
;

$item1 = $shiporder->item()
		->title('Empire Burlesque')->end()
		->note('Special Edition')->end()
		->quantity(1)->end()
		->price('10.90')->end()
	->end()
;

$item2 = $shiporder->item();
$item2->title('Hide your heart')->end();
$item2->quantity(1)->end();
$item2->price('9.90')->end();
$item2->end();

$shiporder->validate($xsd);
echo $shiporder;