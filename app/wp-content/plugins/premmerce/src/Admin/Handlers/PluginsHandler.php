<?php namespace Premmerce\Premmerce\Admin\Handlers;


use Premmerce\Premmerce\Api\PluginApi;
use Premmerce\SDK\V2\FileManager\FileManager;

class PluginsHandler{

	/**
	 * @var PluginApi
	 */
	private $api;
	/**
	 * @var FileManager
	 */
	private $fileManager;

	public function __construct(PluginApi $dep, FileManager $fileManager){
		$this->api         = $dep;
		$this->fileManager = $fileManager;
	}

	/**
	 * Run bulk or single action
	 *
	 * @return void
	 */
	public function runAction(){
		$option = isset($_REQUEST['option'])? $_REQUEST['option'] : null;
		$plugin = isset($_REQUEST['value'])? $_REQUEST['value'] : null;


		if($option && $plugin){

			$plugin = urldecode($plugin);

			switch($option){
				case 'activate';
					$callback = [$this->api, 'activatePlugin'];
					break;
				case 'install':
					$callback = [$this->api, 'installPlugin'];
					break;
				case 'deactivate':
					$callback = [$this->api, 'deactivatePlugin'];
					break;
				case 'activate_theme':
					$callback = [$this->api, 'activateTheme'];
					break;
				default:
					$callback = [];
			}

			if(is_callable($callback)){
				$result = call_user_func($callback, $plugin);

				if(!$result){
					$response['success']      = false;
					$response['errorMessage'] = __('Plugin installation failed.');

				}elseif($result instanceof \WP_Error){
					$response['success']      = false;
					$response['errorMessage'] = $result->get_error_message();
				}else{
					$response['success'] = true;
				}

				echo json_encode($response);

				wp_die();
			}


		}

	}

}