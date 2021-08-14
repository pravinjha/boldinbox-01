<?php

namespace RainLoop\Providers\Settings;

class DefaultSettings implements \RainLoop\Providers\Settings\SettingsInterface
{
	const FILE_NAME = 'settings';

	/**
	 * @var \RainLoop\Providers\Storage
	 */
	private $oStorageProvider;

	/**
	 * @param \RainLoop\Providers\Storage $oStorageProvider
	 */
	public function __construct(\RainLoop\Providers\Storage $oStorageProvider)
	{
		$this->oStorageProvider = $oStorageProvider;
	}

	/**
	 * @param \RainLoop\Account $oAccount
	 *
	 * @return array
	 */
	public function Load(\RainLoop\Account $oAccount)
	{
		$sValue = $this->oStorageProvider->Get($oAccount,
			\RainLoop\Providers\Storage\Enumerations\StorageType::CONFIG,
			\RainLoop\Providers\Settings\DefaultSettings::FILE_NAME);

		$aSettings = array();
		if (\is_string($sValue))
		{
			$aData = \json_decode($sValue, true);
			if (\is_array($aData))
			{
				$aSettings = $aData;
			}
		}

		return $aSettings;
	}

	/**
	 * @param \RainLoop\Account $oAccount
	 * @param array $aSettings
	 *
	 * @return bool
	 */
	public function Save(\RainLoop\Account $oAccount, array $aSettings)
	{
		return $this->oStorageProvider->Put($oAccount,
			\RainLoop\Providers\Storage\Enumerations\StorageType::CONFIG,
			\RainLoop\Providers\Settings\DefaultSettings::FILE_NAME,
			\json_encode($aSettings));
	}
}