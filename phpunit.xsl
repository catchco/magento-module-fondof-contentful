<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes"/>

    <xsl:template match="@* | node()">
        <xsl:copy>
            <xsl:apply-templates select="@* | node()"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template match="/phpunit/filter/blacklist/directory[last()]">
        <xsl:copy-of select="."/>
        <xsl:element name="directory">
            <xsl:attribute name="suffix">.php</xsl:attribute>
            <xsl:text>../vendor</xsl:text>
        </xsl:element>
    </xsl:template>
</xsl:stylesheet>