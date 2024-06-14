<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">
        <xsl:variable name="filteredItems" select="goods/item[sold_quantity > 0]" />

        <xsl:choose>
            <xsl:when test="$filteredItems">
                <h2 style="text-align:center; margin: 50px 0 30px 0;">Processing Form</h2>
                <table class="item-table">
                    <tr>
                        <th class="table-header">Item Number</th>
                        <th class="table-header">Item Name</th>
                        <th class="table-header">Price</th>
                        <th class="table-header">Quantity Available</th>
                        <th class="table-header">Quantity on Hold</th>
                        <th class="table-header">Quantity Sold</th>
                    </tr>
                    <xsl:for-each select="$filteredItems">
                        <tr>
                            <td class="item-cell id" width="75px">
                                <xsl:value-of select="id"/>
                            </td>
                            <td class="item-cell" align="center">
                                <xsl:value-of select="name"/>
                            </td>
                            <td class="item-cell">
                                $<xsl:value-of select="price"/>
                            </td>
                            <td class="item-cell" align="center">
                                <xsl:value-of select="quantity - hold_quantity - sold_quantity"/>
                            </td>
                            <td class="item-cell">
                                <xsl:value-of select="hold_quantity"/>
                            </td>
                            <td class="item-cell sold-quantity">
                                <xsl:value-of select="sold_quantity"/>
                            </td>
                        </tr>
                    </xsl:for-each>
                    <tr>
                        <td colspan="6" style="text-align:center;" >
                            <button class="process-button" onclick ="processItem()">Process</button>
                        </td>
                    </tr>
                </table>
            </xsl:when>
            <xsl:otherwise>
                <p class="out-of-stock-message">Sold items has been processed, no new purchase yet!</p>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>


</xsl:stylesheet>
