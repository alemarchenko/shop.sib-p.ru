if (!window.RS) {
	window.RS = {};
}

window.RS.ShopList = (function() {

	var GoogleMap = function (mapContainer) {

		this.init = function (options) {
			this.map = new google.maps.Map(mapContainer, {
				center: {
					lat: parseFloat(options.center[0]),
					lng: parseFloat(options.center[1])
				},
				zoom: parseInt(options.zoom),
			});

			this.clusterer = new MarkerClusterer(this.map, {}, {
				imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
			});
		};

		this.setMarker = function (item) {
			if (!this.map) {
				throw new Error('map is not defined');
			}

			if (!item.marker && item.coords) {

				var contentWindow = new google.maps.InfoWindow({
					content: item.mapPopupContent
				});;

				item.marker = new google.maps.Marker({
					position: {
						lat: parseFloat(item.coords[0]),
						lng: parseFloat(item.coords[1])
					},
					title: item.name
				});

				item.marker.addListener('click', function() {
					contentWindow.open(this.map, item.marker);
				}.bind(this));
			} else {
				item.marker.setVisible(true);
			}

			this.clusterer.addMarker(item.marker);
		};

		this.removeMarker = function (item) {
			if (!this.map) {
				throw new Error('map is not defined');
			}

			if (item.marker) {
				this.clusterer.removeMarker(item.marker);
				item.marker.setVisible(false);
				item.marker.setMap(null);
			}

			this.clusterer.resetViewport();
		}
	}

	var YandexMap = function (mapContainer) {

		this.init = function (options) {
			this.map = new ymaps.Map(mapContainer, {
				center: options.center,
				zoom: options.zoom,
				controls: ['zoomControl']
			});

			this.clusterer = new ymaps.Clusterer({
				groupByCoordinates: false,
				clusterDisableClickZoom: true,
				clusterHideIconOnBalloonOpen: false,
				geoObjectHideIconOnBalloonOpen: false
			});

			this.map.geoObjects.add(this.clusterer);
		};

		this.setMarker = function (item) {
			if (!this.map) {
				throw new Error('map is not defined');
			}

			if (!item.marker && item.coords) {
				item.marker = new ymaps.Placemark(
					item.coords,
					{
						clusterCaption: item.name,
						balloonContentBody: item.mapPopupContent
					}
				);
			}

			this.clusterer.add(item.marker);
		};

		this.removeMarker = function (item) {
			if (!this.map) {
				throw new Error('map is not defined');
			}

			if (item.marker) {
				this.clusterer.remove(item.marker);
			}
		};
	}

	function ShopList(params) {
		this.container = document.getElementById(params['CONTAINER_ID']);
		this.map = document.getElementById(params['MAP_ID']);

		this.filters = {};

		this.prepareItems(params['ITEMS']);
		this.bindEvents();

		var provider = params['MAP_PROVIDER'];
		var mapOptions = {
			center: params['MAP_CENTER_COORDS'],
			zoom: params['MAP_ZOOM']
		};
		this.initMap(provider, mapOptions);
	}

	ShopList.prototype.prepareItems = function (items) {
		var i, item, itemId, itemNode;

		this.items = {};

		for (i in items) {

			if (!items.hasOwnProperty(i)) {
				continue;
			}

			item = items[i];
			itemId = item['ITEM_ID'];
			itemNode = this.container.querySelector('[data-item="' +  itemId + '"]');

			this.items[itemId] = {
				id: itemId,
				name: item['NAME'],
				filters: item['FILTERS'],
				isHidden: false,
				el: itemNode,
				coords: [
					parseFloat(item.COORDS[0]),
					parseFloat(item.COORDS[1]),
				],
				mapPopupContent: item['PREVIEW_TEXT']
			}

		}
	}

	ShopList.prototype.initMap = function (provider, options) {

		switch(provider) {
			case 'google':
				this.mapProvider = new GoogleMap(this.map);
				break;
			case 'yandex':
			default:
				this.mapProvider = new YandexMap(this.map);

				break;
		}

		this.mapProvider.init(options);
		this.updateMarkers();
	}

	ShopList.prototype.bindEvents = function () {
		this.bindFilterEvents();
	}

	ShopList.prototype.bindFilterEvents = function () {
		var filterItems = this.container.querySelectorAll('[data-filter]');
		var _thisObj = this;
		var i;

		if (!filterItems) {
			return;
		}

		for(i in filterItems) {

			if (!filterItems.hasOwnProperty(i)) {
				continue;
			}

			filterItems[i].addEventListener('click', function (e) {
				e.preventDefault();

				var filterKey = this.getAttribute('data-filter');
				var filterVal = this.getAttribute('data-filter-val');

				_thisObj.filters[filterKey] = filterVal;

				_thisObj.filterItems();
				_thisObj.updateMarkers();

				_thisObj.toggleFilterButton(this);
			});
		}
	}

	ShopList.prototype.toggleFilterButton = function (button, val) {
		if (button.classList.contains('dropdown-item')) {
			var dropdown = button.parentNode.parentNode;
			var dropdownToggle = dropdown.querySelector('.dropdown-toggle');

			dropdownToggle.innerText = button.innerText;
		} else if (button.classList.contains('btn')) {
			var buttonParent = button.parentNode;
			var selectedButton = buttonParent.querySelector('.btn-primary');

			selectedButton.classList.remove('btn-primary');
			selectedButton.classList.add('btn-outline-secondary-primary');

			button.classList.remove('btn-outline-secondary-primary');
			button.classList.add('btn-primary');
		}
	}

	ShopList.prototype.filterItems = function () {
		var i, item, filterKey;

		for (i in this.items) {

			if (!this.items.hasOwnProperty(i)) {
				continue;
			}

			item = this.items[i];
			itemFilters = item.filters;

			item.isHidden = false;
			item.el.classList.remove('d-none');

			for (filterKey in this.filters) {

				if (!this.filters.hasOwnProperty(filterKey)) {
					continue;
				}

				if (
					itemFilters[filterKey] &&
					this.filters[filterKey].trim() != '' &&
					itemFilters[filterKey] != this.filters[filterKey]
				 ) {
					item.isHidden = true;
					item.el.classList.add('d-none');
				}
			}
		}
	}

	ShopList.prototype.updateMarkers = function () {
		var markers = [];
		var i, item;

		for (i in this.items) {

			if (!this.items.hasOwnProperty(i)) {
				continue;
			}

			item = this.items[i];

			if (item.isHidden) {
				this.mapProvider.removeMarker(item);
			} else {
				this.mapProvider.setMarker(item);
			}
		}
	}

	return ShopList;
}());
