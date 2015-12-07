(function() {
	"use strict";

	for( var i in dataCities ) {
        var $itemTinhThanh = $('<li data-id-tt='+i+'><a href="#valTT">'+dataCities[i].name+'</a></li>');
        $('.list-tinh-thanh').append($itemTinhThanh);
    }

    $.getJSON(url_loaibds, function(result){
        arrJSONReturn.objLoai = result;
        for( var i = 0; i < result.length; i++ ) {
            var $item = $('<li data-id-loai='+result[i].id+'><a data-template-show="'+result[i].template+'" href="#valLoai">'+result[i].ten_loai+'</a></li>');
            $('.list-loai-bds').append($item);   
        }
    });

    $.getJSON(url_ttuc, function(result){
        arrJSONReturn.objTTuc = result;
        for( var i = 0; i < result.length; i++ ) {
            var $item = $('<li data-id-ttuc='+result[i].id+'><a data-template-show="'+result[i].template+'" href="#valTTuc">'+result[i].ten_tt+'</a></li>');
            $('.list-loai-tt').append($item);   
        }
    });



	var objEvent = {
			itemInput: $('#searchInput'),
			tabsSearch: $('.search-select a'),
	        flagEnd : false,
	        countStep : 1,
	        countCurrent : 0,
	        wrapListSuggest: $('.type-search ul'),
	        wrapStep: $('.search-wrap'),
	        lenghtSuggest : 0,
	        flagOpenSugget : false,
	        flagTrigger : '',
	        current: 0,
	        next: 0,
	        timeoutClose: '',
	        editItem: '',
	        icon: $('#search-kind button span'),
	        iconScrollSearch : $('.icon-selected'),
	        init: function() {
	        	objEvent.searchEvent();
	        	objEvent.tabSearchEvent();
	        	objEvent.selectItem();
	        	objEvent.btnclose();
	        	objEvent.reOpenBySuggest();
	        	objEvent.removeSuggest();
	        	loadCost.init();
	        	objEvent.centerBox();
	        },
	        searchEvent: function() {
	        	objEvent.tabsSearch.each(function() {
	        		var txtPlaceholder = $(this).data('placeholder');
	        		if( $(this).parent().hasClass('active') ) {
	        			objEvent.itemInput.attr('placeholder', txtPlaceholder);	
	        			objEvent.flagTrigger = $(this).attr('rel');
	        			itemTabs(objEvent.flagTrigger);
	        		}
	        	});
	        	objEvent.tabsSearch.on('click', function(e) {
	        		e.preventDefault();
	        		var _this = $(this),
			            txtPlaceholder = _this.data('placeholder'),
			            itemSearch = _this.attr('rel');

			        objEvent.reset();

			        itemTabs(itemSearch);
			        
			        objEvent.tabsSearch.parent().removeClass('active');
			        objEvent.itemInput.attr('placeholder', txtPlaceholder);
			    	_this.parent().addClass('active');

			    	if( _this.closest('.search-dropdown').length > 0 ) {
	                    _this.closest('.search-dropdown').removeClass('search-dropdown');
	                }
			    	
			        return false;
	        	});

				function itemTabs(itemTab) {
			        switch(itemTab) {
			            case '#dd-search':
			                //something
			                objEvent.flagTrigger = '#dd-search';
			                objEvent.icon.html('<em class="fa fa-search"></em>');
			                objEvent.iconScrollSearch.find('span').html('<em class="fa fa-home"></em><em class="fa fa-search"></em>');
			                break;
			            case '#dd-dky':
			                //_this.trigger( 'real-estate/post', [{data: '1'}, 'something'] );
			                objEvent.icon.html('<em class="fa fa-pencil-square-o"></em>');
			                objEvent.iconScrollSearch.find('span').html('<em class="fa fa-home"></em><em class="fa fa-pencil-square-o"></em>');
			                objEvent.flagTrigger = '#dd-dky';
			                break;
			            case '#dd-news':
			                //something
			                //_this.trigger( 'real-estate/news', [{data: '1'}, 'something'] );
			                objEvent.icon.html('<em class="fa fa-file-text"></em>');
			                objEvent.iconScrollSearch.find('span').html('<em class="fa fa-home"></em><em class="fa fa-file-text"></em>');
			                objEvent.flagTrigger = '#dd-news';
			                objEvent.countStep = "news";
			                break;
			        }
			    }
	        },
	        tabSearchEvent: function() {
	        	objEvent.itemInput.on('click', function (e) {
	        		e.preventDefault();
	        		var _this = $(this);
	        		objEvent.editItem = '';
			        if( !objEvent.flagEnd )
			        	objEvent.open(objEvent.countStep);
				});
	        },
	        open: function(countStep) {//1. edit, 0. open normal
	        	objEvent.countStep = countStep;
	            objEvent.wrapStep.addClass('hidden-effect');

	            isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").removeClass('hidden-effect') : $('#step-'+objEvent.countStep).removeClass('hidden-effect');

	            setTimeout(function() {
	                isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").addClass('active') : $('#step-'+objEvent.countStep).addClass('active');
	            	isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").find('input').eq(0).focus() : '';
	            }, 30);

	            clearTimeout(objEvent.timeoutClose);
	        },
	        close: function() {
	            $('.search-wrap').removeClass('edit-suggest');

	            $('.search-wrap').removeClass('active');

	            objEvent.timeoutClose = setTimeout(function() {
	                $('.search-wrap').addClass('hidden-effect');
	            }, 300);

	            $('.type-search li').removeClass('active');

	            objEvent.editItem = '';
	        },
	        btnclose: function() {
	            $('.btn-close-search').on('click',function(e) {
	                e.preventDefault();

	                $('.type-search li').removeClass('active');

	                $(this).closest('.search-wrap').removeClass('active');
	                objEvent.close();

	                return false;
	            });
	        },
	        selectItem: function() {
	        	$(document).on('click', '.search-item > ul a', function(e) {
	                e.preventDefault();
	                var _this = $(this),
	                    txt = _this.text(),
	                    $itemSuggest = $('<li data-step="'+objEvent.countStep+'" data-step-show='+_this.attr('href')+'><i>x</i><span></span></li>'),
	                    itemId = '',
	                    templateSuggest = _this.data('templateShow');

	                if( _this.parent().data('idTt') != undefined ) {
	                    $('#valTT').val(_this.parent().data('idTt'));
	                    itemId = _this.parent().data('idTt');

	                    for( var i in dataCities ) {
	                        if( i == _this.parent().data('idTt') ) {
	                            $('.list-quan-huyen').html('');
	                            for( var j in dataCities[i].districts ) {
	                                var $item = $('<li data-id-qh='+j+'><a href="#valQh">'+dataCities[i].districts[j].name+'</a></li>');
	                                $('.list-quan-huyen').append($item);
	                            }
	                        }
	                    }

	                }

	                if( _this.parent().data('idQh') != undefined ) {
	                    $('#valQh').val(_this.parent().data('idQh'));
	                    itemId = _this.parent().data('idQh');
	                }else if( _this.parent().data('idLoai') != undefined ) {
	                    $('#valLoai').val(_this.parent().data('idLoai'));
	                    itemId = _this.parent().data('idLoai');
	                }else if( _this.parent().data('idTtuc') != undefined ) {
	                    $('#valTTuc').val(_this.parent().data('idTtuc'));
	                    itemId = _this.parent().data('idTtuc');
	                }

	                $itemSuggest.attr('data-item-id',itemId);

	                if( !objEvent.editItem ) {
	                    
	                    $itemSuggest.find('span').text(txt);
	                    
	                    objEvent.wrapListSuggest.append($itemSuggest);

	                    if( objEvent.flagTrigger == '#dd-dky' && objEvent.countStep === 3 ) {
	                        _this.trigger( 'real-estate/post', [{data: '1'}, 'something'] );
	                        objEvent.flagEnd = true;
	                    }

	                    setTimeout(function() {
	                        if( objEvent.flagTrigger == '#dd-news' ) {
	                            _this.trigger( 'real-estate/news', [{data: '1'}, 'something'] );
	                        }
	                    },200);
	                    
	                    if( templateSuggest !== undefined ) {
	                		objEvent.countStep = templateSuggest;
	                	}

	                    objEvent.checkCounter();

	                    var txtStep;
	                    txtStep = isNaN(objEvent.countStep) ? $(".search-wrap[data-template="+objEvent.countStep+"]").data('txtStep') : $('#step-'+objEvent.countStep).data('txtStep');

	                    objEvent.changeTextHolder(txtStep);

	                }else {
	                    objEvent.wrapListSuggest.find('li[data-step='+objEvent.editItem+'] span').text(txt);
	                    if( templateSuggest !== undefined ) {
	                    	objEvent.countStep = templateSuggest;
	                    }
	                    objEvent.editItem = '';
	                }
	                
	                objEvent.close();
	                
	                objEvent.wrapListSuggest.show();

	                objEvent.resizeWidthInput();

	                if( _this.closest('.search-wrap').data('end') ) {
	                	objEvent.flagEnd = true;
	                }

	                if( !objEvent.flagEnd ) {
	                	objEvent.open(objEvent.countStep);
	                }

	                return false;
	            });
	        },
	        removeSuggest: function() {
	            $(document).on('click', '.type-search li i',function(e) {
	                e.preventDefault();
	                var _this = $(this),
	                    $parentList = _this.parent(),
	                    getStep = $parentList.index(),
	                    count = 0;

	                for( var i = objEvent.wrapListSuggest.find('li').length; i > getStep ; i-- ) {
	                	objEvent.wrapListSuggest.find('li').eq(i-1).remove();
	                }
	                
	                objEvent.resizeWidthInput();
	                
	                objEvent.countStep = $parentList.data('step');
	                objEvent.flagEnd = false;

	                if( !isNaN(objEvent.countStep) ) {
	                	objEvent.changeTextHolder($('#step-'+objEvent.countStep).data('txtStep'));
	                }else {
	                	objEvent.changeTextHolder($('.search-wrap[data-template='+objEvent.countStep+']').data('txtStep'));
	                }
	                
				});
	        },
	        changeTextHolder: function(txtStep) {
	        	objEvent.itemInput.attr('placeholder', txtStep);
	        },
	        resizeWidthInput: function() {
	        	objEvent.itemInput.hide();
	            var wInput = $('.type-search').width() - objEvent.wrapListSuggest.outerWidth();
	            objEvent.itemInput.css('width',wInput+'px').show();
	        },
	        reOpenBySuggest: function() {
	            $(document).on('click', '.type-search li span',function(e) {
	                e.preventDefault();
	                
	                var _this = $(this),
	                    boxId = _this.parent().data('step');

	                objEvent.editItem = boxId;

	                $('.type-search li').removeClass('active');
	                _this.parent().addClass('active');
	                
	                for( var i = objEvent.lenghtSuggest; i >= 0 ; i-- ) {
	                    if( i > boxId ) {
	                        $('.type-search li').eq(i-1).remove();
	                    }
	                }

	                objEvent.resizeWidthInput();

	                objEvent.open(boxId);
	                setTimeout(function() {
	                	objEvent.flagEnd = false;
	                	objEvent.checkCounter();
	                },35);
	            });
	        },
	        checkCounter: function() {
	        	//l('countStep: '+objEvent.countStep);
	            objEvent.lenghtSuggest = objEvent.wrapListSuggest.find('li').length;
	            if( !isNaN(objEvent.countStep) ) { // is a number
	            	objEvent.countStep += 1;
	            }else { // is a string
	            	return;
	            }
	        },
	        reset: function() {
	            objEvent.countStep = 1;
	            objEvent.lenghtSuggest = 0;
	            objEvent.wrapListSuggest.hide().find('li').remove();
	            objEvent.itemInput.removeAttr('style');
	            objEvent.flagEnd = false;
	            $('.getValSuggest').val('');
	        },
	        updateSuggert: function(countStep, lenghtSuggest) {
	            objEvent.countStep = countStep;
	            objEvent.lenghtSuggest = lenghtSuggest;
	        },
	        centerBox: function() {
	        	var logo = $('div.wrap-page-home .logo-home'),
	        		searchBox = $('div.wrap-page-home .box-search-header'),
	        		wLogo = logo.outerWidth(),
	        		hLogo = logo.outerHeight(),
	        		wSearchBox = searchBox.outerWidth(),
	        		hSearchBox = searchBox.outerHeight(),
	        		wWin = $('div.wrap-page-home .wrap-search-home').outerWidth(),
	        		hWin = $(window).outerHeight(),
	        		topCenterLogo = Math.floor(hWin/2 - hLogo/2 - hLogo - 30),
	        		leftCenterLogo = Math.floor(wWin/2 - wLogo/2),
	        		topCenterSearch = Math.floor(hWin/2 - hSearchBox/2) + 30,
	        		leftCenterSearch = Math.floor(wWin/2 - wSearchBox/2);

	        	logo.css({
	        		'transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)',
	        		'-moz-transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)',
	        		'-webkit-transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)',
	        		'-ms-transform' : 'translate3d( '+leftCenterLogo+'px, '+topCenterLogo+'px, 0)'

	        	});
	        	searchBox.css({
	        		'transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)',
	        		'-webkit-transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)',
	        		'-moz-transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)',
	        		'-ms-transform' : 'translate3d( '+leftCenterSearch+'px, '+topCenterSearch+'px, 0)'
	        	});

	        	setTimeout(function() {
	        		logo.show().css({
	        			visibility: 'visible'
	        		});
	        		logo.addClass('aniTopDown');
	        		searchBox.show().css({
	        			visibility: 'visible'
	        		});
	        		searchBox.addClass('aniTopDown');
	        	},100);
	        }
	    };

	var loadCost = {
			inputSubmitVal: $('.val-cost'),
			wrapList: $('.wrap-cost-bds'),
			choiceEvent: $('.wrap-cost-bds li span'),
			inputEvent: $('.cost-value'),
			flag: '',
			init: function () {
				loadCost.show();
				loadCost.clickChoice();
			},
			show: function () {
				loadCost.inputEvent.on('click', function (e) {
					e.preventDefault();
					var _this = $(this),
						tabShow = _this.closest('.box-cost').data('tab');

					loadCost.flag = loadCost.toggleCost(tabShow);
					loadCost.hide();
					loadCost.open(tabShow);
				});
			},
			open: function (item) {
				loadCost.wrapList.addClass('hidden-cost');
				$('.box-cost[data-tab='+item+'] .wrap-cost-bds').removeClass('hidden-cost');
				setTimeout(function() {
	                $('.box-cost[data-tab='+item+'] .wrap-cost-bds').addClass('active');
	            }, 30);
			},
			hide: function () {
				loadCost.wrapList.removeClass('active');
				loadCost.wrapList.addClass('hidden-cost');
			},
			toggleCost: function (item) {
				var itemGet = item == 'min' ? 'max' : 'min';
				return itemGet;
			},
			clickChoice: function() {
				loadCost.choiceEvent.on('click', function() {
					var _this = $(this),
						textChoice = _this.html(),
						costValueChoice = _this.parent().data('cost'),
						costRate = _this.parent().data('unit'),
						tabIndex = _this.closest('.box-cost').data('tab');

					
					$('.box-cost[data-tab='+tabIndex+'] .cost-value').val(textChoice);
					$('.val-cost[data-val-cost='+tabIndex+']').val(costValueChoice);

					loadCost.hide();

					if( loadCost.flag == 'max' ) {
						$('.box-cost[data-tab='+loadCost.flag+'] .cost-value').val('');
						$('.box-cost[data-tab='+loadCost.flag+'] ul li').each(function(i) {
							var _this = $(this),
								valCostList = _this.data('cost');

							if( costValueChoice > valCostList ) {
								_this.hide();
							}else {
								_this.show();
							}
						});

						loadCost.open(loadCost.flag);
						
						loadCost.flag = 'min';
					}
				});
			}
		};

    //start click outside
    $(document).on('click', function(e) {
        var container = $(".outsideevent");
        if (!container.is(e.target) && container.has(e.target).length === 0){
        	if( $(".wrap-cost-bds").hasClass('active') ) {
        		loadCost.hide();
        	}else {
        		$('.btn-close-search').trigger('click');
        	}
        }
    });
    //end click outside

    objEvent.init();

    $(window).resize(function() {
    	objEvent.centerBox();
    });


})(jQuery);


function animateSearch() {
    var $boxSearch = $('.box-search-header'),
        wBox = $boxSearch.outerWidth(),
        wWin = $('.wrap-search-home').outerWidth(),
        cBox = Math.floor(wWin/2 - wBox/2);
    setTimeout(function() {
        $('.wrap-search-home .logo-home').addClass('ani-logo').css({
            'transform': 'translate3d( 0, 0, 0)',
            '-webkit-transform': 'translate3d( 0, 0, 0)',
            '-moz-transform': 'translate3d( 0, 0, 0)',
            '-ms-transform': 'translate3d( 0, 0, 0)'
        });

        $('.box-search-header').addClass('ani-search').css({
            'transform': 'translate3d('+cBox+'px, 10px, 0px)',
            '-webkit-transform': 'translate3d('+cBox+'px, 10px, 0px)',
            '-moz-transform': 'translate3d('+cBox+'px, 10px, 0px)',
            '-ms-transform': 'translate3d('+cBox+'px, 10px, 0px)'
        });
        $('.wrap-search-home .logo-home').addClass('ani-logo');
        $('.box-search-header').addClass('ani-search');
        setTimeout(function() {
            $('header').addClass('border-shadow');
            l(1);
        },500);
    },500);
}