function tracking(e,t){clearTimeout(trackingTimeout);var a=form.fields.filter(function(){return!!this.value}).serialize(),r={location:form.mapSearchEl.val(),payload:a,_csrf:yii.getCsrfToken(),agent:navigator.userAgent};r.is_mobile=-1!==window.navigator.userAgent.search("Mobi")?1:0,e&&(r.referer=e),t?_tracking(r):trackingTimeout=setTimeout(function(){_tracking(r)},detr)}function _tracking(e){$.ajax({method:"POST",url:"/listing/tracking",data:e})}function toogleScroll(){var e=$("#search-form").outerHeight(),t=$(".wrap-listing-item .inner-wrap").outerHeight();$(".wrap-listing").css("height",t-e+"px")}function getShowNumFrm(e){if(!e)return void $(".show-num-frm").each(function(){var e=$(this),t=0;e.find(".val-selected .selected").find("span").remove(),e.find(":input").not("input[type=hidden]").each(function(){""!=$(this).val()&&(t+=1)}),t>0&&e.find(".val-selected .selected").append('<span style="display: inline-block;padding-left:5px;">('+t+")</span>")});if(el=$(this),el.closest(".show-num-frm").length>0){var t=0;el.closest(".show-num-frm").find(":input").not("input[type=hidden]").each(function(){""!=$(this).val()&&(t+=1,el.closest(".show-num-frm").find(".val-selected .selected").find("span").remove(),el.closest(".show-num-frm").find(".val-selected .selected").append('<span style="display: inline-block;padding-left:5px;">('+t+")</span>")),0==t&&el.closest(".show-num-frm").find(".val-selected .selected").find("span").remove()})}}var desktop,form,events,$window=$(window),s={type:"#type"};$(document).ready(function(){events={mobileEvents:[],desktopEvents:[],attachMobileEvent:function(){events._attachEvent.apply(events.mobileEvents,arguments)},attachDesktopEvent:function(){events._attachEvent.apply(events.desktopEvents,arguments)},_attachEvent:function(){events.switchEvent("on",arguments),this.push(arguments)},detachMobileEvents:function(){events._detachEvents(events.mobileEvents),events._attachEvents(events.desktopEvents)},detachDesktopEvents:function(){events._detachEvents(events.desktopEvents),events._attachEvents(events.mobileEvents)},_detachEvents:function(e){for(var t in e)events.switchEvent("off",e[t])},_attachEvents:function(e){for(var t in e)events.switchEvent("on",e[t])},switchEvent:function(e,t){var a=[];for(var r in t)a.push(t[r]);var o=a.splice(0,1);o=o[0],o[e].apply(o,a)}},form={el:$("#search-form"),listSearchEl:$("#search-list"),mapSearchEl:$("#map-search"),autoFill:$("#map-search-wrap").find(".auto-fill"),fields:null,filterFields:function(e){e.preventDefault(),form.fields.filter(function(){return!this.value}).prop("disabled",!0),window.location=form.mappingUrl(form.el.serialize())},mappingUrl:function(e){for(var e=decodeURIComponent(e),t=e.split("&"),a=[],r=[],o=0;o<t.length;o++){var s=t[o].split("="),n=s[0],i=s[1],l="_min",c="_max";if(n.lastIndexOf(l)==n.length-l.length)r.push(n.replace(l,"")+"="+i);else if(n.lastIndexOf(c)==n.length-c.length){var d=r.length-1,h=r[d].split("="),f=n.replace(c,"");h[0]==f?r[d]=r[d]+"-"+i:r.push(f+"=-"+i)}else r.push(t[o])}for(var o=0;o<r.length;o++)if(s=r[o].split("="),"category_id"==s[0])a.push(catsSlug[s[1]]);else if(fieldsMapping[s[0]]){var i="order_by"==s[0]?sortMapping[s[1]]:s[1];a.push(fieldsMapping[s[0]]+"_"+i)}return actionId+"/"+a.join("/")},sortSubmit:function(){form.el.submit()},searchFocus:function(e){form.mapSearchEl.val(""),form.showSearchList(),form.listSearchEl.find(".hint-wrap").show();var t=getCookie("sh1");if(t){form.listSearchEl.find(".center").show();var a=JSON.parse(t),r=a.length;form.listSearchUl.html("");for(var o=0;r>o;o++)form.listSearchUl.append('<li><a class="search-item" href="javascript:;" data-slug="'+a[o].s+'" data-id="'+a[o].i+'" data-type="'+a[o].t+'">'+decodeURIComponent(a[o].v)+"</a></li>")}else form.listSearchEl.find(".center").hide()},searchTyping:function(e){if(13==e.keyCode){var t=form.listSearchUl.find(".active");if(t)return form.mapSearchEl.blur(),void t.find("a").trigger("click")}if(38==e.keyCode||40==e.keyCode){var a=form.listSearchUl.data("text");"string"!=typeof a&&form.listSearchUl.data("text",form.mapSearchEl.val());var r=form.listSearchUl.children(),o=40==e.keyCode?1:-1,t=form.listSearchUl.find(".active"),n=r.index(t),i=n+o,l=r.length;if(i==l?i=-1:-2==i&&(i=l-1),-1==i)"string"==typeof a&&form.mapSearchEl.val(a);else{var c=r.eq(i).addClass("active");form.mapSearchEl.val(c.text())}return void t.removeClass("active")}form.listSearchUl.data("text",!1);var d=form.mapSearchEl.val().trim(),h=form.fields.filter(s.type).val();""!=d?($.data(this,"ajax")&&$.data(this,"ajax").abort(),$.data(this,"ajax",$.get("/api/v1/map/get",{v:d,t:h},function(e){if(form.listSearchUl.html(""),e.length){for(var t=0;t<e.length;t++){var a=e[t];form.listSearchUl.append('<li><a class="search-item" href="javascript:;" data-slug="'+a.slug+'" data-id="'+a.id+'" data-type="'+a.type+'">'+a.full_name+"</a></li>")}form.showSearchList(),form.listSearchEl.find(".hint-wrap").hide()}}))):(form.listSearchUl.html(""),form.searchFocus())},searchItemMouseEnter:function(){var e=form.listSearchUl.find(".active");e.removeClass("active"),$(this).parent().addClass("active")},searchItemMouseLeave:function(){$(this).parent().removeClass("active")},searchItemClick:function(){var e=$(this),t=e.text(),a=e.data("type"),r=e.data("id"),o=e.data("slug");form.mapSearchEl.data("val",t).val(t),form.autoFill.val("").filter("#"+a+"_id").val(r),$segments=actionId.split("/"),actionId="/"+$segments[1]+"/"+o;for(var s=getCookie("sh1"),n=s?JSON.parse(s):[],i=!1,l=0;l<n.length;l++){var c=n[l];if(c.i==r&&c.t==a){i=!0;break}}i?move(n,l,0):(n.length>4&&n.pop(),n.unshift({v:encodeURIComponent(t),i:r,t:a,s:o})),setCookie("sh1",JSON.stringify(n))},showSearchList:function(){form.listSearchEl.hasClass("hide")&&(form.listSearchEl.removeClass("hide"),$(document).on("click",form.hideSearchList))},hideSearchList:function(e){var t=$(e.target);(t.hasClass("search-item")||0==t.closest("#map-search-wrap").length)&&form.hideSearchList_()},hideSearchList_:function(){form.listSearchEl.addClass("hide"),form.listSearchUl.html(""),$(document).off("click",form.hideSearchList),form.mapSearchEl.val(form.mapSearchEl.data("val"))},preventEnterSubmit:function(e){return 13==e.keyCode?(e.preventDefault(),!1):void 0}},form.fields=form.el.find("select:not(.exclude), input:not(.exclude)"),form.fields.on("change",getShowNumFrm),events.attachMobileEvent(form.el,"submit",form.filterFields),events.attachMobileEvent(form.fields.filter("#order_by"),"change",form.sortSubmit),form.listSearchUl=form.listSearchEl.find("ul"),form.mapSearchEl.on("focus",form.searchFocus).on("keyup",form.searchTyping).on("keydown",form.preventEnterSubmit),form.listSearchEl.on("click","a",form.searchItemClick).on("mouseenter","a",form.searchItemMouseEnter).on("mouseleave","a",form.searchItemMouseLeave),desktop={isLoadedResources:!1,countLoadedResource:0,checkToEnable:function(){desktop.isDesktop()&&!desktop.isEnabled?desktop.enable():!desktop.isDesktop()&&desktop.isEnabled&&desktop.disable()},isDesktop:function(){return"none"==$(".m-header").css("display")},enable:function(){desktop.isEnabled=!0,events.detachMobileEvents(),desktop.isLoadedResources||(desktop.isLoadedResources=!0,desktop.loadResources())},disable:function(){desktop.isEnabled=!1,events.detachDesktopEvents()},loadResources:function(){var e=document.getElementsByTagName("head")[0];for(var t in resources){var a=document.createElement("script");a.src=resources[t],e.appendChild(a)}},loadedResource:function(){desktop.countLoadedResource++,desktop.countLoadedResource==resources.length&&m2Map.initMap()}},desktop.checkToEnable(),$window.on("resize",desktop.checkToEnable);var e=new RegExp("/"+pageParam+"_[0-9]+");referer.replace(e,"")!=window.location.href.replace(e,"")&&(referer=qs?"1":referer,tracking(referer,!0)),toogleScroll(),getShowNumFrm(),$window.on("resize",toogleScroll),$(".advande-search").toggleShowMobi({btnEvent:".btn-submit",itemToggle:".toggle-search"}),$(".dropdown-common").dropdown({txtAdd:!0,styleShow:0}),$("#type").on("change",function(){$(".select-price .val-selected div span").hide(),$(".select-price .box-dropdown").price_dt({rebuild:!0}),1==$(this).val()?$(".select-price .box-dropdown").price_dt({hinhthuc:"mua"}):2==$(this).val()?$(".select-price .box-dropdown").price_dt({hinhthuc:"thue"}):$(".select-price .box-dropdown").price_dt()});var t=$("#type").val();1==t?$(".select-price .box-dropdown").price_dt({hinhthuc:"mua"}):2==t?$(".select-price .box-dropdown").price_dt({hinhthuc:"thue"}):$(".select-price .box-dropdown").price_dt(),$(".select-dt .box-dropdown").price_dt()});var trackingTimeout;