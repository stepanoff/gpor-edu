<?php
class VFileManager extends CApplicationComponent {

    const SECURE_KEY = 'C0GITO ERGO SUM';
	
	public $filesPath = null;
    public $filesUrl = null;

    public function getBasePath ()
    {
        return $this->filesPath;
    }

    public function getBaseUrl ()
    {
        return $this->filesUrl;
    }

    public function getImage($uid)
    {
       $file = $this->getFile($uid);
       if (is_object($file) && get_class($file) == 'VImageFile')
           return $file;
       return null;
    }

    public function getFile($uid)
    {
        $path = $this->getStoragePathByUid($uid);
        return VFileBase::createInstance($path, $uid);
    }

    public function publishFile ($filePath, $extensionName)
    {
        $newUid = $this->generateFileUid($filePath, $extensionName);

        $savePath = $this->getStoragePathByUid($newUid);
        $savePathDir = substr($savePath, 0, strrpos($savePath, $newUid));

        if(!is_dir($savePathDir)) {
            mkdir($savePathDir, 0755, true);
            @chmod($savePathDir, 0755);
        }
        copy($filePath, $savePath);
        @chmod($savePath, 0755);

        $fileType = CFileHelper::getMimeType($savePath);
        $resultFile = VFileBase::createInstance($savePath, $newUid);

        if (!is_null($resultFile)) {
            //$resultFile->__convert();
        }
        
        return $resultFile;
    }

    public function deleteFileByUid ($uid)
    {
        $filePath = $this->getStoragePathByUid($uid);
        $file = VFileBase::createInstance($filePath, $uid);
        if ($file)
            $file->delete();
    }

    public function generateFileUid($filePath, $extensionName)
    {
        $resultUid = $this->hash($filePath);
        // uid может содержать в суффиксной части расширение файла, для однозначной дальнешей идентификации и генерации ссылки
        if (!empty($extensionName)) {
            $resultUid .= '.'.$extensionName;
        }

        return $resultUid;
    }

    protected function hash($filePath)
    {
        return sprintf('%x',crc32($filePath.self::SECURE_KEY.(time())));
    }


    protected function getStoragePathByUid($uid)
    {
        $dir = array();
        for ($i = 0; $i < 8; $i+=2) {
            $dir[] = substr($uid, $i, 2); 
        }

        /**
         * особый случай, когда после hex-uid'а файла идёт его расширение
        $ext = '';
        if (($pos = strrpos($uid, '.')) !== false)
        {
            $ext = strtolower(substr($uid, $pos)); // dot is included before ext
        }
         */
        $filePath = $this->getBasePath().DS.implode(DS, $dir).DS.$uid;

        return $filePath;
    }

	public static function getMimeType($file)
	{
		$mimetype = '';
		if(function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE'))
		{
			if(($info=finfo_open(FILEINFO_MIME_TYPE)) && ($result=finfo_file($info,$file))!==false)
				$mimetype = $result;
		} else {
			$mimetype = CFileHelper::getMimeType($file);
			$mimetype = explode(';', $mimetype);
			$mimetype = $mimetype[0];
		}

		if($mimetype=='application/zip') {
			$zip = new ZipArchive();
		    if ($zip->open($file)) {
				// В случае успеха ищем в архиве файл с данными
				if (($index = $zip->locateName('word/document.xml')) !== false) {
					$mimetype = self::$additionalMimeTypes['docx'];
				}
				$zip->close();
			}
		}
		return $mimetype;
	}

}
