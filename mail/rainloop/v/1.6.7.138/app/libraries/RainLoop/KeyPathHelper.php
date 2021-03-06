<?php

namespace RainLoop;

class KeyPathHelper
{
	/**
	 * @param string $sEmail
	 * 
	 * @return string
	 */
	static public function TwoFactorAuthUserData($sEmail)
	{
		return 'TwoFactorAuth/User/'.$sEmail.'/Data/';
	}
	
	/**
	 * @param string $sSsoHash
	 *
	 * @return string
	 */
	static public function SsoCacherKey($sSsoHash)
	{
		return '/Sso/Data/'.$sSsoHash.'/Login/';
	}

	/**
	 * @param string $sSignMeToken
	 *
	 * @return string
	 */
	static public function SignMeUserToken($sSignMeToken)
	{
		return '/SignMe/UserToken/'.$sSignMeToken;
	}

	/**
	 * @param string $sEmail
	 *
	 * @return string
	 */
	static public function WebmailAccounts($sEmail)
	{
		return 'Webmail/Accounts/'.$sEmail.'/Array';
	}

	/**
	 * @param string $sDomain
	 *
	 * @return string
	 */
	static public function LicensingDomainKeyValue($sDomain)
	{
		return 'Licensing/DomainKey/Value/'.$sDomain;
	}

	/**
	 * @param string $sRepo
	 * @param string $sRepoFile
	 *
	 * @return string
	 */
	static public function RepositoryCacheFile($sRepo, $sRepoFile)
	{
		return 'RepositoryCache/Repo/'.$sRepo.'/File/'.$sRepoFile;
	}

	/**
	 * @param string $sRepo
	 *
	 * @return string
	 */
	static public function RepositoryCacheCore($sRepo)
	{
		return 'RepositoryCache/CoreRepo/'.$sRepo;
	}

	/**
	 * @param string $sEmail
	 * @param string $sFolderFullName
	 * @param string $sUid
	 *
	 * @return string
	 */
	static public function ReadReceiptCache($sEmail, $sFolderFullName, $sUid)
	{
		return '/ReadReceipt/'.$sEmail.'/'.$sFolderFullName.'/'.$sUid;
	}

	/**
	 * @param string $sLanguage
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	static public function LangCache($sLanguage, $sPluginsHash)
	{
		return '/LangCache/'.$sPluginsHash.'/'.$sLanguage.'/'.APP_VERSION.'/';
	}
	
	/**
	 * @param bool $bAdmin
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	static public function TemplatesCache($bAdmin, $sPluginsHash)
	{
		return '/TemplatesCache/'.$sPluginsHash.'/'.($bAdmin ? 'Admin' : 'App').'/'.APP_VERSION.'/';
	}

	/**
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	static public function PluginsJsCache($sPluginsHash)
	{
		return '/PluginsJsCache/'.$sPluginsHash.'/'.APP_VERSION.'/';
	}

	/**
	 * @param string $sTheme
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	static public function CssCache($sPluginsHash)
	{
		return '/CssCache/'.$sPluginsHash.'/'.$sTheme.'/'.APP_VERSION.'/';
	}
}
