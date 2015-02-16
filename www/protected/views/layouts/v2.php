<?php
    $this->renderPartial('application.views.blocks.head', array(
    ));
?>


    <div class="l-width">
        <div class="g-48">
            <div class="g-col-1 g-span-28 js-main-col">
                <a class="b-header-logo" href="http://66.ru"><img class="b-header-logo__img" src="http://s.66.ru/localStorage/e3/57/b6/4f/e357b64f.png"></a>

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
		<!--table class="b-header-auth">
        <tbody><tr>
            <td class="b-header-auth__tab b-header-auth__tab_active_yes">
                <div class="b-header-auth__tab__logined-user">
                    <div class="b-header-auth__tab__logined-user-ellipsed">
                        <a href="http://66.ru/user/3160/" class="b-header-auth__tab__logined-user__link">
                            <span class="b-header-auth__tab__logined-user__link__pic" style="background-image: url();"></span>
                            
                        </a>
                    </div>
                    <a href="http://auth.66.ru/logout/" class="b-header-auth__tab__logout-link"><img class="b-icon b-icon-logout" src="http://t.66.ru/c31af5cf43126a2522242915871bebd371dd840c/img/empty.gif" alt="Выйти" title="Выйти"></a>
                </div>
            </td>
                            <td class="b-header-auth__tab">
                    <a id="mailCountContainer" class="b-header-auth__tab__mail-link b-header-auth__tab__mail-link_has_new" href="http://mail.66.ru">
                        <ins class="b-header-auth__tab__mail-link__label">Почта</ins>
                                            </a>
                </td>
                    </tr>
		</tbody>
		</table-->
                        </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="l-body-wrap">
		<div class="l-width">
            <?php
                $this->renderPartial('application.views.blocks.submenu', array(
                ));
            ?>

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

<?php
    $this->renderPartial('application.views.blocks.foot', array(
    ));
?>
