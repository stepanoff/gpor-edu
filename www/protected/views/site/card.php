<?php
	Yii::app()->VExtension->registerGlyphicons();
?>
<style>
.edu-card {
	border-radius: 4px;
	background: url(http://s.66.ru/localStorage/collection/b0/87/eb/c7/b087ebc7.jpg) center center no-repeat;
	position: relative;
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

</style>

<div class="g-48">
    <div class="g-col-1 g-span-37">

    	<div class="edu-card" style="">
    		<div class="edu-card__heading">
    			<h1 class="edu-card__title"><?php echo $item->getFullTitle(); ?></h1>
    		</div>
    		<div class="edu-card__info">
	    		<div class="edu-card__logo"><img width="180" src="http://s.66.ru/localStorage/c5/3d/32/9c/c53d329c.jpg"></div>
    			<?php 
    			if ($item->_addresses) {
    				echo '<div class="edu-card__info__row edu-card__info__row_address">';
    				echo '<span class="edu-card__info__icon glyphicon glyphicon-map-marker" title="Адреса"></span>';
    				foreach ($item->_addresses as $address) {
    					echo '<p>';
    					echo '<span class="edu-card__info__row__title"><a href="#">' . $address['address'] . '</a></span>';
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


    </div>

    <div class="g-col-39 g-span-10 g-main-col-2">
		<?php
			$this->renderPartial('application.views.blocks.bannerRight', array(
			));
		?>
    </div>
</div>