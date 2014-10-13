<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Charset must defined in the begining for following fields to be interpret correctly -->
    <meta charset="utf-8">

    <!-- CHECKLIST: Remove if activated in .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!-- CHECKLIST: Test on CMS
       SEO stuff, need to be on every indexing page -->
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link href="http://s.66.ru/localStorage/14/b5/cd/20/14b5cd20.ico" type="image/x-icon" rel="icon">
    <link href="http://s.66.ru/localStorage/14/b5/cd/20/14b5cd20.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="apple-touch-icon" href="http://s.66.ru/localStorage/3f/a4/74/1./3fa4741.png">

    <!-- Mobile viewport optimized: h5bp.com/viewport -->
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" type="text/css" href="http://t.66.ru/external/fontawesome/css/font-awesome.css">
	<script type="text/javascript" src="http://ptrck.ru/pt2.js"></script>
	<title>Современный портал Екатеринбурга - 66.ru</title>

    <link type="text/css" rel="stylesheet" href="http://t.66.ru/external/fonts/PT_Sans_Narrow.css">
    <link href="http://t.66.ru/c31af5cf43126a2522242915871bebd371dd840c/bd4d7bc0a23ef0c60b69dff1b129f811/client.css" rel="stylesheet" type="text/css">
	<script src="http://t.66.ru/c31af5cf43126a2522242915871bebd371dd840c/bd4d7bc0a23ef0c60b69dff1b129f811/client.js" type="text/javascript"></script>
</head>

<body class="i-branding">
	<div class="js_popup" style="display: none;">
		<div class="js_popup_overlay"></div>
		<div class="js_popup_sizer"></div> <div class="js_frame js_popup_frame">
		<div class="js_frame_wrap js_popup_frame_wrap">
			<div class="js_frame_top-left js_popup_frame_top-left"></div>
			<div class="js_frame_top-right js_popup_frame_top-right"></div>
			<div class="js_frame_content js_popup_frame_content">
				<div class="js_popup_close"></div>
				<div class="js_popup_title"></div>
				<div class="js_popup_content rc5"></div>
			</div>
			<div class="js_frame_bottom-left js_popup_frame_bottom-left"></div>
			<div class="js_frame_bottom-right js_popup_frame_bottom-right"></div>
		</div>
		<div id="js_popup_custom_view"></div>
		</div>
	</div>

<div class="l-body">
    

    <div class="l-branding-top">
        <div class="l-branding-top-repeat"></div>
        <div class="l-branding-top-bg"></div>
        <div class="_l-width">
                    </div>
    </div>

    <div class="l-header-wrap">

    <div class="l-width">
        <div class="g-48">
            <div class="g-col-1 g-span-28 js-main-col">
                <a class="b-header-logo" href="/"><img class="b-header-logo__img" src="http://s.66.ru/localStorage/e3/57/b6/4f/e357b64f.png"></a>

                <?php
				$this->renderPartial('application.views.blocks.menu', array(
				));
                ?>


            </div>
            <div class="g-col-30 g-span-19 g-main-col-2">
                <div class="g-48">
                    <div class="g-col-1 g-span-24">

                        <form class="b-header-search" action="http://66.ru/search/" method="GET">
                            <input class="b-header-search__text" type="text" placeholder="Поиск" value="" name="searchString">
                            <input class="b-header-search__subm" type="submit" value="">
                        </form>

                    </div>
                    <div class="g-col-25 g-span-24">
		<table class="b-header-auth">
        <tbody><tr>
            <td class="b-header-auth__tab b-header-auth__tab_active_yes">
                <div class="b-header-auth__tab__logined-user">
                    <div class="b-header-auth__tab__logined-user-ellipsed">
                        <a href="http://66.ru/user/3160/" class="b-header-auth__tab__logined-user__link">
                            <span class="b-header-auth__tab__logined-user__link__pic" style="background-image: url(http://s.66.ru/avatars/avatar/3160.jpg);"></span>
                            stepanoff
                        </a>
                    </div>
                    <a href="http://auth.66.ru/logout/?token=3a03a2d2de13f5ec1b6db547ecf1bc1b&amp;returnUrl=http%3A%2F%2F66.ru%2F" class="b-header-auth__tab__logout-link"><img class="b-icon b-icon-logout" src="http://t.66.ru/c31af5cf43126a2522242915871bebd371dd840c/img/empty.gif" alt="Выйти" title="Выйти"></a>
                </div>
            </td>
                            <td class="b-header-auth__tab">
                    <a id="mailCountContainer" class="b-header-auth__tab__mail-link b-header-auth__tab__mail-link_has_new" href="http://mail.66.ru">
                        <ins class="b-header-auth__tab__mail-link__label">Почта</ins>
                                            </a>
                </td>
                    </tr>
		</tbody>
		</table>
        <script>
        try {
                            $.ajax({
                    url: '/profile/getmail/',
                    dataType: 'text',
                    success: function(response) {
                        if (response > 0) {
                            if($('#mailCount').length > 0) {
                                $('#mailCount').text(response);
                            } else {
                                $('#mailCountContainer').append('<span id="mailCount" class="b-header-auth__tab__mail-link__count">'+ response +'</span>')
                            }
                        }
                    }
                });
                    } catch(err) {}
    </script>
                        </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="l-body-wrap">
		<div class="l-width">

			<?php echo $content; ?>
	            
		</div>
	</div>

	<div class="l-branding-footer">
		<div class="l-branding-footer-repeat"></div>
		<div class="l-branding-footer-bg"></div>
		<div class="l-width"></div>
	</div>
	<div class="l-push"></div>

</div>

<div class="l-footer">
    <div class="l-footer-bg"></div>         <div class="l-width">
        <div class="g-48">
            <div class="g-col-1 g-span-48">


                                <a class="b-footer-logo admin-switch-btn" href="/admin/admin/toggleAdminMode/">
                    <div class="text">
                                
                                <img class="b-footer-logo__pic" src="http://s.66.ru/localStorage/8c/a4/72/99/8ca47299.png">
                
                                    </div>
                    <div class="info">Admin mode ON</div>
                </a>
                

                                <ul class="b-footer-menu">
                                            <li><a href="http://66.ru/news/">Новости</a></li>
                                            <li><a href="http://66.ru/bank/">Банки</a></li>
                                            <li><a href="http://66.ru/rabota/">Работа</a></li>
                                            <li><a href="http://66.ru/doska/">Объявления</a></li>
                                            <li><a href="http://66.ru/realty/">Недвижимость</a></li>
                                            <li><a href="http://66.ru/auto/">Авто</a></li>
                                            <li><a href="http://66.ru/talk/">Общение</a></li>
                                            <li><a href="http://66.ru/afisha/">Афиша</a></li>
                                            <li><a href="http://66.ru/gurman/">Еда</a></li>
                                            <li><a href="http://66.ru/skidki/">«Клуб 66»</a></li>
                                            <li><a href="http://66.ru/health/">Здоровье</a></li>
                                            <li><a href="http://66.ru/konkurs/">Конкурсы</a></li>
                                            <li><a href="http://66.ru/help/">Справка</a></li>
                                            <li><a href="http://mail.66.ru">Почта</a></li>
                                    </ul>

                <div class="b-footer-seo">
        
            
                				<ul class="b-footer-links">
					<li><a href="http://66.ru/about/">О проекте</a></li>
					<li><a href="http://66.ru/advert/">Размещение рекламы</a></li>
				</ul>
				<ul class="b-footer-links">
					<li><a href="http://66.ru/agreement/">Пользовательское соглашение</a></li>
					<li><a href="http://support.66.ru">Техподдержка</a></li>
				</ul>
				<ul class="b-footer-links">
					<li><a href="http://66.ru/lite/setmobile/">Мобильная версия</a></li>
					<li><a href="http://66.ru/rabota/office/82788/">Наши вакансии</a></li>
				</ul>

				<ul class="b-footer-links">
					<li></li>
				</ul>


                </div>

            </div>
        </div>
                    <a href="/admin" class="to-admin-btn">Админка</a>
            </div>
</div>

</body></html>