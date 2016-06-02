function createImage(e){var t=document.createElement("img");return t.src=e,t}function measureText(e,t){var a=document.createElement("span");a.style.font=t,a.style.visibility="hidden",a.style.position="fixed",a.style.zIndex=-1,a.appendChild(document.createTextNode(e)),document.body.appendChild(a);var r=a.getBoundingClientRect().width;return document.body.removeChild(a),r}function createMarkerIcon(e,t,a){var r=document.createElement("canvas"),o=r.getContext("2d");return r.width=e.width,r.height=e.height,o.drawImage(e,0,0,e.width,e.height),o.font=a.font,o.fillStyle=a.color,o.fillText(t,a.left,a.top),r.toDataURL()}function decodeGeometry(e){e=JSON.parse(e);for(var t=e.length,a=[],r=0;t>r;r++)a.push(google.maps.geometry.encoding.decodePath(e[r]));return a}function Area(e,t){this.attrs=e,this.type=t}function Product(e){this.attrs=e}function initInfoBox(){InfoBox=function(e){google.maps.OverlayView.call(this),"undefined"==typeof e&&(e={}),this.opts=e,this.viewHolder=document.createElement("div"),this.viewHolder.style.position="absolute",this.opts.content&&this.setContent(this.opts.content)},InfoBox.prototype=new google.maps.OverlayView,InfoBox.prototype.draw=function(){var e=this.getProjection().fromLatLngToDivPixel(this.position);this.viewHolder.style.left=e.x-this.viewHolder.offsetWidth/2+"px";var t=e.y-this.viewHolder.offsetHeight;this.anchor instanceof google.maps.Marker&&(t-=this.anchor.getShape().coords[3]),this.viewHolder.style.top=t+"px",this.opts.disableAutoPan||this.boundsChangedListener&&this.panMap(),this.opts.onDraw&&this.opts.onDraw.apply(this)},InfoBox.prototype.remove=function(){this.viewHolder.parentNode.removeChild(this.viewHolder)},InfoBox.prototype.onAdd=function(){var e=this.getPanes();e.floatPane.appendChild(this.viewHolder);var t=this;this.opts.disableAutoPan||(this.boundsChangedListener=google.maps.event.addListener(this.map,"bounds_changed",function(){return t.panMap.apply(t)}))},InfoBox.prototype.open=function(e,t){var a,r;e instanceof google.maps.Marker?(a=e.getPosition(),r=e.getMap()):e instanceof google.maps.Map&&(a=t?t:e.getCenter(),r=e),(this.anchor!==e||this.position.lat()!=a.lat()&&this.position.lng()!=a.lng())&&(this.anchor=e,this.position=a,this.setMap(r))},InfoBox.prototype.close=function(){this.anchor=null,this.position=null,this.setMap(null)},InfoBox.prototype.setContent=function(e){if(this.content=e,"string"==typeof e)this.viewHolder.innerHTML=e;else{for(;this.viewHolder.firstChild;)this.viewHolder.removeChild(this.viewHolder.firstChild);this.viewHolder.appendChild(e)}},InfoBox.prototype.panMap=function(){var e=this.getMap(),t=e.getBounds();if(t){var a=t.getSouthWest().lng(),r=t.getNorthEast().lng(),o=t.getNorthEast().lat(),n=t.getSouthWest().lat(),i=this.getBounds(20,20),l=i.getSouthWest().lng(),s=i.getNorthEast().lng(),m=i.getNorthEast().lat(),p=i.getSouthWest().lat(),f=(a>l?a-l:0)+(s>r?r-s:0),d=(m>o?o-m:0)+(n>p?n-p:0),c=e.getCenter(),g=c.lng()-f,u=c.lat()-d;e.panTo(new google.maps.LatLng(u,g)),google.maps.event.removeListener(this.boundsChangedListener),this.boundsChangedListener=null}},InfoBox.prototype.getBounds=function(e,t){var a=this.getMap();if(a){var r=a.getBounds();if(r){var o=a.getDiv(),n=o.offsetWidth,i=o.offsetHeight,l=r.toSpan(),s=l.lng(),m=l.lat(),p=s/n,f=m/i,d=this.anchor instanceof google.maps.Marker?this.anchor.__gm.Gf.shape.coords[3]:0,c=this.position.lng()+(-(this.viewHolder.offsetWidth/2)-e)*p,g=this.position.lng()+(this.viewHolder.offsetWidth/2+e)*p,u=this.position.lat()-(-this.viewHolder.offsetHeight-d-t)*f,M=this.position.lat()-(t-d)*f;return new google.maps.LatLngBounds(new google.maps.LatLng(M,c),new google.maps.LatLng(u,g))}}}}var markerIcon={PADDING:8,MIN_WIDTH:24,IMG:null,IMG_HOVER:null,COLOR:"black",COLOR_HOVER:"white",FONT:"12px Arial",ARROW_HEIGHT:0,TEXT_HEIGHT:0,MAX_WIDTH:0,MAX_HEIGHT:0,create:function(e,t){var a=t?markerIcon.IMG_HOVER:markerIcon.IMG,r=t?markerIcon.COLOR_HOVER:markerIcon.COLOR,o={color:r,font:markerIcon.FONT},n=Math.round(measureText(e,o.font)),i=n+2*markerIcon.PADDING;i<=markerIcon.MIN_WIDTH?(i=markerIcon.MIN_WIDTH,o.left=(i-n)/2):o.left=markerIcon.PADDING;var l=Math.round(i*a.height/a.width),s=l*markerIcon.ARROW_HEIGHT;return o.top=(l-s)/2+markerIcon.TEXT_HEIGHT,a.width=i,a.height=l,markerIcon.MAX_WIDTH<i&&(markerIcon.MAX_WIDTH=i,markerIcon.MAX_HEIGHT=l),{icon:createMarkerIcon(a,e,o),width:i,height:l}}};markerIcon.IMG=createImage("data:image/png;base64,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"),markerIcon.IMG_HOVER=createImage("data:image/png;base64,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"),markerIcon.ARROW_HEIGHT=4/24,markerIcon.TEXT_HEIGHT=4.5,Area.prototype.getPaths=function(){var e,t=this.attrs;return t.geometry&&(e=decodeGeometry(t.geometry)),e},Area.prototype.getBounds=function(){var e,t=this.getPaths();if(t){e=new google.maps.LatLngBounds;for(var a=t.length,r=0;a>r;r++)for(var o=t[r],n=o.length,i=0;n>i;i++)e.extend(o[i])}return e},Area.prototype.getCenter=function(){var e,t=this.attrs;if(t.center)centerP=JSON.parse(t.center),e=new google.maps.LatLng(centerP[0],centerP[1]);else{var a=this.getBounds();a&&(e=a.getCenter())}return e},Area.prototype.getName=function(){var e=this.attrs;return e.pre?e.pre+" "+e.name:e.name},Area.prototype.draw=function(e){var t=this.getPaths(),a=this;if("street"==this.type)t&&(this.poly=new google.maps.Polyline({map:e,path:t[0],strokeColor:m2Map.polygonColor,strokeOpacity:.8,strokeWeight:6,geodesic:!0}));else{t&&(this.poly=new google.maps.Polygon({map:e,paths:t,strokeColor:m2Map.polygonColor,strokeOpacity:.8,strokeWeight:1,fillColor:m2Map.polygonColor,fillOpacity:.2}),this.poly.addListener("mouseover",function(){a.marker&&a.marker.set("clickable",!1),a.mouseover()}),this.poly.addListener("mouseout",function(){a.marker&&a.marker.set("clickable",!0),a.mouseout()}),this.poly.addListener("click",function(){a.nextZoomLevel()}));var r=this.getCenter();r&&this.attrs.total>0&&(this.marker=new google.maps.Marker({map:e,position:r}),m2Map.setIcon(this.marker,this.attrs.total,0),this.marker.addListener("mouseover",function(){a.mouseover()}),this.marker.addListener("mouseout",function(){a.mouseout()}),this.marker.addListener("click",function(){a.nextZoomLevel()}))}},Area.prototype.remove=function(){this.poly&&this.poly.setMap(null),this.marker&&this.marker.setMap(null)},Area.prototype.mouseover=function(){m2Map.infoBoxHover.setContent('<div class="info-wrap-single" style="margin-bottom: 12px;"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">'+this.getName()+'</div><div class="arrow"></div></div>'),this.marker?m2Map.infoBoxHover.open(this.marker):m2Map.infoBoxHover.open(m2Map.map,this.getCenter())},Area.prototype.mouseout=function(){m2Map.infoBoxHover.close()},Area.prototype.nextZoomLevel=function(){for(var e=m2Map.map.getZoom(),t=e+1,a=form.af.filter(s.iz).val(),r=form.getFocusLocation(),o=m2Map.getZoomAreaLevel(e,a,r.type),n=r.type;m2Map.getZoomAreaLevel(t,a,n)==o&&t<m2Map.detailZoomLevel;)t++;m2Map.map.setCenter(this.getCenter()),m2Map.map.setZoom(t)},Product.prototype.getMarkerKey=function(){return this.attrs.lat+"-"+this.attrs.lng},Product.prototype.getPosition=function(){return new google.maps.LatLng(this.attrs.lat,this.attrs.lng)},s.rect="#rect",s.ra="#ra",s.rm="#rm",s.rl="#rl",s.raK="#ra_k",s.iz="#iz",s.z="#z",s.c="#c",s.page="#page",s.did="#did";var contentHolder=$("#content-holder"),detailListingWrap=$(".detail-listing-dt"),m2Map={mapEl:$("#map"),polygonColor:"#00a769",map:null,markers:{},areas:{},areasLevel:{city:3,district:2,ward:1},deteilZoomLevelDefault:16,detailZoomLevel:16,infoBoxHover:null,boundsChangedEvent:null,zoomChangedEvent:null,closeDetailListener:null,currentDrawState:null,markerIconCached:{},shape:{coords:[0,0,24,28],type:"rect"},initMap:function(){History.Adapter.bind(window,"statechange",m2Map.stateChange),initInfoBox(),m2Map.infoBoxHover=new InfoBox({disableAutoPan:!0});var e=form.afZoom.val(),t=form.afCenter.val();m2Map.mapOptions={center:{lat:10.783091,lng:106.704899},zoom:18,mapTypeControl:!0,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}},e&&t?(m2Map.initMapRe(e,t),m2Map.hasMapInstance()):m2Map.initMapFresh(m2Map.hasMapInstance)},hasMapInstance:function(){var e=form.af.filter(s.did).val();e&&m2Map.detail(e),m2Map.addDrawControl()},stateChange:function(){},pushState:function(e){e||(e=form.serialize()),e=decodeURIComponent(e),History.pushState({},document.title,actionId+"?"+e)},initMapRe:function(e,t){m2Map.mapOptions.center=m2Map.urlValueToLatLng(t),m2Map.mapOptions.zoom=Number(e);m2Map.map=new google.maps.Map(m2Map.mapEl.get(0),m2Map.mapOptions);m2Map.initMapReFirstLoad(),google.maps.event.addListenerOnce(m2Map.map,"idle",m2Map.InitMapReIdle),m2Map.detectHasChange()},initMapReFirstLoad:function(){var e=form.getFocusLocation();if("project_building"==e.type)m2Map.changeLocation(m2Map.drawBuildingProject);else if("street"==e.type)m2Map.currentDrawState=e.type,form.af.filter(s.rl).val(""),form.af.filter(s.ra).val(e.type),form.af.filter(s.raK).val("id"),m2Map.ajaxRequest=m2Map.get(function(e){m2Map.drawStreet(e),e.ra&&e.ra.length&&m2Map.drawArea(new Area(e.ra[0],"street"))});else{var t=Number(form.af.filter(s.iz).val()),a=m2Map.mapOptions.zoom,r={ward:0,district:1,city:2};if(t+r[e.type]>=m2Map.deteilZoomLevelDefault?"city"==e.type?m2Map.detailZoomLevel=t+3:"district"==e.type?m2Map.detailZoomLevel=t+2:"ward"==e.type&&(m2Map.detailZoomLevel=t+1):m2Map.detailZoomLevel=m2Map.deteilZoomLevelDefault,a<m2Map.detailZoomLevel){var o=m2Map.getZoomAreaLevel(a,t,e.type);m2Map.currentDrawState=o,form.af.filter(s.ra).val(o);var n=e.type==o?"id":e.type+"_id";form.af.filter(s.raK).val(n);var i=form.fields.filter(s.rect).prop("disabled",!0);m2Map.ajaxRequest=m2Map.get(function(e){e.ra&&m2Map.drawAreas(e.ra,o)}),i.prop("disabled",!1)}else m2Map.currentDrawState="detail",m2Map.loadDetail("")}},InitMapReIdle:function(){form.afRect.val(m2Map.getBounds(0,0,0,0).toUrlValue()),m2Map.boundsChangedEvent=m2Map.map.addListener("bounds_changed",m2Map.boundsChanged),m2Map.zoomChangedEvent=m2Map.map.addListener("zoom_changed",m2Map.zoomChanged)},initMapFresh:function(e){m2Map.changeLocation(function(t){m2Map.drawMap(t),e()})},drawMap:function(e){m2Map.map=new google.maps.Map(m2Map.mapEl.get(0),m2Map.mapOptions);m2Map.drawLocation(e),m2Map.detectHasChange(),google.maps.event.addListenerOnce(m2Map.map,"bounds_changed",m2Map.setInitLocationProps)},detectHasChange:function(){var e,t,a=m2Map.map;google.maps.event.addListenerOnce(m2Map.map,"bounds_changed",function(){var r=a.getZoom();e=r,t=a.getCenter().toString()}),google.maps.event.addListenerOnce(m2Map.map,"idle",function(){var r=a.getZoom();if(r!=e||a.getCenter().toString()!=t){var o=form.getFocusLocation();"project_building"==o.type?"project_building"==m2Map.currentDrawState:"street"==o.type?"street"==m2Map.currentDrawState:r<m2Map.detailZoomLevel?(m2Map.zoomChanged(),m2Map.removeAllDetail()):(m2Map.currentDrawState="detail",m2Map.removeAreas(),m2Map.ajaxRequest&&(m2Map.ajaxRequest.abort(),m2Map.ajaxRequest=null),m2Map.infoBoxHover.close(),form.af.filter(s.page).val(""),form.af.filter(s.ra).val(""),form.af.filter(s.rm).val(1),form.af.filter(s.rl).val(1),m2Map.ajaxRequest=m2Map.get(function(e){m2Map.removeAreas(),m2Map.drawDetailCallBack(e)})),form.afRect.val(m2Map.getBounds(0,0,0,0).toUrlValue()),form.afZoom.val(m2Map.map.getZoom()),form.afCenter.val(m2Map.getCenter().toUrlValue()),m2Map.pushState()}})},setInitLocationProps:function(){var e=m2Map.map.getZoom(),t=form.getFocusLocation();m2Map.currentDrawState=t.type,form.af.filter(s.iz).val(e);var a={ward:0,district:1,city:2};e+a[t.type]>=m2Map.deteilZoomLevelDefault?"city"==t.type?m2Map.detailZoomLevel=e+3:"district"==t.type?m2Map.detailZoomLevel=e+2:"ward"==t.type&&(m2Map.detailZoomLevel=e+1):m2Map.detailZoomLevel=m2Map.deteilZoomLevelDefault},boundsChanged:function(){var e=m2Map.map;clearTimeout(e.get("bounds_changed_timeout")),e.set("bounds_changed_timeout",setTimeout(function(){form.afRect.val(m2Map.getBounds(0,0,0,0).toUrlValue()),form.afZoom.val(m2Map.map.getZoom()),form.afCenter.val(m2Map.getCenter().toUrlValue()),"detail"==m2Map.currentDrawState?m2Map.loadDetail(1):"street"==m2Map.currentDrawState&&m2Map.loadDetail(1),m2Map.pushState()},100))},zoomChanged:function(){var e=m2Map.map.getZoom();if("project_building"==m2Map.currentDrawState||"street"==m2Map.currentDrawState)return!1;if(e<m2Map.detailZoomLevel){form.af.filter(s.rl).val(""),"detail"==m2Map.currentDrawState&&(m2Map.removeAllDetail(),form.af.filter(s.rm).val(""),form.af.filter(s.page).val(""),form.af.filter(s.rl).val(1));var t=form.getFocusLocation(),a=m2Map.getZoomAreaLevel(m2Map.map.getZoom(),form.af.filter(s.iz).val(),t.type);if(m2Map.currentDrawState!=a){m2Map.ajaxRequest&&(m2Map.ajaxRequest.abort(),m2Map.ajaxRequest=null),m2Map.infoBoxHover.close(),m2Map.currentDrawState=a,form.af.filter(s.ra).val(a);var r=t.type==a?"id":t.type+"_id";form.af.filter(s.raK).val(r);var o=form.fields.filter(s.rect).prop("disabled",!0);m2Map.ajaxRequest=m2Map.get(function(e){e.ra&&m2Map.drawAreas(e.ra,a),e.rl&&m2Map.drawList(e.rl)}),o.prop("disabled",!1)}}else m2Map.currentDrawState="detail",m2Map.removeAreas()},loadDetail:function(e){m2Map.ajaxRequest&&(m2Map.ajaxRequest.abort(),m2Map.ajaxRequest=null),m2Map.infoBoxHover.close(),form.af.filter(s.page).val(""),form.af.filter(s.ra).val(""),form.af.filter(s.rm).val(1),form.af.filter(s.rl).val(e),m2Map.ajaxRequest=m2Map.get(m2Map.drawDetailCallBack)},drawLocation:function(e){var t=form.getFocusLocation();if(e.rm&&("street"==t.type?m2Map.drawStreet(e):m2Map.drawBuildingProject(e,!0)),e.ra&&e.ra.length){var a=new Area(e.ra[0],t.type);if(m2Map.drawAndFitArea(a),"street"==t.type){var r=a.getBounds(),o=a.getCenter();r||o||e.rm&&e.rm.length&&m2Map.setCenter({lat:Number(e.rm[0].lat),lng:Number(e.rm[0].lng)})}}google.maps.event.addListenerOnce(m2Map.map,"idle",m2Map.drawLocationCallback)},drawBuildingProject:function(e,t){m2Map.removeAllDetail(),m2Map.removeAreas();var a=$(".infor-duan-suggest");if(a.length&&a.data("lat")&&a.data("lng"))var r={lat:a.data("lat"),lng:a.data("lng")};else if(e.rm.length)var r={lat:Number(e.rm[0].lat),lng:Number(e.rm[0].lng)};if(r){t===!0&&(m2Map.setCenter(r),m2Map.map.setZoom(m2Map.deteilZoomLevelDefault));var o=new google.maps.Marker({map:m2Map.map,position:r}),n=[];for(var i in e.rm)n.push(new Product(e.rm[i]));o.addListener("click",m2Map.markerClick),o.set("products",n),m2Map.setIcon(o,n.length,0),m2Map.markers[r]=o}},drawLocationCallback:function(){null==m2Map.boundsChangedEvent&&(m2Map.boundsChangedEvent=m2Map.map.addListener("bounds_changed",m2Map.boundsChanged)),null==m2Map.zoomChangedEvent&&(m2Map.zoomChangedEvent=m2Map.map.addListener("zoom_changed",m2Map.zoomChanged))},drawAndFitArea:function(e){var t=e.getBounds();if(t)m2Map.fitBounds(e.getBounds());else{var a=e.getCenter();a&&m2Map.setCenter(a)}m2Map.drawArea(e)},changeLocation:function(e){var t=form.getFocusLocation();"project_building"==t.type?form.af.filter(s.rm).val(1):(form.af.filter(s.ra).val(t.type),form.af.filter(s.raK).val("id"),"street"==t.type&&form.af.filter(s.rm).val(1));var a=form.fields.filter(s.rect);"project_building"==t.type&&a.prop("disabled",!0),m2Map.get(e),"project_building"==t.type&&a.prop("disabled",!1)},drawStreet:function(e){e.rm&&(m2Map.removeAllDetail(),m2Map.drawDetail(e.rm))},removeAreas:function(){var e=m2Map.areas;e.length;for(var t in e)e[t].remove();m2Map.areas={}},drawAreas:function(e,t){m2Map.removeAreas();for(var a=e.length,r=0;a>r;r++){var o=new Area(e[r],t);m2Map.drawArea(o)}},drawArea:function(e){e.draw(m2Map.map),m2Map.areas[e.attrs.id]=e},drawDetailCallBack:function(e){e.rm&&(m2Map.removeAllDetail(),m2Map.drawDetail(e.rm)),e.rl&&m2Map.drawList(e.rl)},drawDetail:function(e){for(var t=m2Map.map,a=m2Map.markers,r=e.length,o=0;r>o;o++){var n=new Product(e[o]),i=n.getMarkerKey(),l=a[i];if(l){var s=l.get("products");s.push(n),l.set("products",s),m2Map.setIcon(l,s.length,0)}else l=new google.maps.Marker({map:t,position:n.getPosition()}),l.addListener("click",m2Map.markerClick),l.set("products",[n]),m2Map.setIcon(l,1,0),a[i]=l}},markerClick:function(){var e=this.get("products");if(1==e.length){var t=e[0];m2Map.showDetail(t.attrs.id)}},removeAllDetail:function(){var e=m2Map.markers;for(var t in e)e[t].setMap(null);m2Map.markers={}},drawList:function(e){contentHolder.html(e)},getBounds:function(e,t,a,r,o){return m2Map.map.getBounds()},getCenter:function(){return m2Map.map.getCenter()},setCenter:function(e){m2Map.map.setCenter(e)},fitBounds:function(e){m2Map.map.fitBounds(e)},setIcon:function(e,t,a){if(1==t){var r="/images/marker-"+a+".png";e.setShape(m2Map.shape)}else{var o=t+"-"+a,n=m2Map.markerIconCached[o];if(n)var i=n;else{var i=markerIcon.create(t,a);m2Map.markerIconCached[o]=i}var r=i.icon,l={coords:[0,0,i.width,i.height],type:"rect"};e.setShape(l)}e.setIcon(r)},resetAf:function(){},get:function(e,t){t||(t=form.serialize());var a=form.af.filter(s.rl).val();return form.af.filter(s.ra).val()||form.af.filter(s.rm).val(),a&&$(".items-list").loading({full:!1}),$.ajax({url:form.el.attr("action"),data:t,success:e,complete:function(){a&&($(".items-list").loading({done:!0}),$(".wrap-listing").scrollTop(0))}})},urlValueToLatLng:function(e){var t=e.split(",");return new google.maps.LatLng(t[0],t[1])},getZoomAreaLevel:function(e,t,a){t=Number(t);var r=m2Map.areasLevel[a],o=m2Map.detailZoomLevel-t,n=m2Map.detailZoomLevel-e;if(n>o)return a;var i=Math.ceil(o/r),l=Math.ceil((o-i)/(r-1))+i;return i>=n?"ward":l>=n?"district":"city"},getFocusMarker:function(e){var t=m2Map.markers;for(var a in t)for(var r=t[a],o=r.get("products"),n=o.length,i=0;n>i;i++)if(o[i].attrs.id==e)return r},focusMarker:function(e){m2Map.setIcon(e,e.get("products").length,1),e.setZIndex(google.maps.Marker.MAX_ZINDEX++)},showDetail:function(e){m2Map.detail(e),form.af.filter(s.did).val(e),m2Map.pushState()},detail:function(e){var t=$(".wrap-listing-item .inner-wrap").outerWidth(),a=$(".detail-listing");detailListingWrap.loading({full:!1}),detailListingWrap.css({right:t+"px"}),google.maps.event.removeListener(m2Map.closeDetailListener),m2Map.closeDetailListener=m2Map.map.addListener("click",m2Map.closeDetail),$.get("/listing/detail",{id:e},function(e){var t=$(e).find("#detail-wrap");t.find(".popup-common").each(function(){var e=$(this),t=e.attr("id");$("body").find("#"+t).remove()}),a.find(".container").html($(e).find("#detail-wrap").html()),a.find(".popup-common").appendTo("body");new Swiper(".swiper-container",{pagination:".swiper-pagination",paginationClickable:!0,spaceBetween:0});detailListingWrap.loading({done:!0}),$(".inner-detail-listing").scrollTop(0),$(".btn-extra").attr("href",a.find(".btn-copy").data("clipboard-text"))})},closeDetail:function(e){e.preventDefault&&e.preventDefault();var t=$(".wrap-listing-item .inner-wrap").outerWidth();detailListingWrap.css({right:-t+"px"}),form.af.filter(s.did).val(""),m2Map.pushState()},addDrawControl:function(){var e=document.createElement("div");e.className="draw-wrap",e.index=1;var t=document.createElement("a");t.className="button draw-button",t.innerHTML='<span class="icon-mv"><span class="icon-edit-copy-4"></span></span>Vẽ khoanh vùng';var a=document.createElement("a");a.className="button remove-button",a.innerHTML='<span class="icon-mv"><span class="icon-close-icon"></span></span>Xóa khoanh vùng',e.appendChild(t),e.appendChild(a),m2Map.map.controls[google.maps.ControlPosition.TOP_LEFT].push(e)}};form.af=$("#af-wrap").children(),form.afRect=form.af.filter(s.rect),form.afZoom=form.af.filter(s.z),form.afCenter=form.af.filter(s.c),form.projectInfoEl=$("#project-info"),form.formChange=function(e){var t=$(e.target);if(form.af.filter(s.rl).val(1),t.hasClass("search-item")){if(form.af.val(""),form.af.filter(s.rl).val(1),google.maps.event.removeListener(m2Map.boundsChangedEvent),m2Map.boundsChangedEvent=null,google.maps.event.removeListener(m2Map.zoomChangedEvent),m2Map.zoomChangedEvent=null,google.maps.event.addListenerOnce(m2Map.map,"bounds_changed",m2Map.setInitLocationProps),m2Map.removeAllDetail(),"project_building"==t.data("type")){form.projectInfoEl.html("");var a=t.data("id");$.get(loadProjectUrl,{id:a},function(e){form.projectInfoEl.html(e),toogleScroll()})}else form.projectInfoEl.html(""),toogleScroll();m2Map.changeLocation(function(e){m2Map.removeAreas(),m2Map.drawLocation(e),e.rl&&m2Map.drawList(e.rl)})}else if("order_by"==t.attr("id")){form.af.filter(s.ra).val(""),form.af.filter(s.raK).val(""),form.af.filter(s.page).val(""),m2Map.pushState();var r=form.fields.filter(s.rect);"city"==m2Map.currentDrawState||"district"==m2Map.currentDrawState||"ward"==m2Map.currentDrawState?r.prop("disabled",!0):form.af.filter(s.rm).val(1),form.af.filter(s.rl).val(1),m2Map.get(m2Map.drawDetailCallBack),r.prop("disabled",!1)}else{form.af.filter(s.rl).val(1),form.af.filter(s.page).val("");var r=form.fields.filter(s.rect);if("city"==m2Map.currentDrawState||"district"==m2Map.currentDrawState||"ward"==m2Map.currentDrawState){r.prop("disabled",!0),form.af.filter(s.rm).val("");var o=form.getFocusLocation(),n=m2Map.getZoomAreaLevel(m2Map.map.getZoom(),form.af.filter(s.iz).val(),o.type);form.af.filter(s.ra).val(n);var i=o.type==n?"id":o.type+"_id";form.af.filter(s.raK).val(i)}else form.af.filter(s.rm).val(1),form.af.filter(s.ra).val("");m2Map.get(function(e){m2Map.drawList(e.rl),e.rm&&(m2Map.removeAllDetail(),m2Map.drawDetail(e.rm)),e.ra&&m2Map.drawAreas(e.ra,n)}),r.prop("disabled",!1)}m2Map.pushState()},form.serialize=function(){return form.serialize_(form.fields)},form.serialize_=function(e){return e.filter(function(){return!!this.value}).serialize()},form.toggleConditionFields=function(){desktop.isDesktop()?form.af.prop("disabled",!1):form.af.prop("disabled",!0)},form.getFocusLocation=function(){var e={};return form.autoFill.each(function(){var t=$(this),a=t.val();a&&(e.id=a,e.type=t.attr("id").replace("_id",""))}),e},form.pagination=function(e){e.preventDefault(),form.af.filter(s.rm).val(""),form.af.filter(s.ra).val(""),form.af.filter(s.raK).val("");var t=Number($(this).data("page"))+1,a=form.af.filter(s.page);1==t?a.val(""):a.val(t),m2Map.pushState();var r=form.fields.filter(s.rect);("city"==m2Map.currentDrawState||"district"==m2Map.currentDrawState||"ward"==m2Map.currentDrawState)&&r.prop("disabled",!0),form.af.filter(s.rl).val(1),m2Map.get(form.paginationCallback),form.af.filter(s.rl).val(""),r.prop("disabled",!1)},form.paginationCallback=function(e){m2Map.drawList(e.rl)},form.itemClick=function(e){e.preventDefault();var t=$(this).data("id");m2Map.showDetail(t)},form.itemMouseEnter=function(e){var t=$(this).data("id");$.data(this,"mouseenterTimer",setTimeout(function(){var e=m2Map.getFocusMarker(t);e&&m2Map.focusMarker(e)},300))},form.itemMouseLeave=function(e){clearTimeout($.data(this,"mouseenterTimer"));var t=m2Map.getFocusMarker($(this).data("id"));t&&m2Map.setIcon(t,t.get("products").length,0)},events.attachDesktopEvent(form.fields,"change",form.formChange),events.attachDesktopEvent(form.listSearchEl,"click","a",form.formChange),events.attachDesktopEvent(contentHolder,"click",".pagination a",form.pagination),events.attachDesktopEvent(contentHolder,"click",".item a",form.itemClick),events.attachDesktopEvent($(".close-slide-detail"),"click",m2Map.closeDetail),events.attachDesktopEvent(contentHolder,"mouseenter",".item a",form.itemMouseEnter),events.attachDesktopEvent(contentHolder,"mouseleave",".item a",form.itemMouseLeave),events.attachDesktopEvent($window,"resize",form.toggleConditionFields);var InfoBox;form.toggleConditionFields(),desktop.loadedResource();