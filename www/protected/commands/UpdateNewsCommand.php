<?php
/*
 * Синхронизация баз старых и новых юзеров
 */
class UpdateNewsCommand extends CConsoleCommand
{
	public function run($args)
	{
        $xmlRpc = new VXmlRpc(Yii::app()->params['gporApiUrl'], Yii::app()->params['gporApiKey'], 'news.listNews');
        $params = array(
            'News',
            array(
                array('type' => 'array', 'value' => array('важно'), 'field' => 'tags'),
            ),
            array('id','simpletitle','title','postTime','annotation', 'commentsCount', 'titlelink', 'fulltitlelink', 'link', 'imageurl', 'containPhoto', 'containVideo', 'containAudio', 'infograph', 'havePoll','newMainImageURL',),
            array('limit' => 10),
        );
        $res = $xmlRpc->send($params);
        if ($res) {
            Yii::app()->cache->set('mainNews', $xmlRpc->getResponseVal(), 3600*24*7);
        }
        else {
            echo 'error';
        }
    }

}