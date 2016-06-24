/*!
 * author: Heminei
 * site: https://github.com/heminei/jquery-hemi-intro
 * email: heminei@heminei.com
 * v1.2
 */
(function ($) {
	var pluginName = "hemiIntro";

	$[pluginName] = function (userOptions) {
		var plugin = this;

		var defaultOptions = {
			debug: false,
			steps: [],
			startFromStep: 0,
            backdrop: {
                element: $("<div>"),
                class: "hemi-intro-backdrop"
            },
            popover: {
                template: '<div class="popover hemi-intro-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
            },
            buttons: {
                holder: {
                    element: $("<div>"),
                    class: "hemi-intro-buttons-holder"
                },
                next: {
                    element: $("<a href='javascript:void(0)' class='btn-tour btn'>"+lajax.t('Next')+"</a>"),
                    class: "btn btn-primary"
                },
                noThanks: {
                	element: $("<a href='javascript:void(0)' class='btn-tour btn mgL-10'>"+lajax.t('No Thanks')+"</a>"),
                    class: "btn btn-primary"
                },
                finish: {
                    element: $("<a href='javascript:void(0)' class='btn-tour btn'>"+lajax.t('Finish')+"</a>"),
                    class: "btn btn-primary"
                }
            },
            welcomeDialog: {
                show: false,
                selector: "#myModal"
            },
            scroll: {
                anmationSpeed: 500
            },
            currentStep: {
                selectedClass: "hemi-intro-selected"
            },
			init: function (plugin) {

			},
			onLoad: function (plugin) {

			},
			onStart: function (plugin) {

			},
			onBeforeChangeStep: function (plugin, step) {

			},
			onAfterChangeStep: function (plugin, step) {

			},
			onShowModalDialog: function (plugin, modal) {

			},
			onHideModalDialog: function (plugin, modal) {

			},
			onComplete: function (plugin) {

			}
		};

		plugin.options = $.extend(true, defaultOptions, userOptions);
		plugin.options.init(plugin); //CALLBACK

		var currentIndex = plugin.options.startFromStep;
		var currentElement = null;
		var currentStep = null;

		plugin.backdrop = plugin.options.backdrop.element.clone().addClass(plugin.options.backdrop.class);

		plugin.options.onLoad(plugin); //CALLBACK

		plugin.start = function () {
			
			if ( parseInt($('#checkUserFirst').val()) || checkMobile() ) return;

			plugin.options.onStart(plugin); //CALLBACK

			if (plugin.options.welcomeDialog.show) {
				var modal = $(plugin.options.welcomeDialog.selector);
				if (modal.length > 0) {
					modal.on('show.bs.modal', function (e) {
						plugin.options.onShowModalDialog(plugin, modal); //CALLBACK
					});
					modal.on('hidden.bs.modal', function (e) {
						plugin.options.onHideModalDialog(plugin, modal); //CALLBACK
						plugin.backdrop.appendTo("body");
						goToStep(currentIndex);
					});
					modal.modal("show");
				} else {
					debugLog(pluginName + ":", "Modal '" + plugin.options.welcomeDialog.selector + "' not found");
					plugin.backdrop.appendTo("body");
					goToStep(currentIndex);
				}
			} else {
				//plugin.backdrop.appendTo("body");
				goToStep(currentIndex);
				if ( plugin.options.steps[currentIndex].selector != '.dt-header' ) {
					$('.dt-header').append('<div class="bg-over"></div>');
				}
			}
		};

		plugin.next = function () {
			if (plugin.options.steps[currentIndex + 1]) {
				goToStep(currentIndex + 1);
			} else {
				plugin.finish();
			}
		};

		plugin.prev = function () {
			if (currentIndex - 1 < 0) {
				goToStep(currentIndex);
			} else {
				goToStep(currentIndex - 1);
			}
		};

		plugin.finish = function () {
			onFinish();
		};

		plugin.goToStep = function (index) {
			goToStep(index);
		};

		plugin.getCurrentStep = function () {
			return currentStep;
		};

		var goToStep = function (index) {
			if (plugin.options.steps[index]) {
				var step = plugin.options.steps[index];

				if ($(step.selector).length > 0) {
					removeCurrentStep();

					currentElement = $(step.selector);
					currentIndex = index;
					currentStep = step;

					if ( step.selector == "#map-wrap" ) {
						currentElement.parent().css({
							'z-index': 11
						});
					}

					plugin.backdrop.insertAfter(currentElement);

					plugin.options.onBeforeChangeStep(plugin, step); //CALLBACK

					currentElement.addClass(plugin.options.currentStep.selectedClass);

					scrollToElement(function () {
						var template = $(plugin.options.popover.template);
						var uniq = "id" + Math.random().toString(30).slice(2);
						var buttonsHolder = plugin.options.buttons.holder.element.clone().addClass(plugin.options.buttons.holder.class);
						var button;
						if (plugin.options.steps[index + 1]) {
							button = plugin.options.buttons.next.element.clone();
							button.addClass(plugin.options.buttons.next.class).addClass(uniq);
							buttonsHolder.append(button);
						} else {
							button = plugin.options.buttons.finish.element.clone();
							button.addClass(plugin.options.buttons.finish.class).addClass(uniq);
							buttonsHolder.append(button);
						}

						if (plugin.options.steps[index + 1]) {
							button = plugin.options.buttons.noThanks.element.clone();
							button.addClass(plugin.options.buttons.noThanks.class).addClass(uniq+' no-thanks');
							buttonsHolder.append(button);
						}

						var content = $("<div>").append(step.content);

						if (step.showButtons !== false) {
							content.append(buttonsHolder.get(0).outerHTML);
						}

						currentElement.popover({
							content: content.get(0).outerHTML,
							html: true,
							trigger: 'manual',
							placement: step.placement,
							template: template.get(0).outerHTML
						}).popover('show');

						currentElement.on('shown.bs.popover', function () {
							plugin.options.onAfterChangeStep(plugin, step); //CALLBACK

							$("." + uniq).on("click", function () {
								if ( plugin.options.steps[index].selector == "#map-wrap" ) {
									$(plugin.options.steps[index].selector).parent().css({
										'z-index': 0
									});
								}else if ( plugin.options.steps[index].selector == ".dt-header" ) {
									$(plugin.options.steps[index].selector).append('<div class="bg-over"></div>');
								}

								if ( $(this).hasClass('no-thanks') ) {
									plugin.finish();
									$(".dt-header").find('.bg-over').remove();
									return;
								}

								if (plugin.options.steps[index + 1]) {
									plugin.next();
								} else {
									plugin.finish();
									$(".dt-header").find('.bg-over').remove();
								}
							});
						});
					});
				} else {
					debugLog(pluginName + ":", "Step element not found: ", step);
				}
			} else {
				debugLog(pluginName + ":", "Step not found");
			}
		};
		var removeCurrentStep = function () {
			if (currentElement !== null) {
				currentElement.removeClass(plugin.options.currentStep.selectedClass);
				currentElement.popover('destroy');
			}
		};
		var onFinish = function () {
			removeCurrentStep();
			plugin.backdrop.remove();
			plugin.options.onComplete(plugin); //CALLBACK
		};
		var scrollToElement = function (callback) {
			if (typeof callback != "function") {
				callback = $.noop();
			}
			if (currentStep.scrollToElement !== false) {
				var offsetTop = 20;
				if (currentStep.offsetTop) {
					offsetTop = currentStep.offsetTop;
				}
				var called = false;
				if ( currentElement.hasClass('wrap-frm-listing') || currentElement.hasClass('wrap-list-duan') || currentElement.hasClass('type-payment') || currentElement.hasClass('profile-item') || currentElement.hasClass('dashboard-item') || currentElement.hasClass('pay-item') || currentElement.hasClass('noti-item') || currentElement.hasClass('mess-item') ) {
					if (called === false) {
						callback();
						called = true;
					}
				}else {
					$('html, body').animate({
						scrollTop: currentElement.offset().top - offsetTop - 70
					}, plugin.options.scroll.anmationSpeed, function () {
						if (called === false) {
							callback();
							called = true;
						}
					});	
				}
				
			} else {
				callback();
			}
		};
		var debugLog = function () {
			if (plugin.options.debug) {
				console.log.apply(console, arguments);
			}
		};

		return plugin;
	};
})(jQuery);

function textTour(txtArr, urlPage) {
	console.log(urlPage);
    var steps = '';
	if ( $(".search-wrap-home").length ) {
		steps = [
	                {
	                    selector: ".dt-header",
	                    placement: "bottom",
	                    content: txtArr[0],
	                },{
	                    selector: ".search-wrap-home",
	                    placement: "bottom",
	                    content: txtArr[1]
	                }
	            ]
	}else if ( $(".statis section").length ) {
		steps = [
	                {
	                    selector: ".statis section",
	                    placement: "left",
	                    content: txtArr[0],
	                }
	            ]
	}else if ( $(".menuUser ul").length ) {
		steps = [
	                {
	                    selector: ".menuUser li .profile-item",
	                    placement: "right",
	                    content: txtArr[0]
	                },
	                {
	                    selector: ".menuUser li .dashboard-item",
	                    placement: "right",
	                    content: txtArr[1],
	                },
	                {
	                    selector: ".menuUser li .pay-item",
	                    placement: "right",
	                    content: txtArr[2],
	                },
	                {
	                    selector: ".menuUser li .noti-item",
	                    placement: "right",
	                    content: txtArr[3],
	                },
	                {
	                    selector: ".menuUser li .mess-item",
	                    placement: "right",
	                    content: txtArr[4],
	                },
	                {
	                    selector: ".wrap-list-duan",
	                    placement: "left",
	                    content: txtArr[5],
	                }
	            ]
	}else if ( $("#map-wrap").length ) {
		steps = [
	                {
	                    selector: ".toggle-search",
	                    placement: "bottom",
	                    content: txtArr[0],
	                },{
	                	selector: "#map-wrap",
	                	placement: "right",
	                	content: txtArr[1]
	                },{
	                	selector: ".wrap-listing",
	                	placement: "left",
	                	content: txtArr[2]
	                }
	            ]
	}else if ( $(".type-payment").length ) {
		steps = [
	                {
	                    selector: ".type-payment",
	                    placement: "left",
	                    content: txtArr[0],
	                }
	            ]
	}else if ( $(".wrap-frm-listing").length ) {
		steps = [
			{
				selector: ".wrap-frm-listing",
	            placement: "left",
	            content: txtArr[0]
            }
		]
	}

	var intro = $.hemiIntro({
        debug: false,
        steps: steps,
        onComplete: function (item) {
            $.ajax({
                type: "get",
                dataType: 'json',
                url: urlPage,
                success: function (data) {
                }
            });
        }
    });

    intro.start();
}