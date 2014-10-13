<?php

require_once 'IGporAuthDbDriver.php';

class MysqlGporAuthDbDriver implements IGporAuthDbDriver
{
    const TTOKEN_LIFETIME = 60;

    private $db = null;

    public $userModel = 'User';
    public $tokenModel = 'Token';
    public $temporaryTokenModel = 'TemporaryToken';

    private $usersTable = 'users';
    private $tokensTable = 'tokens';
    private $tempTokensTable = 'temporary_tokens';
    private $notifyTable = 'notification_info';
    private $usersQueueTable = 'user_update_queue';


    public function __construct($host=false, $user=false, $password=false, $dbName=false)
    {
        $host       = $host ?       $host :     Yii::app()->params['db_host'];
        $user       = $user ?       $user :     Yii::app()->params['db_user'];
        $password   = $password ?   $password : Yii::app()->params['db_password'];
        $dbName     = $dbName ?     $dbName :   Yii::app()->params['db_name'];
        $charset = 'utf8';

        $this->db = new mysqli($host, $user, $password, $dbName);
        $this->db->set_charset($charset);

        // Используем процедурный стиль, чтобы гарантировать работу на php до 5.2.9
        if (mysqli_connect_error())
            throw new Exception( mysqli_connect_error(), mysqli_connect_errno());

        register_shutdown_function(array(&$this, 'close')); 
    }

    public function close()
    {
        $this->db->close();
    }

    public function findByService($service, $serviceId)
    {
        return $this->find('SELECT * FROM :usersTable WHERE `service` = ":service" AND `serviceId` = ":serviceId"', array(
            ':usersTable' => $this->usersTable,
            ':service' => $service,
            ':serviceId' => $serviceId,
        ), true);
    }

	public function findByToken($token)
    {
        $token = $this->findToken($token);
        if ($token)
        {
            $user = $this->findByPk($token['userId']);
            if ($user)
            {
                $user['duration'] = $token['expire'];
                return $user;
            }
        }
        return false;
    }

    public function findByPk($id)
    {
        $r = $this->find('SELECT * FROM :usersTable WHERE `id` = :userId', array(
            ':usersTable' => $this->usersTable,
            ':userId' => $id
        ), true);
        if (empty($r))
            return false;
        return $r;
    }

	public function addUser($data)
    {
        $className = $this->userModel;
        $user = new $className;

        // создаем пользователя всегда со сквозным id
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $user->setAttributes($data);
        $user->updated = date('Y-m-d G:i:s');
        if ($user->save())
            return $user->id;
        return false;
    }

    public function updateByPk($id, $data)
    {
        $user = CActiveRecord::model($this->userModel)->findByPk($id);
        if (!$user)
            return false;
        unset($data['id']);
        $user->setAttributes($data);
        $user->updated = date('Y-m-d G:i:s');
        if ($user->save())
            return true;
        return false;
    }

	public function addToken($userId, $tokenValue, $duration = 0)
    {
        $className = $this->tokenModel;
        $token = new $className;
        $token->userId  = $userId;
        $token->token   = $tokenValue;
        $token->expire  = $duration ? (time() + $duration) : 0;
        if ($token->save())
            return true;
        return false;
    }

    public function addTemporaryToken($realToken, $tempToken)
    {
        return $this->query('INSERT INTO :tempTokensTable (`token`, `realToken`, `created`, `expire`) VALUES (":token", ":realToken", :created, :expire)', array(
            ':tempTokensTable' => $this->tempTokensTable,
            ':token' => $tempToken,
            ':realToken' => $realToken,
            ':created' => time(),
            ':expire' => time() + self::TTOKEN_LIFETIME,
        ));
    }

    public function removeTemporaryToken($tempToken)
    {
        return $this->query('DELETE FROM :tempTokensTable WHERE `token` = ":token"', array(
            ':tempTokensTable' => $this->tempTokensTable,
            ':token' => $tempToken
        ));
    }

    public function removeToken($token)
    {
        return $this->query('DELETE FROM :tokensTable WHERE `token`=":token"', array(
            ':tokensTable' => $this->tokensTable,
            ':token' => $token
        ));
    }

    public function findTokenByTemporaryToken($tempToken)
    {
        $token = $this->find('SELECT * FROM :tempTokensTable WHERE `token` = ":token"', array(
            ':tempTokensTable' => $this->tempTokensTable,
            ':token' => $tempToken
        ));
        if ($token)
            return $token->realToken;
        return false;
    }

    public function findUidByToken($token)
    {
        $token = $this->findToken($token);
        if ($token)
            return $token['userId'];
        return false;
    }

    public function findToken($token)
    {
        return $this->find('SELECT * FROM :tokensTable WHERE `token` = ":token"', array(
            ':tokensTable' => $this->tokensTable,
            ':token' => $token
        ), true);
    }

    public function addTokenToRemovalQueue($token)
    {
        $token = $this->findToken($token);
        if (!$token)
            return false;

        return $this->query('UPDATE :tokensTable SET expire = 0 WHERE token = ":token"', array(
            ':tokensTable' => $this->tokensTable,
            ':token' => $token['token']
        ));
    }

    public function removeLastQueueToken($maxTime)
    {
        $token = $this->find('SELECT * FROM :tokensTable WHERE expire = 0 ORDER BY created', array(
            ':tokensTable' => $this->tokensTable
        ));
        if (!$token)
            return false;

        if ($token->created < $maxTime)
        {
            $this->removeToken($token->token);
            return true;
        }
        return false;
    }

    public function removeSessionTokens($maxTime)
    {
        $tokens = $this->findAll('SELECT * FROM :tokensTable WHERE expire != 0 ORDER BY expire', array(
            ':tokensTable' => $this->tokensTable
        ));
        if (!$tokens || empty($tokens))
            return false;

        foreach ($tokens as $token)
        {
            if ($token->expire > $maxTime)
                break;
            $this->removeToken($token->token);
        }
        return true;
    }

    /**
     * Get all users
     * @return array Array of arrays or objects which represent users
     */
    public function getAllUsers()
    {
        return $this->findAll('SELECT * FROM :usersTable', array(
            ':usersTable' => $this->usersTable
        ), true);
    }

    /**
     * Get all users queue
     * @return array Array of arrays or objects which represent users queue item
     */
    public function getAllUsersUpdateQueue()
    {
        return $this->findAll('SELECT * FROM :usersQueueTable', array(
            ':usersQueueTable' => $this->usersQueueTable
        ), true);
    }

    /**
     * Get all tokens
     * @return array Array of arrays or objects which represent token
     */
    public function getAllTokens()
    {
        return $this->findAll('SELECT * FROM :tokensTable', array(
            ':tokensTable' => $this->tokensTable
        ));
    }

    /**
     * Get all notifications
     * @return array Array of arrays or objects which represent notifications
     */
    public function getAllNotifications()
    {
        return $this->findAll('SELECT * FROM :notifyTable', array(
            ':notifyTable' => $this->notifyTable
        ));
    }

    public function setUserCustomAttributes($data, $userId)
    {
        if (!is_array($data))
            throw new Exception(__METHOD__.' : data is empty', 500);

        if (empty($userId))
            throw new Exception(__METHOD__.' : user id is empty', 500);

        $fields = array();
        foreach ($data as $k => $v)
        {
            $arr = array(
                $this->db->real_escape_string($userId),
                $this->db->real_escape_string($k),
                $this->db->real_escape_string($v),
            );
            $fields[] = '("'.implode('","', $arr).'")';
        }
        $values = implode(',',$fields);

        return $this->query('REPLACE INTO :notifyTable (`userId`,`key`,`value`) VALUES '.$values, array(
            ':notifyTable' => $this->notifyTable
        ));
    }

    /**
     * get user info
     */
    public function getUserCustomAttributes($userId)
    {
        if (empty($userId))
            throw new Exception(__METHOD__.' : user id is empty', 500);

        $res = $this->findAll('SELECT * FROM :notifyTable WHERE userId = :userId', array(
            ':notifyTable' => $this->notifyTable,
            ':userId' => $userId
        ), true);
        if (empty($res))
            return false;

        $notificationData = array();
        foreach ($res as $v)
            $notificationData[$v['key']] = $v['value'];
        return $notificationData;
    }

    public function addUserToQueue($userId)
    {
        if (empty($userId))
            throw new Exception(__METHOD__.' : user id is empty', 500);

        $userQueue = new UserUpdateQueue();
        $userQueue->userId = $userId;
        if (!$userQueue->save())
            return false;
        return true;
    }

    public function clear()
    {
        $r = $this->query('TRUNCATE TABLE :tempTokensTable', array(
            ':tempTokensTable' => $this->tempTokensTable,
        ));
        if (!$r)
            return false;

        $r = $this->query('TRUNCATE TABLE :tokensTable', array(
            ':tokensTable' => $this->tokensTable,
        ));
        if (!$r)
            return false;

        $r = $this->query('TRUNCATE TABLE :usersTable', array(
            ':usersTable' => $this->usersTable,
        ));
        if (!$r)
            return false;
        
        $r = $this->query('TRUNCATE TABLE :notifyTable', array(
            ':notifyTable' => $this->notifyTable,
        ));
        if (!$r)
            return false;

        $r = $this->query('TRUNCATE TABLE :usersQueueTable', array(
            ':usersQueueTable' => $this->usersQueueTable,
        ));
        if (!$r)
            return false;

        return true;
    }

    /**
     * Select query for single object
     * @return object|array
     */
    public function find($query, $params, $returnArray = false)
    {
        foreach ($params as $k=>$v)
            $query = preg_replace('/([\W`\"\'])'.$k.'([\W`\"\']|$)/', '${1}'.$this->db->real_escape_string($v).'${2}', $query);

        $obj = $returnArray ? array() : null;

        $res = $this->db->query($query.' LIMIT 1');
        if ($res)
        {
            $obj = $returnArray ? $res->fetch_assoc() : $res->fetch_object();
            $res->close();
        }
        return $obj;
    }

    /**
     * Select query for all objects
     * @return array
     */
    public function findAll($query, $params, $returnArray = false)
    {
        foreach ($params as $k=>$v)
            $query = preg_replace('/([\W`\"\'])'.$k.'([\W`\"\']|$)/', '${1}'.$this->db->real_escape_string($v).'${2}', $query);

        $arr = array();
        $res = $this->db->query($query);
        if ($res)
        {
            do
            {
                $obj = $returnArray ? $res->fetch_assoc() : $res->fetch_object();
                if (!$obj)
                    break;
                $arr[] = $obj;
            } while(true);
            $res->close();
        }
        return $arr;
    }

    /**
     * Delete / insert / update queries
     * @return boolean
     */
    public function query($query, $params)
    {
        foreach ($params as $k=>$v)
            $query = preg_replace('/([\W`\"\'])'.$k.'([\W`\"\']|$)/', '${1}'.$this->db->real_escape_string($v).'${2}', $query);
        return $this->db->query($query);
    }
}
