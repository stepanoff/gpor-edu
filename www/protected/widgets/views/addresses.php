<?php
    Yii::app()->VExtension->registerMaps();
    $config = Yii::app()->VExtension->getMapsConfig();
    $lat = $config['defaultLatitude'];
    $lng = $config['defaultLongitude'];
    $zoom = $config['defaultZoom'];

?>


<div id="addresss_<?php echo $inputId; ?>"></div>

<style>
.addresss__row__input {width: 200px; display: inline;}
</style>

<style type="text/css">
    .coordinatesFinder {position: fixed; top: 10px; width: 800px; margin-left: -400px; left: 50%; background-color: #fff;}
</style>
<script type="text/javascript">
var YandexMapsSelectCoordinates =
{
    _defaultLatitude:0,
    _defaultLongitude:0,
    _defaultZoom:12,
    _placemark:false,
    _container:false,
    map:NaN,
    obj:false,

    valueLatClass:'coordinates-latitude-value',
    valueLngClass:'coordinates-longitude-value',
    valueZoomClass:'coordinates-zoom-value',


    init:function(el, opts)
    {
        if (!this.obj) {
            var obj = $("<div>");
            obj.attr("id", "coordinatesFinderObj");
            obj.attr("class", "coordinatesFinder");
            obj.html('<div id="ymap-coordinatesFinder" style="width: 100%; height: 300px; z-index: 99999 !important;"></div><a href="#" class="btn btn-primary coordinatesOk">Ок</a>');
            $("body").append(obj);
            this.obj = $("#coordinatesFinderObj");
            this.obj.hide();
            this.obj.find(".coordinatesOk").click(function(){
                YandexMapsSelectCoordinates.setZoom(YandexMapsSelectCoordinates.map.getZoom());
                obj.hide();
                return false;
            });
            this.initMap("ymap-coordinatesFinder", YandexMapsSelectCoordinates._defaultLatitude, YandexMapsSelectCoordinates._defaultLongitude, YandexMapsSelectCoordinates._defaultZoom);
        }

        var defaults = {
            'inputName' : 'coordinates',
            'containerSelector' : '',
            'itemSelector' : ''
        };
        var o = $.extend({}, defaults, opts);
        var container = el.closest(o.containerSelector);
        this._container = container;

        var coords = YandexMapsSelectCoordinates.getCoordinates();
        var longitude = coords[0];
        var latitude = coords[1];
        var zoom = isNaN(YandexMapsSelectCoordinates.getZoom()) ? this._defaultZoom : YandexMapsSelectCoordinates.getZoom();

        var centerLongitude = (isNaN(coords[0])) ? this._defaultLongitude : coords[0];
        var centerLatitude = (isNaN(coords[1])) ? this._defaultLatitude : coords[1];

        YMaps.ready(function() {
            if (YandexMapsSelectCoordinates._placemark) {
                YandexMapsSelectCoordinates.map.geoObjects.remove(YandexMapsSelectCoordinates._placemark);
                YandexMapsSelectCoordinates._placemark = false;
            }
            if (!isNaN(latitude) && !isNaN(longitude))
                YandexMapsSelectCoordinates.placemarkAdd(latitude, longitude);
        });
        this.obj.show();
    },

    initMap:function(id, centerLatitude, centerLongitude, zoom)
    {
        var map = new YMaps.Map(id,
        {
            // Центр карты
            center:[centerLatitude, centerLongitude],
            // Коэффициент масштабирования
            zoom:zoom,
            // Тип карты
            type: "yandex#map",
            // Поведение карты
            behaviors:["default", "scrollZoom"]
        });
        map.controls
            // Кнопка изменения масштаба
            .add('zoomControl')
            // Список типов карты
            .add('typeSelector');

        // Обработка события, возникающего при щелчке
        // левой кнопкой мыши в любой точке карты.
        // При возникновении такого события поставим метку, если ее еще нет.
        map.events.add('click', function(e)
        {
            if (!YandexMapsSelectCoordinates._placemark)
            {
                var coords = e.get('coordPosition');
                YandexMapsSelectCoordinates.placemarkAdd(coords[0], coords[1]);
                $(document).trigger('yandexMapEvent_add', coords);
            }
        });
        // Передаем готовую карту в свойство объекта
        this.map = map;
    },

    placemarkAdd:function(latitude, longitude)
    {
        var self = this;
        var myPlacemark = new YMaps.Placemark([latitude, longitude], {
            hintContent:'Подвинь меня!'
        }, {
            draggable:true // Метку можно перетаскивать, зажав левую кнопку мыши.
        });
        myPlacemark.events.add('dragend', function(e)
        {
            var coords = this.geometry.getCoordinates();
            self.setCoordinates(coords[0], coords[1]);
            self.setZoom(self.map.getZoom());
        }, myPlacemark);

        this.map.geoObjects.add(myPlacemark);

        this._placemark = myPlacemark;
        this.setCoordinates(latitude, longitude);
        this.setZoom(this.map.getZoom());

    },

    getCoordinates:function()
    {
        latitude = false;
        var latInput = this._container.find("."+this.valueLatClass);
        if (latInput.length) {
            latitude = parseFloat(latInput.val());
        }

        longitude = false;
        var lngInput = this._container.find("."+this.valueLngClass);
        if (lngInput.length) {
            longitude = parseFloat(lngInput.val());
        }
        return new Array(longitude, latitude);
    },

    setCoordinates:function(latitude, longitude)
    {
        var latInput = this._container.find("."+this.valueLatClass);
        if (latInput.length) {
            latInput.val(latitude);
        }

        var lngInput = this._container.find("."+this.valueLngClass);
        if (lngInput.length) {
            lngInput.val(longitude);
        }
    },

    getZoom:function()
    {
        var zoomInput = this._container.find("."+this.valueZoomClass);
        if (zoomInput.length) {
            return zoomInput.val();
        }
    },

    setZoom:function(val)
    {
        var zoomInput = this._container.find("."+this.valueZoomClass);
        if (zoomInput.length) {
            zoomInput.val(val);
        }
    }

};
</script>


<script type="text/javascript">
jQuery(function($) {
	var addresss = <?php echo CJSON::encode($model->$attribute); ?>;

	var addresssEditor_<?php echo $inputId; ?> = function(options) {
		var obj = $("#addresss_<?php echo $inputId; ?>");
		if (!obj) {
			return false;
		}

		var currentIndex = 1;

		var createRow = function (data, addBtn) {
			addBtn = addBtn ? addBtn : false;
			var addressInput = $('<input>');
			addressInput.attr("type", "text");
			addressInput.attr("name", options['inputName']+"["+currentIndex+"][address]");
			addressInput.attr("value", data['address']);
			addressInput.attr("class", "form-control addresss__row__input addresss__row__addressInput");

			var addressTextInput = $('<input>');
			addressTextInput.attr("type", "text");
			addressTextInput.attr("name", options['inputName']+"["+currentIndex+"][text]");
			addressTextInput.attr("value", data['text']);
			addressTextInput.attr("class", "form-control addresss__row__input addresss__row__textInput");

			var a = $('<a>');
			a.attr("href", "#");
			a.attr("class", "btn btn-default addresss__row__delete");
			a.html('<span class="glyphicon glyphicon-remove"></span>');

			var add = $('<a>');
			add.attr("href", "#");
			add.attr("class", "btn btn-default addresss__row__add");
			add.html('<span class="glyphicon glyphicon-plus"></span>');

			var placemarkAdd = $('<a>');
			placemarkAdd.attr("href", "#");
            placemarkAdd.attr("data-inputName", options['inputName']+"["+currentIndex+"]");
			placemarkAdd.attr("class", "btn btn-default addresss__placemark__add");
			placemarkAdd.html('<span class="glyphicon glyphicon-map-marker"></span>');

            var lat = data['lat'] ? data['lat'] : "";
            var lng = data['lng'] ? data['lng'] : "";
            var zoom = data['zoom'] ? data['zoom'] : "";
            var inputName = options['inputName']+"["+currentIndex+"]";

            var input1 = $("<input>");
            input1.attr("type", "hidden");
            input1.attr("name", inputName+'[lat]');
            input1.attr("value", lat);
            input1.attr("class", 'coordinates-latitude-value');

            var input2 = $("<input>");
            input2.attr("type", "hidden");
            input2.attr("name", inputName+'[lng]');
            input2.attr("value", lng);
            input2.attr("class", 'coordinates-longitude-value');

            var input3 = $("<input>");
            input3.attr("type", "hidden");
            input3.attr("name", inputName+'[zoom]');
            input3.attr("value", zoom);
            input3.attr("class", 'coordinates-zoom-value');

			var row = $('<div>');
			row.attr("class", "form-group addresss__row addresss__row_"+currentIndex);

			row.append(addressInput);
			row.append(addressTextInput);
			if (addBtn) {
				row.append(add);
			}
			else {
				row.append(placemarkAdd);
				row.append(a);
                row.append(input1);
                row.append(input2);
                row.append(input3);
			}

			obj.append(row);
			currentIndex++;
		}

		var init = function() {
			createRow({"text": "", "address": ""}, true);

			for (i in options['addresss']) {
				createRow(options['addresss'][i]);
			}

			obj.delegate('.addresss__row__delete', 'click', function(){
				var _this = $(this);
				deleteRow(_this);
				return false;
			});

			obj.delegate('.addresss__row__add', 'click', function(){
				var _this = $(this);
				addRow(_this);
				return false;
			});

			obj.delegate('.addresss__placemark__add', 'click', function(){
				var _this = $(this);
		        YandexMapsSelectCoordinates.init(_this, {
					'inputName' : _this.attr("data-inputName"),
					'containerSelector' : '.addresss__row',
					'itemSelector' : '.addresss__placemark__add'

		        });
				return false;
			});

		};

		var deleteRow = function(el) {
			var row = $(el).closest(".addresss__row");
			row.remove();
		}

		var addRow = function(el) {
			var row = $(el).closest(".addresss__row");
			var addressInput = row.find(".addresss__row__addressInput");
			var textInput = row.find(".addresss__row__textInput");
			data = {};
			data['address'] = addressInput.attr("value");
			data['text'] = textInput.attr("value");
			addressInput.attr("value", "");
			textInput.attr("value", "");
			createRow(data);
		}

		init();

	}

	var addresssEditor_<?php echo $inputId; ?>Obj = new addresssEditor_<?php echo $inputId; ?>({
		'addresss' : <?php echo CJSON::encode($model->$attribute); ?>,
		'inputName' : '<?php echo $inputName; ?>',
		'inputId' : '<?php echo $inputId; ?>'
	});

    YandexMapsSelectCoordinates._defaultLatitude = '<?php echo $config['defaultLatitude'] ?>';
    YandexMapsSelectCoordinates._defaultLongitude = '<?php echo $config['defaultLongitude'] ?>';
    YandexMapsSelectCoordinates._defaultZoom = '<?php echo $config['defaultZoom'] ?>';
    YandexMapsSelectCoordinates.valueLatClass = 'coordinates-latitude-value';
    YandexMapsSelectCoordinates.valueLngClass = 'coordinates-longitude-value';
    YandexMapsSelectCoordinates.valueZoomClass = 'coordinates-zoom-value';
});
</script>
