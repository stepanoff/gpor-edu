<?php 
return array(
	'urlFormat'=>'path',
	'showScriptName'=>false,
    'urlSuffix' => '/',
	'rules'=>array(
        '/forgetPass' => 'site/forgetPass',

        '/login' => 'site/login',
        '/register' => 'site/register',
        '/logout' => 'site/logout',

        '/coll/cat<sectionId:([0-9]+)>'=>'vitrinaCollection/section/',
        '/coll/<collectionId:([0-9]+)>/<photoId:([0-9]+)>'=>'vitrinaCollection/show',
        '/coll/<collectionId:([0-9]+)>/<photoId:([0-9]+)>/toggleFavorite'=>'vitrinaCollection/toggleFavorite',
        '/coll/<collectionId:([0-9]+)>'=>'vitrinaCollection/show/',
        '/coll'=>'vitrinaCollection/index/',

        '/card/<id:([0-9]+)>'=>'site/showCard',

        '/cb/edit/<id:([0-9]+)>' => 'VCb/VCb/edit',
        '/cb/edit/' => 'VCb/VCb/edit',

        '/admin/<_c:([a-zA-Z0-9]+)>/<_a:([a-zA-Z0-9]+)>/<id:([0-9]+)>' => 'admin/<_c>/<_a>',
        '/admin/<_c:([a-zA-Z0-9]+)>' => 'admin/<_c>/index',
        '/admin/<_c:([a-zA-Z0-9]+)>/edit' => 'admin/<_c>/edit',

        '/<staticPage:([a-zA-Z0-9_]+)>'=>'staticPage/show',

	),
);
?>