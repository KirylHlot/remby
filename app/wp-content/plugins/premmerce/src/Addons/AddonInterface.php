<?php namespace Premmerce\Premmerce\Addons;


interface AddonInterface{

	/**
	 * AddonInterface constructor.
	 *
	 * @param string $mainFile
	 */
	public function __construct($mainFile);

	/**
	 * @return mixed
	 */
	public function run();

	/**
	 * @return mixed
	 */
	public function activate();


	/**
	 * @return mixed
	 */
	public function deactivate();


	/**
	 * @return mixed
	 */
	public static function uninstall();

}