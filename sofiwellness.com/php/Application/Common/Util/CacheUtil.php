<?php
/*
 $id:$
 * FILE:CacheUtil.php
 * CREATE:Apr 17, 2008
 * BY:ghuang
 *
 * NOTE:
 */
require_once ("Zend/Cache.php");

require_once CONF_DIR .'/cache_config.php';

class CacheUtil
{
	const LIFETIME_SHORT = 300;  //5 min
	const LIFETIME_MID = 7200;    //2 hrs
	const LIFETIME_PERM = null;   //forever

	const FRONT_CORE = 'Core';
	const FRONT_OUTPUT = 'Output';
	const FRONT_CLASS = 'class';
	const FRONT_FILE = 'File';
	const FRONT_FUNCTION = 'Function';

	const BACKEND_FILE = 'File';
	const BACKEND_MEMCACHED = 'Memcached';
	const BACKEND_APC = 'Apc';
	const BACKEND_SQLITE = 'Sqlite';


	private $_cache = null;
	private $_lifeTime = CacheUtil::LIFETIME_PERM;
	private $_autoSerialize = false;
	private $_frontend = CacheUtil::FRONT_CORE;
	private $_backend = CacheUtil::BACKEND_FILE;

	private $_cacheDir = CACHE_SHORTTERM;

	public function __construct ($lifeTime = CacheUtil::LIFETIME_PERM,
								 $autoSerialize = false,
								 $frontend= CacheUtil::FRONT_CORE)
	{
		//$_cacheDir = 'cache/tmp';
		$this->_lifeTime = $lifeTime;
		$this->_autoSerialize = $autoSerialize;
		$this->_frontend = $frontend;

		$this->init ();
	}

	public function getCache ()
	{
		return $this->_cache;
	}

	public function load ($id)
	{
		$data = $this->_cache->load($id);
		if ($data == false)
			return null;
		else
			return $data;
	}

	public function save ($id, $data)
	{
		Log::debug ('save to cache:' .$id);
		$this->_cache->save($data, $id);
	}

	public function start ($id)
	{
		if ($this->_frontend != CacheUtil::FRONT_OUTPUT)
			Log::debug ('You call start not for output!!!');

		$this->_cache->start ($id);
	}

	public function end ()
	{
		$this->_cache->end ();
	}

	public function remove ($id)
	{
		$this->_cache->remove ($id);
	}

	private function init ()
	{
		$fOpt = CacheUtil::getFrontOption ($this->_lifeTime, $this->_autoSerialize);
		$bOpt = array (
					//'hashed_directory_level' => 2,
					'cache_dir' => $this->_cacheDir
				);
		Log::debug ('cacheDir:' .$this->_cacheDir);
		try
		{
			$this->_cache = Zend_Cache::factory($this->_frontend, $this->_backend, $fOpt, $bOpt);
		}
		catch (Exception $e)
		{
			Log::debug ('getCache Error:' .$e->getMessage());
		}

		return $this->_cache;
	}

	private function getFrontOption ($lifeTime, $autoSerialize)
	{
		$fOpt = null;
		if ($autoSerialize == true)
		{
			$fOpt = array (
					'lifetime' => $lifeTime,
					'automatic_serialization' => true
				);
		}
		else
		{
			$fOpt = array (
					'lifetime' => $lifeTime
				);
		}
		return $fOpt;
	}
}
?>
