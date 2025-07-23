/**
 * Meta Box.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

(function ($) {
	/**
	 * Tabs.
	 *
	 * @since	1.0.0
	 */
	$('.kata-nav-tabs').on('click', 'a', function (event) {
		event.preventDefault();

		var $li = $(this).parent();
		var $content = $li.closest('.kata-tabs-wrap').find('.' + $li.data('target'));

		$li.addClass('active').siblings().removeClass('active');
		$content.show().siblings().hide();

		if (typeof google !== 'undefined' && (typeof google === 'object' || typeof google.maps === 'object')) {
			$('.rwmb-map-field').each(function () {
				var mapController = $(this).data('mapController');
				if (typeof mapController !== 'undefined' && typeof mapController.map !== 'undefined') {
					var map = mapController.map;
					var zoom = map.getZoom();
					var center = map.getCenter();
					google.maps.event.trigger(map, 'resize');
					map.setZoom(zoom);
					map.setCenter(center);
				}
			});
		}
	});
	$('.kata-nav-tabs').children('li').first().addClass('active').children('a').trigger('click');

	/**
	 * Dependecy.
	 *
	 * @since	1.0.0
	 */
	var parents = [];
	$('.rwmb-field[data-dependency]').each(function (index, element) {
		var $field = $(element);
		var dataDependency = $field.attr('data-dependency');
		var $parent = $('#' + dataDependency);
		if ($parent.is(':checked')) {
			$field.show();
		} else {
			$field.hide();
		}
		if (parents.indexOf(dataDependency) === -1) {
			parents.push(dataDependency);
		}
	});
	$.each(parents, function (indexInArray, valueOfElement) { 
		var $parent = $('#' + valueOfElement);
		$parent.on('change', function() {
			var $childs = $('.rwmb-field[data-dependency="' + valueOfElement + '"]');
			if ($parent.is(':checked')) {
				$childs.show();
			} else {
				$childs.hide();
			}
		});
	});
})(jQuery);