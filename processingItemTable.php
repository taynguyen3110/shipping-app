<?php
// load XML file into a DOM element
$xmlDoc = new DOMDocument;
$xmlDoc->formatOutput = true;
$xmlDoc->load("data/goods.xml");
// load XSL file into a DOM document
$xslDoc = new DomDocument;
$xslDoc->load("data/goodsProcess.xsl");
// create a new XSLT processor object
$proc = new XSLTProcessor;
// load the XSL DOM object into the XSLT processor
$proc->importStyleSheet($xslDoc);
// transform the XML document using the configured XSLT processor
$strXml= $proc->transformToXML($xmlDoc);
// echo the transformed HTML back to the client
echo ($strXml);

?>
