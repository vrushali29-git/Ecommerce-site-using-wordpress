<?php

defined( 'ABSPATH' ) || exit;

?>
<div class="sp-thankyou-order-details">
	<section class="woocommerce-order-details">
	<h2 class="woocommerce-order-details__title">Order details</h2>
	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
		<thead>
		<tr>
			<th class="woocommerce-table__product-name product-name">Product</th>
			<th class="woocommerce-table__product-table product-total">Total</th>
		</tr>
		</thead>
		<tbody>
		<tr class="woocommerce-table__line-item order_item">
			<td class="woocommerce-table__product-name product-name">
			<a href="#">T-Shirt</a>
			<strong class="product-quantity">×&nbsp;1</strong>
			</td>
			<td class="woocommerce-table__product-total product-total">
			<span class="woocommerce-Price-amount amount">
				<bdi>
				<span class="woocommerce-Price-currencySymbol">$</span>200.00 </bdi>
			</span>
			</td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<th scope="row">Subtotal:</th>
			<td>
			<span class="woocommerce-Price-amount amount">
				<span class="woocommerce-Price-currencySymbol">$</span>200.00 </span>
			</td>
		</tr>
		<tr>
			<th scope="row">Discount:</th>
			<td>- <span class="woocommerce-Price-amount amount">
				<span class="woocommerce-Price-currencySymbol">$</span>200.00 </span>
			</td>
		</tr>
		<tr>
			<th scope="row">Shipping:</th>
			<td>Flat rate</td>
		</tr>
		<tr>
			<th scope="row">Total:</th>
			<td>
			<span class="woocommerce-Price-amount amount">
				<span class="woocommerce-Price-currencySymbol">$</span>0.00 </span>
			</td>
		</tr>
		</tfoot>
	</table>
	</section>
</div>
