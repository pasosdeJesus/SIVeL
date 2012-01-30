<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE xsl:stylesheet [

<!ENTITY % confv SYSTEM "confv.ent">
%confv;
]>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version='1.0'
        xmlns="http://www.w3.org/TR/xhtml1/transitional"
	exclude-result-prefixes="#default"> 

	<xsl:import href="&DOCBOOK-XSL;/html/chunk.xsl"/>

	<xsl:include href="&REPASA-DOCBOOK-XSL-HTML;"/>

	<xsl:variable name="toc.section.depth">3</xsl:variable>
	<xsl:variable name="use.id.as.filename">1</xsl:variable>
	<xsl:variable name="root.filename">index</xsl:variable>
	<xsl:variable name="section.autolabel">1</xsl:variable>
	<xsl:variable name="citerefentry.link">1</xsl:variable>
		
</xsl:stylesheet>

