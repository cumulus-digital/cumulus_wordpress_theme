(function($, theme_vars, window, undefined) {

	if ( ! theme_vars) {
		return;
	}

	$('.image-flipper').each(function() {
		var that = this,
			$that = $(that),
			cat = $that.data('category'),
			timer = null,
			timeout = 2000;

		if ( ! cat) {
			console.log('fail', cat);
			return;
		}
		$.ajax({
			url: theme_vars.ajaxurl,
			data: {
				'action': 'get_images_by_category',
				'category': cat,
			},
			type: 'POST',
			dataType: 'jsonp',
		}).done(function(data) {
			var images = [];
			data.forEach(function(img, i) {
				images.push($('<img/>', {src: img.guid, class: i < 1 ? 'image-flipper-current' : ''}));
			});
			var container = $('<div/>');
			container.append(images);
			$that.append(container);

			timer = setTimeout(nextImage, timeout);
		});

		function nextImage() {
			var $current = $that.find('.image-flipper-current');
			var $next = $current.next('img');
			if ( ! $next.size()) {
				$next = $current.siblings('img').eq(0);
			}
			$current.removeClass('image-flipper-current');
			$next.addClass('image-flipper-current');
			timer = setTimeout(nextImage, timeout);
		}
	});

}(jQuery, window.self.theme_vars || null, window.self));