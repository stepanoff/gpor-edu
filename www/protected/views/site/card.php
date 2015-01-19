<?php
	Yii::app()->VExtension->registerGlyphicons();
    Yii::app()->VExtension->registerMaps();
?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->staticUrl; ?>css/card.css" media="all">

<div class="g-48">
    <div class="g-col-1 g-span-37">

    	<div class="edu-card" style="background-image: url(<?php echo Yii::app()->fileManager->getImageThumbUrlByUid($item->image, 960, false); ?>);">
    		<div class="edu-card__heading">
    			<h1 class="edu-card__title"><?php echo $item->getFullTitle(); ?></h1>
    		</div>
    		<div class="edu-card__info">
                <?php
                if ($item->logo) {
                    $logo = Yii::app()->fileManager->getImage($item->logo);
                    if ($logo) {
                        $logoThumb = $logo->getThumb (180, 180);
                        $logoHeight = $logoThumb->getHeight();
                    ?>
                <div class="edu-card__logo"><img style="margin-top: -<?php echo ceil($logoHeight/2); ?>px;" width="" src="<?php echo $logo->getThumbUrl(180, 180); ?>" alt="<?php echo CHtml::encode($item->getFullTitle()); ?>"></div>
                    <?php
                    }
                }
                else  {
                    ?>
                <div class="edu-card__logo"></div>
                    <?php
                }
                ?>

                <?php 
                if ($item->site) {
                    echo '<div class="edu-card__info__row edu-card__info__row_site">';
                    echo '<p><a href="http://66.ru/go/' . str_replace('http://', '', $item->site) . '"><span class="edu-card__info__row__title">' . $item->site . '</span></a></p>';
                    echo '</div>';
                }
                ?>

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
                    <?php
                    $res = $item->getParsedText();
                    if ($res) {
                        foreach ($res as $textBlock) {
                            if ($textBlock['title']) {
                                echo '<div class="edu-card-textBlock">';
                                echo '<h2 class="edu-card-textBlock__title">' . $textBlock['title'] . '<span class="glyphicon glyphicon-collapse-down"></span></h2>';
                            }
                            if ($textBlock['text']) {
                                echo '<div class="edu-card-textBlock__content">' . $textBlock['text'] . '</div>';
                            }
                            if ($textBlock['title']) {
                                echo '</div>';
                            }
                        }
                    }
                    ?>
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

    $(".edu-card-textBlock").delegate(".edu-card-textBlock__title", "click", function(){
        var el = $(this).closest(".edu-card-textBlock").find(".edu-card-textBlock__content");
        if ($(this).hasClass("edu-card-textBlock__title_open")) {
            el.hide();
            $(this).find("span").removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
            $(this).removeClass("edu-card-textBlock__title_open");
        }
        else {
            el.show();
            $(this).find("span").removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
            $(this).addClass("edu-card-textBlock__title_open");
        }
    });
    $(".edu-card-textBlock").find(".edu-card-textBlock__content").hide();

});
</script>