<?php
/**
 * IGporAuthDbDriver interface
 *
 * A user application component represents information
 * for the current user
 */
interface IGporAuthDbDriver
{
    /**
     * Returns an user information array.
     * @param string $service
     * @param string $serviceId
     * @return array array(attribute => value) user information.
     */
    public function findByService($service, $serviceId);

    /**
     * Returns an user information array.
     * @param string $token
     * @return array array(attribute => value) user information.
     */
    public function findByToken($token);

    /**
     * Returns an user information array.
     * @param int $id
     * @return array array(attribute => value) user information.
     */
    public function findByPk($id);

    /**
     * Update user information.
     * @param int $id
     * @param array $data array(attribute => value)
     * @return bool
     */
    public function updateByPk($id, $data);

    /**
     * Creates new user and store information in db.
     * @param array $data array(attribute => value)
     * @return int|bool user id. False on fail
     */
    public function addUser($data);

    /**
     * Add user token.
     * @param int $userId
     * @param string $token
     * @param int $duration
     * @return boolean
     */
    public function addToken($userId, $token, $duration=0);

    /**
     * Add temporary token
     * @param string $realToken
     * @param string $token
     * @return boolean
     */
    public function addTemporaryToken($realToken, $token);

    /**
     * Remove user token.
     * @param string $token
     * @return boolean
     */
    public function removeToken($token);

    /**
     * Remove user temporary token.
     * @param string $token
     * @return boolean
     */
    public function removeTemporaryToken($token);

    /**
     * find token by temporary token
     * @param string $ttoken
     * @return string
     */
    public function findTokenByTemporaryToken($ttoken);

    /**
     * find user uid by token
     * @param string $token
     * @return string
     */
    public function findUidByToken ($token);

    /**
     * @param string $token
     * @return array array(attribute => value)
     */
    public function findToken($token);

    /**
     * @param string $token
     * @return boolean
     */
    public function addTokenToRemovalQueue($token);

    /**
     * Удаляем самый старый сессионный токен если он старше указанного времени
     * @param int $maxTime unix timestamp
     * @return boolean true, если токен был удален и можно пытаться удалить следующий,
     * false - если нечего удалять или мы нашли первый "свежий" токен
     */
    public function removeLastQueueToken($maxTime);

    /**
     * Удаляем токены, поставленный на время, если он старше указанного времени
     * @param int $maxTime unix timestamp
     * @return boolean
     */
    public function removeSessionTokens($maxTime);

    /**
     * @param array $data array(attribute => value)
     * @param int $userId
     * @return bool
     */
    public function setUserCustomAttributes($data, $userId);

    /**
     * @param int $userId
     * @return array array(attribute => value)
     */
    public function getUserCustomAttributes($userId);

    /**
     * Добавление пользователей в очередь на обновление
     * @param int $userId
     * @return bool
     */
    public function addUserToQueue($userId);

    /**
     * Clear tables
     * @return boolean
     */
    public function clear();
}
