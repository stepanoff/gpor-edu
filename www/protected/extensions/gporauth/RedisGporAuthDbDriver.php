<?php
/**
 * RedisGporAuthDbDriver interface
 *
 * @author stepanoff <stenlex@gmail.com>
 * @since 1.0
 */
require_once (LIB_PATH. '/redisServer/RedisServer.php');
require_once ('IGporAuthDbDriver.php');


class RedisGporAuthDbDriver implements IGporAuthDbDriver
{
    const TTOKEN_LIFETIME = 60;
    private $_server = null;

    public static function getUserFileds()
    {
        return array('id', 'name', 'username', 'avatar', 'photo', 'gender', 'url', 'service', 'serviceId', 'email', 'updated', 'birthday');
    }

    public function __construct ($host = false, $port = false)
    {
        $host = $host ? $host : Yii::app()->params['redis_host'];
        $port = $port ? $port : Yii::app()->params['redis_port'];
        $this->_server = new Jamm\Memory\RedisServer($host, $port);
    }

    public function getServer()
    {
        return $this->_server;
    }

    public function findByService($service, $serviceId)
    {
        $uid = $this->_server->send_command('get', 'service:'.$service.':'.md5($serviceId));
        if ($uid)
            return $this->findByPk($uid);
        return false;
    }

    public function findByToken($token)
    {
        $res = $this->_server->send_command('hmget', 'token:'.$token, 'id', 'expire');
        list($uid, $expire) = $res;
        if ($uid)
        {
            $userInfo = $this->findByPk($uid);
            if ($userInfo)
            {
                $userInfo['duration'] = $expire;
                return $userInfo;
            }
        }
        return false;
    }

    public function findByPk($id)
    {
        //$res = $this->_server->send_command('hmget', 'user:'.$id, 'uid', 'username', 'name', 'avatar', 'photo', 'service', 'serviceId', 'email', 'gender', 'url', 'updated');
        $args = array_merge(array('hmget', 'user:'.$id), $this->getUserFileds());
        $res = call_user_func_array(array( $this->_server, "send_command"), $args);
        $userInfo = array();
        $i = 0;
        foreach ($this->getUserFileds() as $key)
        {
            $userInfo[$key] = $res[$i];
            $i++;
        }

        return $id ? $userInfo : false;
    }

    public function addUser($data)
    {
        $id = $this->_server->send_command('incr', 'global:nextUserId');
        $fields = array();

        foreach ($this->getUserFileds() as $key)
        {
            $fields[] = $key;
            if ($key == 'id')
                $fields[] = $id;
            elseif ($key == 'updated')
                $fields[] = date ('Y-m-d G:i:s');
            else
                $fields[] = isset($data[$key]) ? $data[$key] : '';
        }

        $res = $this->_server->send_command('set', 'service:'.($data['service'].':'.md5($data['serviceId']) ), $id);
        if ($res)
        {
            $args = array_merge(array('hmset', 'user:'.$id), $fields);
            $res = call_user_func_array(array( $this->_server, "send_command"), $args);
        }
        return $res ? $id : false;
    }

    public function updateByPk($id, $data)
    {
        $fields = array();

        foreach ($this->getUserFileds() as $key)
        {
            $fields[] = $key;
            if ($key == 'id')
                $fields[] = $id;
            elseif ($key == 'updated')
                $fields[] = date ('Y-m-d G:i:s');
            else
                $fields[] = isset($data[$key]) ? $data[$key] : '';
        }
        $args = array_merge(array('hmset', 'user:'.$id), $fields);
        $res = call_user_func_array(array( $this->_server, "send_command"), $args);
        return $res ? true : false;
    }

    public function addToken($userId, $tokenValue, $duration = 0)
    {
        $expire = $duration ? (time() + $duration) : 0;
        $res = $this->_server->send_command('hmset', 'token:'.$tokenValue, 'id', $userId, 'expire', $expire, 'created', time() );
        // очередь токенов на удаление
        $res = ($duration==0)
            ? $this->_server->send_command('rpush', 'token_queue', $tokenValue)
            : $this->_server->send_command('rpush', 'token_storage', $tokenValue);
        return $res ? true : false;
    }

    public function addTemporaryToken($realToken, $tokenValue)
    {
        $res = $this->_server->send_command('setex', 'ttoken:'.$tokenValue, self::TTOKEN_LIFETIME, $realToken );
        return $res ? true : false;
    }

    public function removeTemporaryToken($token)
    {
        $res = $this->_server->send_command('del', 'ttoken:'.$token );
        return $res ? true : false;
    }

    public function removeToken($token)
    {
        $res = $this->_server->send_command('del', 'token:'.$token );
        return $res ? true : false;
    }

    public function findTokenByTemporaryToken($ttoken)
    {
        $res = $this->_server->send_command('get', 'ttoken:'.$ttoken );
        return $res ? $res : false;
    }

    public function findUidByToken($token)
    {
        $res = $this->_server->send_command('hmget', 'token:'.$token, 'id', 'expire' );
        return ($res && $res[0]) ? $res[0] : false;
    }

    public function findToken($token)
    {
        $res = $this->_server->send_command('hmget', 'token:'.$token, 'id', 'expire', 'created');
        if ($res && $res[0])
        {
            return array(
                'id' => $res[0],
                'expire' => $res[1],
                'created' => isset($res[2]) ? $res[2] : 0,
            );
        }
        return false;
    }

    public function addTokenToRemovalQueue($token)
    {
        $res = false;
        $tokenData = $this->findToken($token);
        if ($tokenData)
        {
            $res = $this->_server->send_command('hmset', 'token:'.$token, 'id', $tokenData['id'], 'expire', '0', 'created', $tokenData['created']);
            if ($res)
                $res = $this->_server->send_command('rpush', 'token_queue', $token);
        }
        return $res ? true : false;
    }

    public function addUserToQueue($id)
    {
        $res = false;
        if ($this->findByPk($id))
            $res = $this->_server->send_command('rpush', 'user_update_queue', $id);

        return $res ? true : false;
    }

    public function removeLastQueueToken($maxTime)
    {
        $length = $this->_server->send_command('llen', 'token_queue');
        if (!$length)
            return false;

        $lastToken = $this->_server->send_command('lindex', 'token_queue', 0);
        if (!$lastToken)
            return false;

        $token = $this->findToken($lastToken);
        if (!$token)
        {
            $this->_server->send_command('lpop', 'token_queue');
            return true;
        }

        if ($token['created'] < $maxTime)
        {
            $this->removeToken($lastToken);
            $this->_server->send_command('lpop', 'token_queue');
            return true;
        }
        return false;
    }

    public function removeSessionTokens($maxTime)
    {
        $length = $this->_server->send_command('llen', 'token_storage');
        if ($length)
        {
            $n = 0;
            for ($i = 0; $i < $length; $i++)
            {
                $token =  $this->_server->send_command('lindex', 'token_storage', $n);
                if (!$token)
                {
                    $res =  $this->_server->send_command('lpop', 'token_storage');
                    continue;
                }

                $tokenData = $this->findToken($token);

                // если протухла - удаляем
                $removed = false;
                if ($tokenData)
                {
                    if ($tokenData['expire'] < $maxTime)
                    {
                        $this->removeToken($token);
                        $removed = true;
                    }
                }
                else
                    $removed = true;

                // если не найдена сама кука или кука удалена -удаляем из списка
                if ($removed)
                    $token =  $this->_server->send_command('lrem', 'token_storage', '1',  $token);
                else
                    $n++;
            }
        }
        return true;
    }

    public function setUserCustomAttributes($data, $userId)
    {
        $fields = array();

        if(!is_array($data))
            throw new RedisGporAuthDbDriverException(Yii::t('RedisGporAuthDbDriver', 'data is empty.', array(), 'en'), 500);

        if(empty($userId))
            throw new RedisGporAuthDbDriverException(Yii::t('RedisGporAuthDbDriver', 'user id is empty.', array(), 'en'), 500);

        foreach ($data as $key => $value)
        {
            $fields[] = $key;
            $fields[] = $value;
        }

        $args = array_merge(array('hmset', 'notificationInfo:'.$userId), $fields);
        $res = call_user_func_array(array( $this->_server, "send_command"), $args);

        return $res ? true : false;
    }

    public function getUserCustomAttributes($userId)
    {
        if(empty($userId))
            throw new RedisGporAuthDbDriverException(Yii::t('RedisGporAuthDbDriver', 'user id is empty.', array(), 'en'), 500);

        $res = $this->_server->send_command('hgetall', 'notificationInfo:'.$userId);
        if (!$res)
            return false;

        $notificationData = array();
        foreach($res as $keyN => $value)
        {
            if(!($keyN%2) || $keyN == 0)
                $notificationData[$value] = isset($res[$keyN + 1]) ? $res[$keyN + 1] : '';
        }
        return $notificationData;
    }

    public function clear()
    {
        $keys = $this->_server->send_command('keys', 'token:*');
        foreach ($keys as $key)
            $this->_server->send_command('del', $key);

        $keys = $this->_server->send_command('keys', 'ttoken:*');
        foreach ($keys as $key)
            $this->_server->send_command('del', $key);

        $keys = $this->_server->send_command('keys', 'user:*');
        foreach ($keys as $key)
            $this->_server->send_command('del', $key);

        $keys = $this->_server->send_command('keys', 'service:*');
        foreach ($keys as $key)
            $this->_server->send_command('del', $key);

        $keys = $this->_server->send_command('keys', 'notificationInfo:*');
        foreach ($keys as $key)
            $this->_server->send_command('del', $key);

        do
        {
            $res = $this->_server->send_command('lpop', 'user_update_queue');
        }
        while ($res !== null);

        $this->_server->send_command('del', 'global:nextUserId');

        // Удаляем токены
        while ($this->_server->send_command('rpop', 'token_queue'));
        while ($this->_server->send_command('rpop', 'token_storage'));

        return true;
    }
}

class RedisGporAuthDbDriverException extends Exception {}