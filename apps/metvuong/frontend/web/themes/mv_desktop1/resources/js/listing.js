$(document).ready(function(){
	
	var map = {
		gmap: null,
		focus: 1,
		focusLevel: {
			CITY: 0,
			DISTRICT: 1,
			WARD: 2,
			DETAIL: 3
		},
		zoom: [[0,11], [12,13], [14,15], [16,21]],
		initMap: function() {
			m2Map.initial(function(gmap) {
				var options = {center: {lat: 10.783068, lng: 106.704825}, zoom: 15};
				map.gmap = new google.maps.Map(document.getElementById('map'), options);
			});
		}
	};
	
	var desktop = {
		isEnabled: false,
		enable: function() {
			this.isEnabled = true;
			
			map.initMap();
		},
		disable: function() {
			this.isEnabled = false;
		}
	};

	$(window).resize(function(){
		if($('.m-header').css('display') == 'none') {
			if(!desktop.isEnabled) {
				desktop.enable();
			}
		} else {
			if(desktop.isEnabled) {
				desktop.disable();
			}
		}
	}).trigger('resize');
	
	$('.tinh-thanh').html('');
	$('.loai-bds').html('');
	for ( var i in dataCities) {
		var item = $('<li><a href="#" data-value="'+i+'" data-order="1" class="disable">'+dataCities[i].name+'</a></li>');
		if ( i == "1" ) {
			item.find('a').removeClass('disable');
		}
		$('.tinh-thanh').append(item);
	}
	for ( var i in dataCategories) {
		var item = $('<li><a href="#" data-value="'+i+'" data-order="3">'+dataCategories[i].name+'</a></li>');
		$('.loai-bds').append(item);
	}

	$('.dropdown-common').dropdown({
		txtAdd: true,
		styleShow: 0,
		funCallBack: function (item) {
			if(item.closest('ul').hasClass('loai-bds')) {
				if(item.data('value') == '6') {
					$('#du-an-select').removeClass('hide');
					loadProjects();
				} else {
					$('#du-an-select').addClass('hide');
				}
			} else if(item.closest('ul').hasClass('quan-huyen')) {
				if($('#loai-bds').val() == '6') {
					loadProjects();
				}
			}
			
			
			var selectedCityList = $('<li data-value="'+item.data('value')+'" data-order="'+item.data('order')+'">'+item.text()+'<span class="icon arrow-left arrow-small"></span></li>');

			if ( item.closest('.tinh-thanh').length > 0 || item.is('input[type=hidden]') ) {
				var idTT = item.data('value') == undefined ? item.val() : item.data('value');

				$('.quan-huyen').html('');
				var txtDefault = $('.quan-huyen').closest('.box-dropdown').find('.val-selected').data('placeholder');
				$('.quan-huyen').closest('.box-dropdown').find('.val-selected').text(txtDefault);

				for ( var i in dataCities) {
					if ( i == idTT ) {
						for ( var j in dataCities[i].districts ) {
							var item = $('<li><a href="#" data-value="'+j+'" data-order="2">'+dataCities[i].districts[j].name+'</a></li>');
							$('.quan-huyen').append(item);
						}
						break;
					}
				}
			}
		}
	});
	
	function loadProjects() {
		var districtId = $('#quan-huyen').val();
		var cityId = $('#tinh-thanh').val();
		
		var projects = dataCities[cityId]['districts'][districtId]['projects'];
		
		for(projectId in projects) {
			var item = $('<li><a href="#" data-value="'+projectId+'">'+projects[projectId].name+'</a></li>');
			$('.du-an').append(item);
		}
	}

	$('.choice_price_dt').price_dt();

	$('.advande-search').toggleShowMobi({
        btnEvent: '.btn-submit',
        itemToggle: '.toggle-search'
    });
	
	var searchForm = $('#search-form');
	var page = 1;
	var listingListId = '#listing-list';
	var listingListWrap = '#listing-list > ul';
	var itemLoadingId = '#item-loading';
	
	if(!$(itemLoadingId).hasClass('hide')) {
		$(window).on('scroll', next);
	}
	
	function next() {
		var self = $(this);
		
		if(self.scrollTop() >= (document.body.scrollHeight - 260 - self.height())) {
			$(window).off('scroll', next);
			
			var inputPage = $('<input type="hidden" name="page" value="' + (++page) + '" />');
			searchForm.append(inputPage);
			
			$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
				r = $(r);
				
				inputPage.remove();
				
				var items = r.find('.item-listing');
				items.each(function() {
					var item = $('<li></li>');
					item.append($(this));
					$(listingListWrap).append(item);
				});
				
				var countTo = $('#count-to');
				countTo.text(Number(countTo.text()) + items.length);
				
				if(r.find(itemLoadingId).hasClass('hide')) {
					$(itemLoadingId).addClass('hide');
				} else {
					$(window).on('scroll', next);
				}

				if ( checkMobile() ) {
					$('.wrap-lazy img').lazyload({
						threshold : 200
					});
				}else {
					$('.wrap-lazy img').lazyload({
						threshold : 200,
						container: $("#listing-list")
					});
				}
				

				$(".rippler").rippler({
			        effectClass      :  'rippler-effect',
			        effectSize      :  0,      // Default size (width & height)
			        addElement      :  'div',   // e.g. 'svg'(feature)
			        duration        :  400
			    });
			});
		}
	}
	
	$('.dropdown-select').dropdown({
		ajaxSubmit: function () {
			$('button.btn-submit').data('flag',false);
			searchForm.submit();
		}
	});
	
	searchForm.submit(function(e){
		e.preventDefault();

		var btnSubmit = $(this).find('button.btn-submit');

		if ( btnSubmit.data('flag') ) {
			btnSubmit.data('flag', false);
			return;
		}else {
			btnSubmit.data('flag', true);
		}

		$(window).off('scroll', next);
		
		$(itemLoadingId).removeClass('hide');
		
		var id = '#content-holder';
		var contentHolder = $(id).html('');
		
		page = 1;
		
		$.get(searchForm.attr('action'), searchForm.serialize(), function(r){
			r = $(r);
			
			contentHolder.html(r.find(id).html());
			
			if(r.find(itemLoadingId).hasClass('hide')) {
				$(itemLoadingId).addClass('hide');
			} else {
				$(window).on('scroll', next);
			}
			
			if ( checkMobile() ) {
				$('.wrap-lazy img').lazyload({
					threshold : 200
				});
			}else {
				$('.wrap-lazy img').lazyload({
					threshold : 200,
					container: $("#listing-list")
				});
			}
		});
	});
});