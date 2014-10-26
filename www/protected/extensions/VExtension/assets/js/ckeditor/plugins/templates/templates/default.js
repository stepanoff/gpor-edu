/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default', {
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath: CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

	// The templates definitions.
	templates :
		[
			{
				title: 'Цитата с картинкой',
				image: '1.png',
				description: '',
				html:
					'<div class="pic-with-cite"><h3 class="pic-with-cite-header">Заголовок цитаты:</h3><p class="fo-width-120"><img src="' + $(document).data("portal.resources") + '/img/ckeditor/pic_comments.jpg" alt="" /></p><blockquote><p>Текст цитаты</p></blockquote></div><p></p>'
			},
			{
				title: 'Цитата без картинки',
				image: '2.png',
				description: '',
				html:
					'<h3 class="pic-without-cite-header">Заголовок цитаты:</h3><ins>Текст цитаты</ins><p></p>'
			},
			{
				title: 'Картинка с подписью',
				image: '3.png',
				description: '',
				html:
					'<table border="0" cellpadding="0" cellspacing="0" class="simple-picture"><thead><tr><th><img alt="" src="' + $(document).data("portal.resources") + '/img/ckeditor/pic_img.jpg" title=""></th></tr></thead><tbody><tr><td><p><em>Текст</em></p></td></tr></tbody></table><p></p>'
			},
			{
				title: 'Таблица 3x3',
				image: '6.png',
				description: '',
				html:
					'<p><em>Заголовок таблицы</em></p><table width="100%"><tbody><tr><th>Заголовок</th><th>Заголовок</th><th>Заголовок</th></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table>'
			}
		]
} );
