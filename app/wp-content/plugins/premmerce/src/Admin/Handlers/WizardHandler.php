<?php namespace Premmerce\Premmerce\Admin\Handlers;

use Premmerce\Premmerce\Api\WizardApi;
use Premmerce\SDK\V2\FileManager\FileManager;

class WizardHandler{

	/**
	 * @var WizardApi
	 */
	private $helper;

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @param WizardApi $helper
	 * @param FileManager $fileManager
	 */
	public function __construct(WizardApi $helper, FileManager $fileManager){
		$this->helper      = $helper;
		$this->fileManager = $fileManager;
	}

	/**
	 * Handle action
	 */
	public function runAction(){

		if(isset($_POST['update'])){
			switch($_POST['update']){
				case 'position':
					$this->helper->updatePositions($_POST['positions']);
					break;
				case 'state':
					$this->helper->updateState($_POST['task'], $_POST['state']);
					break;
				case 'container-state':
					$this->handleContainerState();
					break;

			}
		}

	}


	public function handleContainerState(){
		$this->helper->updateContainerState($_POST['state']);

		wp_send_json($this->fileManager->renderTemplate('admin/tabs/wizard.php', [
			'wizard' => $this->helper,
		]));
	}


}