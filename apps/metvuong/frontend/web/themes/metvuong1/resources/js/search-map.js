var gmap = null, response = null;

$(document).ready(function(){
	var mapOptions = {
		center: {lat: 10.803164, lng: 106.631439},
	    zoom: 14
	};
	asynInitial(document.getElementById('map'), mapOptions, function(gmapInstance){
		gmap = gmapInstance;
		start();
	});
	
	$.get('/real-estate/result', {result: 1}, function(r){
		response = r;
		start();
	});
});

function start() {
	if(response && gmap) {
		var listResult = $('.list-results');
		
		listResult.on('click', '> li', function(){
			var marker = gmap.getMarker($(this).data('id'));
			var position = marker.getPosition();
			gmap.setCenter({lat: position.lat(), lng: position.lng()});
		});
		
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
			
			var li = '<li data-id="' + markerId + '">' +
                        '<a href="#" class="wrap-img pull-left"><img src="/frontend/web/themes/metvuong1/resources/images/IS5em8q8mi2p8p0000000000.jpg" alt=""></a>' +
                        '<div class="infor-result">' +
                            '<p class="item-title">' + address + '</p>' +
                            '<p class="type-result"><em class="fa fa-circle for-rent"></em>APARTMENT FOR RENT</p>' +
                            '<p class="rice-result">$750/mo</p>' +
                            '<p class="beds-baths-sqft">2 phòng ngủ • 1 phòng tắm • 950 m<sup>2</sup> • Built 1950</p>' +
                            '<p class="date-post-rent"><span class="toz-count">7&nbsp;</span>ngày trước</p>' +
                        '</div>' +
                    '</li>';
			list += li;
			count++;
		}
		
		$('#count-listing').text(count);
		listResult.empty().append(list);
	}
}