<?php
	Yii::app()->VExtension->registerMaps();
	$config = Yii::app()->VExtension->getMapsConfig();
    $lat = $config['defaultLatitude'];
    $lng = $config['defaultLongitude'];
    $zoom = $config['defaultZoom'];

?>

	<style="text/css">
	#coordinatesFinder {position: fixed; top: 10px; width: 800px; margin-left: -400px; left: 50%; background-color: #fff;}
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

    valueLatClass:'coordinates-latitude-value',
    valueLngClass:'coordinates-longitude-value',
    valueZoomClass:'coordinates-zoom-value',

    var obj = $("<div>");
    obj.attr("id", "coordinatesFinderObj");
    obj.attr("class", "coordinatesFinder");
    $("body").append(obj);
    obj = $("#coordinatesFinderObj");
    obj.hide();
    obj.html('<div id="ymap-coordinatesFinder" style="width: 100%; height: 300px; z-index: 99999 !important;"></div><a href="#" class="btn btn-primary coordinatesOk">Ок</a>');
	obj.find(".coordinatesOk").click(function(){
		YandexMapsSelectCoordinates.setZoom(YandexMapsSelectCoordinates.map.getZoom());
		obj.hide();
	});
	YandexMapsSelectCoordinates.initMap("coordinatesFinder", YandexMapsSelectCoordinates._defaultLatitude, YandexMapsSelectCoordinates._defaultLongitude, YandexMapsSelectCoordinates._defaultZoom);

    init:function(el, opts)
    {
        var defaults = {
			'inputName' : 'coordinates',
			'containerSelector' : '',
			'itemClass' : ''
		};
		var o = $.extend({}, defaults, opts);
		var container = el.closest(containerSelector);
		this._container = container;

		var coords = YandexMapsSelectCoordinates.getCoordinates(container);
		var longitude = coords[0];
		var latitude = coords[1];
		var zoom = (isNaN(YandexMapsSelectCoordinates.getZoom(container))) ?  : YandexMapsSelectCoordinates.getZoom(container);

		var centerLongitude = (isNaN(coords[0])) ?  : coords[0];
		var centerLatitude = (isNaN(coords[1])) ?  : coords[1];

		YMaps.ready(function() {
	        this._placemark = false;
			if (!isNaN(latitude) && !isNaN(longitude))
				placemarkAdd(latitude, longitude);
		});
		obj.show();
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

    setValues:function()
    {
    	var latInput = _container.find("."+this.valueLatClass);
    	if (!latInput.length) {
    		var input = $("<input>");
    		input.attr("type", "hidden");
    		input.attr("name", o.inputName+'[lat]');
    		input.attr("value", "");
    		input.attr("class", this.valueLatClass);
    		_container.append(input);
    		latitude = false;
    	}

    	var lngInput = _container.find("."+this.valueLngClass);
    	if (!lngInput.length) {
    		var input = $("<input>");
    		input.attr("type", "hidden");
    		input.attr("name", o.inputName+'[lng]');
    		input.attr("value", "");
    		input.attr("class", this.valueLngClass);
    		_container.append(input);
    		longitude = false;
    	}
    }

    getCoordinates:function()
    {
    	latitude = false;
    	var latInput = _container.find("."+this.valueLatClass);
    	if (latInput.length) {
	    	latitude = parseFloat(latInput.val());
    	}

    	longitude = false;
    	var lngInput = _container.find("."+this.valueLngClass);
    	if (lngInput.length) {
	    	longitude = parseFloat(lngInput.val());
    	}
        return new Array(longitude, latitude);
    },

    setCoordinates:function(latitude, longitude)
    {
    	var latInput = _container.find("."+this.valueLatClass);
    	if (latInput.length) {
	    	latInput.val(latitude);
    	}

    	longitude = false;
    	var lngInput = _container.find("."+this.valueLngClass);
    	if (lngInput.length) {
	    	lngInput.val(longitude);
    	}
    },

    getZoom:function()
    {
    	var zoomInput = _container.find("."+this.valueZoomClass);
    	if (zoomInput.length) {
	    	return zoomInput.val();
    	}
    },

    setZoom:function(val)
    {
    	var zoomInput = _container.find("."+this.valueZoomClass);
    	if (zoomInput.length) {
	    	zoomInput.val(val);
    	}
    }

};
	</script>

    <script type="text/javascript">

    $(document).ready(function()
    {
        YandexMapsSelectCoordinates._defaultLatitude = '<?php echo $config['defaultLatitude'] ?>';
        YandexMapsSelectCoordinates._defaultLongitude = '<?php echo $config['defaultLongitude'] ?>';
        YandexMapsSelectCoordinates._defaultZoom = '<?php echo $config['defaultZoom'] ?>';

        YandexMapsSelectCoordinates.init('<?php echo $uniqId; ?>');

    });
    </script>

<?php
	echo CHtml::activeTextField($model, $textField);
	echo CHtml::hiddenField($inputId.'_lat' , '', array('id' => $inputId.'_lat'));
	echo CHtml::hiddenField($inputId.'_lng' , '', array('id' => $inputId.'_lng'));
	echo CHtml::hiddenField($inputId.'_zoom' , '', array('id' => $inputId.'_zoom'));
?>
<div id="gpsMap<?php echo $uniqId; ?>" style="width: 300px; height: 150px; float:left; padding: 0px 5px 0px 0px;"></div>
<div>
    <div class="btn-toolbar">
        <div class="btn-group btn-group-vertical">
            <a id="show-<?php echo $uniqId; ?>" class="btn btn-default" href="#" title="Указать точку"><span class="glyphicon glyphicon-map-marker"></span></a>
            <a id="del-<?php echo $uniqId; ?>" class="btn btn-default" href="#" title="Удалить точку"><i class="glyphicon glyphicon-remove"></i></a>
        </div>
    </div>
</div>
<div id="findPlacemark<?php echo $uniqId; ?>" style="padding: 0px 5px 0px 0px;"></div>
