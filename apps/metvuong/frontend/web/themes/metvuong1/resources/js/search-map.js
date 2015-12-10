var gmap = null, response = null, listResult;

$(document).ready(function(){
	listResult = $('.list-results');
	var mapOptions = {
		center: {lat: 10.803164, lng: 106.631439},
	    zoom: 14
	};
	asynInitial(document.getElementById('map'), mapOptions, function(gmapInstance){
		gmap = gmapInstance;
		start();
	});
	
	search(function(r){
		response = r;
		start();
	});
});

function search(callback) {
	var searchForm = $('#map-search-form');
	
	$.get(searchForm.attr('action'), searchForm.serialize(), function(r) {
		if(callback) {
			callback(r);
		}
	});
}

function start() {
	if(response && gmap) {
		listResult.on('click', '> li', function(){
			var marker = gmap.getMarker($(this).data('id'));
			var position = marker.getPosition();
			gmap.setCenter({lat: position.lat(), lng: position.lng()});
			
			$.get('/ads/detail', {id: $(this).data('detail')}, function(response){
				$('#detail-listing').html($(response).html()).modal();
				$('.gallery-detail').imagesLoaded()
				 	.always( function( instance ) {
					    // all loaded (broken + success)
				 		console.log('all images loaded');
				 	})
				 	.done( function( instance ) {
				 		setTimeout(function() {
				 			$('#detail-listing .bxslider').bxSlider({
				                moveSlides: 1,
				                startSlide: 0,
				                minSlides: 1,
				                maxSlides: 2,
				                slideWidth: 444,
				                startSlide: 0,
				                onSliderLoad: function() {
				                    this.infiniteLoop = false;
				                    this.hideControlOnEnd = true;
				                    scrollFixed();
				                }
				            });
							lightbox.option({
					            'resizeDuration': 300,
					            'fadeDuration': 400
					        });
				 		},500);
					    
				 	})
				 	.fail( function() {
				 		console.log('all images loaded, at least one is broken');
				 	})
				 	.progress( function( instance, image ) {
				 		var result = image.isLoaded ? 'loaded' : 'broken';
				 		console.log( 'image is ' + result + ' for ' + image.img.src );
				 	});
				
			});
		});
		
		$('#order-by-tab a').click(function(){
			$('#order-by').val($(this).data('order'));
			search(function(r){
				response = r;
				loadListing();
			});
		});
		
		loadListing();
	}
}

function loadListing() {
	var list = '';
	var count = 0;
	for(index in response) {
		var product = response[index];
		var city = dataCities[product.city_id];
		var district = city['districts'][product.district_id];
		var ward = district['wards'][product.ward_id];
		var street = district['streets'][product.street_id];
		var address = product.home_no + ' ' + street.pre + ' ' + street.name + ', ' + ward.pre + ' ' + ward.name + ', ' + district.pre + ' ' + district.name + ', ' + city.name;

		var marker = new Marker({
			draggable: false,
		    position: {lat: Number(product.lat), lng: Number(product.lng)}
		});
		
		var markerId = gmap.addMarker(marker, true);
		var type = (product.type == 1) ? 'BÁN' : 'CHO THUÊ';
		var category = categories[product.category_id]['name'].toUpperCase();
		var price = (product.type == 1) ? product.price : product.price + '/tháng';
		
		var li = '<li data-detail="' + product.id +'" data-id="' + markerId + '">' +
                    '<div class="bgcover wrap-img pull-left" style="background-image:url('+product.image_url+')"><a href="#" class=""></a></div>' +
                    '<div class="infor-result">' +
                        '<p class="item-title">' + address + '</p>' +
                        '<p class="type-result"><em class="fa fa-circle for-rent"></em>' + category + ' ' + type + '</p>' +
                        '<p class="rice-result">' + price + '</p>' +
                        '<p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>' +
                        '<p class="date-post-rent">' + product.previous_time + '</p>' +
                    '</div>' +
                '</li>';
		list += li;
		count++;
	}
	
	$('#count-listing').text(count);
	listResult.empty().append(list);
}