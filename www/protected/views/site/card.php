<?php
	Yii::app()->VExtension->registerGlyphicons();
    Yii::app()->VExtension->registerMaps();
?>
<style>
.edu-card {
	border-radius: 4px;
	background: url() center center no-repeat;
	position: relative;
    margin-bottom: 20px;
}
.edu-card__heading {
	height: 240px;
	border-top-left-radius: 4px;
	border-top-right-radius: 4px;
	background-color: rgba(256,256,256,0.6078431372549);
	padding: 40px 0 0 210px;
	position: relative;
}
.edu-card__info {
	padding: 20px 20px 12px 210px;
	background-color: #f7f7f9;
	border-bottom-left-radius: 4px;
	border-bottom-right-radius: 4px;	
	position: relative;
}
.edu-card__logo {
	position: absolute;
	bottom: 50%;
	left: 10px;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 4px;
	background-color: #fff;
	width: 190px;
	height: 190px;
    text-align: center;
}
.edu-card__title {
	position: absolute;
	bottom: 10px;
	_font-weight: bold;
	font-size: 38px;
	font-family: PT Sans Narrow,Arial,Helvetica,sans-serif;
}

.edu-card__info__row {
	clear: both;
	padding-left: 25px;
	position: relative;
}

.edu-card__info__row p {
	margin-bottom: 8px;
	width: 33%;
	float: left;
}

.edu-card__info__icon {
	position: absolute;
	font-size: 14px;
	left: 0;
	top: 6px;
}

.edu-card__info__row__title {
	font-family: PT Sans Narrow,Arial,Helvetica,sans-serif;
	font-size: 20px;
	line-height: 23px;
	margin-bottom: 4px;
	display: block;
}

.edu-card__info__row__desc {
	font-size: 12px;
	line-height: 14px;
	color: #595959;
	display: block;
}

.edu-card-announce {
    float: left;
    width: 200px;
    font-size: 12px;
    line-height: 16px;
    color: #595959;
    padding: 0 10px;
    border-right: 1px solid #dfdfdf;
}

.edu-card-text {
    padding-left: 220px;
}

.map-obj {position: fixed; top: 10px; width: 800px; margin-left: -400px; left: 50%; background-color: #fff;}
</style>
<link rel="stylesheet" href="<?php echo Yii::app()->request->staticUrl; ?>css/card.css" media="all">

<div class="g-48">
    <div class="g-col-1 g-span-37">

    	<div class="edu-card" style="background-image: url(<?php echo Yii::app()->fileManager->getImageThumbUrlByUid($item->image, 960, false); ?>);">
    		<div class="edu-card__heading">
    			<h1 class="edu-card__title"><?php echo $item->getFullTitle(); ?></h1>
    		</div>
    		<div class="edu-card__info">
	    		<div class="edu-card__logo"><img width="" src="<?php echo Yii::app()->fileManager->getImageThumbUrlByUid($item->logo, 180, 180); ?>"></div>
    			<?php 
    			if ($item->_addresses) {
    				echo '<div class="edu-card__info__row edu-card__info__row_address">';
    				echo '<span class="edu-card__info__icon glyphicon glyphicon-map-marker" title="Адреса"></span>';
    				foreach ($item->_addresses as $address) {
    					echo '<p>';
                        if (isset($address['lat']) && $address['lat'] && isset($address['lng']) && $address['lng']) {
                            echo '<span class="edu-card__info__row__title">';
                            echo '<a href="#" class="map-marker" data-lat="'. $address['lat'] .'" data-lng="'. $address['lng'] .'" data-zoom="'. $address['zoom'] .'">' . $address['address'] . '</a>';
                            echo '</span>';
                        } else {
                            echo '<span class="edu-card__info__row__title">' . $address['address'] . '</span>';
                        }
    					if ($address['text']) {
    						echo '<span class="edu-card__info__row__desc">' . $address['text'] . '</span>';
    					}
    					echo '</p>';
    				}
    				echo '</div>';
    			}
    			?>

    			<?php 
    			if ($item->_phones) {
    				echo '<div class="edu-card__info__row edu-card__info__row_phone">';
    				echo '<span class="edu-card__info__icon glyphicon glyphicon-earphone" title="Телефоны"></span>';
    				foreach ($item->_phones as $phone) {
    					echo '<p>';
    					echo '<span class="edu-card__info__row__title">' . $phone['phone'] . '</span>';
    					if ($phone['text']) {
    						echo '<span class="edu-card__info__row__desc">' . $phone['text'] . '</span>';
    					}
    					echo '</p>';
    				}
    				echo '</div>';
    			}
    			?>

    			<?php 
    			if ($item->_emails) {
    				echo '<div class="edu-card__info__row edu-card__info__row_email">';
    				echo '<span class="edu-card__info__icon glyphicon glyphicon-envelope" title="Почтовые адреса"></span>';
    				foreach ($item->_emails as $email) {
    					echo '<p>';
    					echo '<span class="edu-card__info__row__title"><a href="mailto:' . $email['email'] . '">' . $email['email'] . '</a></span>';
    					if ($email['text']) {
    						echo '<span class="edu-card__info__row__desc">' . $email['text'] . '</span>';
    					}
    					echo '</p>';
    				}
    				echo '</div>';
    			}
    			?>
    			<div style="clear: both;"></div>
    		</div>
    	</div>

        <div class="edu-card-texts">
            <div class="edu-card-announce">
                <?php echo nl2br($item->announce); ?>
            </div>

            <div class="edu-card-text">
                <div class="wysiwyg_content">
                    <?php echo $item->text; ?>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>


    </div>

    <div class="g-col-39 g-span-10 g-main-col-2">
		<?php
			$this->renderPartial('application.views.blocks.bannerRight', array(
			));
		?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var MapMarkers = function () {
        var map = false;
        var mapObj = false;
        var placemark = false;

        var initMap = function () {
            var obj = $("<div>");
            obj.attr("id", "mapObj");
            obj.attr("class", "map-obj");
            obj.html('<div id="ymap" style="width: 100%; height: 400px; z-index: 99999 !important;"></div><a href="#" class="btn btn-primary btn-close-map">Закрыть</a>');
            $("body").append(obj);
            mapObj = $("#mapObj");
            mapObj.hide();
            mapObj.find(".btn-close-map").click(function(){
                mapObj.hide();
                return false;
            });

            var defaultLatitude = $.data(document, 'yandexDefaultLatitude');
            var defaultLongitude = $.data(document, 'yandexDefaultLongitude')
            var defaultZoom = $.data(document, 'yandexDefaultZoom');

            YMaps.ready(function() {
                map = new YMaps.Map('ymap',
                {
                    center:[defaultLatitude, defaultLongitude],
                    // Коэффициент масштабирования
                    zoom:defaultZoom,
                    // Тип карты
                    type: "yandex#map",
                    // Поведение карты
                    behaviors:["default", "scrollZoom"]
                });
                map.controls
                    .add('zoomControl')
                    .add('typeSelector');
            });
        };

        var showPlacemark = function(data) {
            if (placemark) {
                map.geoObjects.remove(placemark);
            }
            var myPlacemark = new YMaps.Placemark([data['lat'], data['lng']], {
                hintContent: data['text']
            });
            map.geoObjects.add(myPlacemark);
            map.setCenter([data['lat'], data['lng']], data['zoom'], {
                checkZoomRange: true
            });
            placemark = myPlacemark;
        }

        initMap();
        $(".map-marker").click(function(){
            var data = {
                'lat' : $(this).attr("data-lat"),
                'lng' : $(this).attr("data-lng"),
                'zoom' : $(this).attr("data-zoom"),
                'text' : $(this).html()
            };
            showPlacemark(data);
            mapObj.show();
        });

    };

    var mapMarkers = new MapMarkers(); 
});
</script>