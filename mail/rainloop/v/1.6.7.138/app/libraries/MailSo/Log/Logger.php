<?php

namespace MailSo\Log;

/**
 * @category MailSo
 * @package Log
 */
class Logger extends \MailSo\Base\Collection
{
	/**
	 * @var bool
	 */
	private $bUsed;

	/**
	 * @var array
	 */
	private $aForbiddenTypes;

	/**
	 * @var array
	 */
	private $aSecretWords;

	/**
	 * @access protected
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->bUsed = false;
		$this->aForbiddenTypes = array();
		$this->aSecretWords = array();

		\register_shutdown_function(array(&$this, '__loggerShutDown'));
	}

	/**
	 * @return \MailSo\Log\Logger
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @staticvar \MailSo\Log\Logger $oInstance;
	 *
	 * @return \MailSo\Log\Logger
	 */
	public static function SingletonInstance()
	{
		static $oInstance = null;
		if (null === $oInstance)
		{
			$oInstance = self::NewInstance();
		}

		return $oInstance;
	}

	/**
	 * @staticvar string $sCache;
	 *
	 * @return string
	 */
	public static function Guid()
	{
		static $sCache = null;
		if (null === $sCache)
		{
			$sCache = \substr(\md5(\microtime(true).\rand(10000, 99999)), -8);
		}

		return $sCache;
	}

	/**
	 * @return bool
	 */
	public function IsEnabled()
	{
		return 0 < $this->Count();
	}

	/**
	 * @param string $sWord
	 * @return bool
	 */
	public function AddSecret($sWord)
	{
		if (0 < \strlen(\trim($sWord)))
		{
			$this->aSecretWords[] = $sWord;
			$this->aSecretWords = array_unique($this->aSecretWords);
		}
	}

	/**
	 * @param int $iType
	 *
	 * @return \MailSo\Log\Logger
	 */
	public function AddForbiddenType($iType)
	{
		$this->aForbiddenTypes[$iType] = true;

		return $this;
	}

	/**
	 * @param int $iType
	 *
	 * @return \MailSo\Log\Logger
	 */
	public function RemoveForbiddenType($iType)
	{
		$this->aForbiddenTypes[$iType] = false;

		return $this;
	}

	/**
	 * @return void
	 */
	public function __loggerShutDown()
	{
		if ($this->bUsed)
		{
			$aStatistic = \MailSo\Base\Loader::Statistic();
//			$this->WriteDump($aStatistic, \MailSo\Log\Enumerations\Type::INFO);
			if (\is_array($aStatistic) && isset($aStatistic['php']['memory_get_peak_usage']))
			{
				$this->Write('Memory peak usage: '.$aStatistic['php']['memory_get_peak_usage'],
					\MailSo\Log\Enumerations\Type::MEMORY);
			}
		}
	}

	/**
	 * @return bool
	 */
	public function WriteEmptyLine()
	{
		$iResult = 1;
		
		$aLoggers =& $this->GetAsArray();
		foreach ($aLoggers as /* @var $oLogger \MailSo\Log\Driver */ &$oLogger)
		{
			$iResult &= $oLogger->WriteEmptyLine();
		}

		return (bool) $iResult;
	}

	/**
	 * @param string $sDesc
	 * @param int $iType = \MailSo\Log\Enumerations\Type::INFO
	 * @param string $sName = ''
	 * @param bool $bSearchWords = false
	 *
	 * @return bool
	 */
	public function Write($sDesc, $iType = \MailSo\Log\Enumerations\Type::INFO, $sName = '', $bSearchWords = false)
	{
		if (isset($this->aForbiddenTypes[$iType]) && true === $this->aForbiddenTypes[$iType])
		{
			return true;
		}

		$this->bUsed = true;

		$oLogger = null;
		$aLoggers = array();
		$iResult = 1;

		if ($bSearchWords && 0 < \count($this->aSecretWords))
		{
			$sDesc = \str_replace($this->aSecretWords, '*******', $sDesc);
		}

		$aLoggers =& $this->GetAsArray();
		foreach ($aLoggers as /* @var $oLogger \MailSo\Log\Driver */ $oLogger)
		{
			$iResult &= $oLogger->Write($sDesc, $iType, $sName);
		}

		return (bool) $iResult;
	}

	/**
	 * @param mixed $oValue
	 * @param int $iType = \MailSo\Log\Enumerations\Type::INFO
	 * @param string $sName = ''
	 * @param bool $bSearchSecretWords = false
	 *
	 * @return bool
	 */
	public function WriteDump($oValue, $iType = \MailSo\Log\Enumerations\Type::INFO, $sName = '', $bSearchSecretWords = false)
	{
		return $this->Write(\print_r($oValue, true), $iType, $sName, $bSearchSecretWords);
	}

	/**
	 * @param \Exception $oException
	 * @param int $iType = \MailSo\Log\Enumerations\Type::NOTICE
	 * @param string $sName = ''
	 * @param bool $bSearchSecretWords = true
	 *
	 * @return bool
	 */
	public function WriteException($oException, $iType = \MailSo\Log\Enumerations\Type::NOTICE, $sName = '', $bSearchSecretWords = true)
	{
		if ($oException instanceof \Exception)
		{
			if (isset($oException->__LOGINNED__))
			{
				return true;
			}

			$oException->__LOGINNED__ = true;

			return $this->Write((string) $oException, $iType, $sName, $bSearchSecretWords);
		}

		return false;
	}

	/**
	 * @param mixed $mData
	 * @param int $iType = \MailSo\Log\Enumerations\Type::NOTICE
	 * @param string $sName = ''
	 * @param bool $bSearchSecretWords = true
	 *
	 * @return bool
	 */
	public function WriteMixed($mData, $iType = null, $sName = '', $bSearchSecretWords = true)
	{
		$iType = null === $iType ? \MailSo\Log\Enumerations\Type::INFO : $iType;
		if (\is_array($mData) || \is_object($mData))
		{
			if ($mData instanceof \Exception)
			{
				$iType = null === $iType ? \MailSo\Log\Enumerations\Type::NOTICE : $iType;
				return $this->WriteException($mData, $iType, $sName, $bSearchSecretWords);
			}
			else
			{
				return  $this->WriteDump($mData, $iType, $sName, $bSearchSecretWords);
			}
		}
		else
		{
			return $this->Write($mData, $iType, $sName, $bSearchSecretWords);
		}

		return false;
	}
}
