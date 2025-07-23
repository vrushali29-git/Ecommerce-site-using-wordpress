<div class="widget woocommerce widget_price_filter"><h2 class="widgettitle">Price</h2>
	<form method="get" action="#">
		<div class="price_slider_wrapper">
			<div class="price_slider ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" style="">
				<div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div>
				<span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span>
				<span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span>
			</div>
			<div class="price_slider_amount" data-step="10">
				<label class="screen-reader-text" for="min_price">Min price</label>
				<input type="text" id="min_price" name="min_price" value="0" data-min="0" placeholder="Min price" style="display: none;">
				<label class="screen-reader-text" for="max_price">Max price</label>
				<input type="text" id="max_price" name="max_price" value="30" data-max="30" placeholder="Max price" style="display: none;">
				<button type="submit" class="button">Filter</button>
				<div class="price_label" style="display: block;">
					Price: <span class="from">$0</span> — <span class="to">$30</span>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</form>
</div>
