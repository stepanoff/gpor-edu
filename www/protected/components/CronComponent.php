<?php
/**
 * CronComponent class file.
 *
 * @author Stepanoff Alex <stenlex@gmail.com>
 */
class CronComponent extends CApplicationComponent {
	
	public $commands = array();

    public $logPath = '';
	
	/**
	 * Returns declared commands
	 * @return array commands.
	 */
	public function getCommands() {
        return array (
            'removeSessionTokens' => array (
                'class' => 'RemoveSessionTokensCommand',
                'title' => 'Удаление старых сессионых токенов',
                'name' => 'removeSessionTokens',
                'period' => '/10 * * * *',
            ),
            'removeStorageTokens' => array (
                'class' => 'RemoveStorageTokensCommand',
                'title' => 'Удаление протухших токенов',
                'name' => 'removeStorageTokens',
                'period' => '0 3 * * *',
            ),
            'updateUserInfo' => array (
                'class' => 'UpdateUserInfoCommand',
                'title' => 'Обновление данных пользователей',
                'name' => 'updateUsers',
                'period' => '/10 * * * *',
            ),
        );
	}
	
	protected function getCommand($command) {
		$commands = $this->getCommands();
		if (!isset($commands[$command]))
			throw new CronException(Yii::t('auth_backend', 'Undefined command name: {command}', array('{command}' => $command)), 500);
		return $commands[$command];
	}
	
	public function getCommandClass($command) {
        $commands = $this->getCommands();
		if (!isset($commands[$command]))
			throw new CronException(Yii::t('essentialdata', 'Undefined command name: {command}', array('{command}' => $command)), 500);
		$command = $commands[$command];
		
		$class = $command['class'];
		$point = strrpos($class, '.');
		// if it is yii path alias
		if ($point > 0) {
			Yii::import($class);
			$class = substr($class, $point + 1);
		}
		unset($command['class']);
		$commandClass = new $class();
		$commandClass->init($this, $command);
		return $commandClass;
	}
	
	public function runCommand($command)
	{
		$commandClass = $this->getCommandClass($command);
		$commandClass->run();
	}
	
	public function initCommand($command)
	{
		$commandClass = $this->getCommandClass($command);
		$period = $commandClass->getCommandPeriod();
		if (!$period)
			return false;

		list($minute, $hour, $day, $month, $dayOfWeek) = preg_split('/\s+/', $period) + array('*','*','*','*','*');

		$run = $this->parseTimeArgument($minute, date('i'));
		$run = $run && $this->parseTimeArgument($hour, date('G'));
		$run = $run && $this->parseTimeArgument($day, date('j'));
		$run = $run && $this->parseTimeArgument($month, date('n'));
		$run = $run && $this->parseTimeArgument($dayOfWeek, date('N'));

		if ( $run )
		{
        	return $run;
        }
			
		return false;
	}
	
	public function report ($message)
	{
		$data = array (
			'html' => $message,
			'text' => '',
			'subject' => Yii::app()->name . ': report',
		);
		
		return MailHelper::sendMailToAdmin($data);
		
	}
	
	
    /**
     * Проверка, что наступило время из текстового поля периода запуска :-)
     * Функция рекурсивна!
     * Допустимый формат строки: 1 или 2-5 или 6,7 или 8,9-12
     *
     * @param string $string Строка для сравнения
     * @param mixed $compare Значение, с которым сравниваем
     * @return boolean Подходит или нет
     */
    public function parseTimeArgument($string, $compare)
    {
        if ( $string === '*' )
        {
            return true;
        }

        if ( strpos($string, ',') )
        {
            $string = explode(',', $string);
            foreach ( $string as $element )
            {
                if ( $this->parseTimeArgument($element, $compare) )
                {
                    return true;
                }
            }
            return false;
        }
        else
        {
            if ( strpos($string, '-') )
            {
                list($min, $max) = explode('-', $string);
                return ($compare >= $min) && ($compare <= $max);
            }
            elseif ( substr($string, 0, 1) == '/' )
            {
                return !($compare % substr($string, 1));
            }
            else
            {
                return $compare == $string;
            }
        }
    }

}

class CronException extends CException {}