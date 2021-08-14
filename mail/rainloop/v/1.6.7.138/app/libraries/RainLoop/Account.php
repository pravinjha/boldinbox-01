<?php

namespace RainLoop;

class Account
{
	/**
	 * @var string
	 */
	private $sEmail;

	/**
	 * @var string
	 */
	private $sLogin;

	/**
	 * @var int
	 */
	private $sPassword;

	/**
	 * @var string
	 */
	private $sSignMeToken;

	/**
	 * @var \RainLoop\Domain
	 */
	private $oDomain;

	/**
	 * @var string
	 */
	private $sParentEmail;

	/**
	 * @param string $sEmail
	 * @param string $sLogin
	 * @param string $sPassword
	 * @param \RainLoop\Domain $oDomain
	 * @param string $sSignMeToken = ''
	 *
	 * @return void
	 */
	protected function __construct($sEmail, $sLogin, $sPassword, \RainLoop\Domain $oDomain, $sSignMeToken = '')
	{
		$this->sEmail = \MailSo\Base\Utils::IdnToAscii($sEmail, true);
		$this->sLogin = \MailSo\Base\Utils::IdnToAscii($sLogin);
		$this->sPassword = $sPassword;
		$this->oDomain = $oDomain;
		$this->sSignMeToken = $sSignMeToken;
	}

	/**
	 * @param string $sEmail
	 * @param string $sLogin
	 * @param string $sPassword
	 * @param \RainLoop\Domain $oDomain
	 * @param string $sSignMeToken = ''
	 *
	 * @return \RainLoop\Account
	 */
	public static function NewInstance($sEmail, $sLogin, $sPassword, \RainLoop\Domain $oDomain, $sSignMeToken = '')
	{
		return new self($sEmail, $sLogin, $sPassword, $oDomain, $sSignMeToken);
	}

	/**
	 * @return string
	 */
	public function Email()
	{
		return $this->sEmail;
	}

	/**
	 * @return string
	 */
	public function ParentEmail()
	{
		return $this->sParentEmail;
	}
	
	/**
	 * @return string
	 */
	public function ParentEmailHelper()
	{
		return 0 < \strlen($this->sParentEmail) ? $this->sParentEmail : $this->sEmail;
	}

	/**
	 * @return string
	 */
	public function IncLogin()
	{
		$sLogin = $this->sLogin;
		if ($this->oDomain->IncShortLogin())
		{
			$sLogin = \MailSo\Base\Utils::GetAccountNameFromEmail($this->sLogin);
		}

		return $sLogin;
	}

	/**
	 * @return string
	 */
	public function OutLogin()
	{
		$sLogin = $this->sLogin;
		if ($this->oDomain->OutShortLogin())
		{
			$sLogin = \MailSo\Base\Utils::GetAccountNameFromEmail($this->sLogin);
		}

		return $sLogin;
	}

	/**
	 * @return string
	 */
	public function Login()
	{
		return $this->IncLogin();
	}

	/**
	 * @return string
	 */
	public function Password()
	{
		return $this->sPassword;
	}

	/**
	 * @return bool
	 */
	public function SignMe()
	{
		return 0 < \strlen($this->sSignMeToken);
	}

	/**
	 * @return string
	 */
	public function SignMeToken()
	{
		return $this->sSignMeToken;
	}

	/**
	 * @return \RainLoop\Domain
	 */
	public function Domain()
	{
		return $this->oDomain;
	}

	/**
	 * @return \RainLoop\Domain
	 */
	public function Hash()
	{
		return md5(APP_SALT.$this->Email().APP_SALT.$this->oDomain->IncHost(\MailSo\Base\Utils::GetDomainFromEmail($this->Email())).
			APP_SALT.$this->oDomain->IncPort().APP_SALT.$this->Password().APP_SALT.'0'.APP_SALT.$this->ParentEmail().APP_SALT);
	}

	/**
	 * @param string $sPassword
	 *
	 * @return void
	 */
	public function SetPassword($sPassword)
	{
		$this->sPassword = $sPassword;
	}

	/**
	 * @param string $sParentEmail
	 *
	 * @return void
	 */
	public function SetParentEmail($sParentEmail)
	{
		$this->sParentEmail = \MailSo\Base\Utils::IdnToAscii($sParentEmail, true);
	}

	/**
	 * @return string
	 */
	public function GetAuthToken()
	{
		return \RainLoop\Utils::EncodeKeyValues(array(
			'token',
			$this->sEmail,
			$this->sLogin,
			$this->sPassword,
			\RainLoop\Utils::Fingerprint(),
			$this->sSignMeToken,
			$this->sParentEmail,
			\RainLoop\Utils::GetShortToken()
		));
	}
}
