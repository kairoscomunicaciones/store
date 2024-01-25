<?php
/**
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */

namespace Hybridauth\Adapter;

/**
 * Class AbstractDataStore
 */
abstract class AbstractDataStore
{
	/**
	 * Provider ID (unique name).
	 *
	 * @var string
	 */
	protected $providerId = '';

	/**
	 * Returns storage instance
	 *
	 * @return \Hybridauth\Storage\StorageInterface
	 */
	abstract public function getStorage();

	/**
	 * Store a piece of data in storage.
	 *
	 * This method is mainly used for OAuth tokens (access, secret, refresh, and whatnot), but it
	 * can be also used by providers to store any other useful data (i.g., user_id, auth_nonce, etc.)
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	protected function storeData($name, $value = null)
	{
		// if empty, we simply delete the thing as we'd want to only store necessary data
		if (empty($value)) {
			$this->deleteStoredData($name);
		}

		$this->getStorage()->set($this->providerId.'.'.$name, $value);
	}

	/**
	 * Retrieve a piece of data from storage.
	 *
	 * This method is mainly used for OAuth tokens (access, secret, refresh, and whatnot), but it
	 * can be also used by providers to retrieve from store any other useful data (i.g., user_id,
	 * auth_nonce, etc.)
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	protected function getStoredData($name)
	{
		return $this->getStorage()->get($this->providerId.'.'.$name);
	}

	/**
	 * Delete a stored piece of data.
	 *
	 * @param string $name
	 */
	protected function deleteStoredData($name)
	{
		$this->getStorage()->delete($this->providerId.'.'.$name);
	}

	/**
	 * Delete all stored data of the instantiated adapter
	 */
	protected function clearStoredData()
	{
		$this->getStorage()->deleteMatch($this->providerId.'.');
	}
}
