<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">
        <xsl:variable name="filteredItems" select="goods/item[quantity - hold_quantity - sold_quantity > 0]" />

        <xsl:choose>
            <xsl:when test="$filteredItems">
                <h2 style="text-align:center; margin: 50px 0 30px 0;">Shopping Catalogue</h2>
                <table class="item-table">
                    <tr>
                        <th class="table-header">Item Number</th>
                        <th class="table-header">Name</th>
                        <th class="table-header">Description</th>
                        <th class="table-header">Price</th>
                        <th class="table-header">Quantity</th>
                        <th class="table-header">Add</th>
                    </tr>
                    <xsl:for-each select="$filteredItems">
                        <tr>
                            <td id="id" class="item-cell" width="75px">
                                <xsl:value-of select="id"/>
                            </td>
                            <td id="name" class="item-cell" align="center">
                                <xsl:value-of select="name"/>
                            </td>
                            <td id="description" class="item-cell">
                                <xsl:value-of select="substring(description, 1, 20)"/>
                            </td>
                            <td id="price" class="item-cell" align="center">
                                $<xsl:value-of select="price"/>
                            </td>
                            <td id="quantity" class="item-cell">
                                <xsl:value-of select="quantity - hold_quantity - sold_quantity"/>
                            </td>
                            <td class="item-cell">
                                <button class="add-button" onclick ="AddRemoveItem('Add', {id}, {price})">Add one to cart</button>
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
            </xsl:when>
            <xsl:otherwise>
                <p class="out-of-stock-message">Goods out of stock</p>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>


</xsl:stylesheet>
