<?php namespace Premmerce\Premmerce\Api;

use Premmerce\Premmerce\Data\Tasks;

class WizardApi{

	const KEY_POSITIONS = 'premmerce-wizard-tips-positions';

	const KEY_STATES = 'premmerce-wizard-tips-states';

	const KEY_SWITCHER_STATE = 'premmerce-wizard-switcher-state';


	/**
	 * @var PluginApi
	 */
	private $api;

	/**
	 * @var array
	 */
	private $tasks;

	/**
	 * Wizard constructor.
	 *
	 * @param PluginApi $api
	 */
	public function __construct(PluginApi $api){
		$this->api = $api;
	}

	/**
	 * @param $state
	 */
	public function updateContainerState($state){
		update_option(self::KEY_SWITCHER_STATE, $state);
	}

	/**
	 * @param $positions
	 */
	public function updatePositions($positions){
		update_option(self::KEY_POSITIONS, $positions);
	}

	/**
	 * @param $key
	 * @param $state
	 */
	public function updateState($key, $state){
		$states         = get_option(self::KEY_STATES, []);
		$states[ $key ] = $state;
		update_option(self::KEY_STATES, $states);

	}

	/**
	 * @return int
	 */
	public function getUncompletedTipsCount(){
		$tasks   = $this->getTasks();
		$checked = 0;

		foreach($tasks as $task){
			if($task['state'] !== Tasks::STATE_DEFAULT){
				$checked ++;
			}
		}

		return count($tasks) - $checked;
	}

	/**
	 * @return string
	 */
	public function getContainerState(){
		return get_option(self::KEY_SWITCHER_STATE, Tasks::STATE_DEFAULT);
	}

	/**
	 * @return array
	 */
	private function getPositions(){
		return get_option(self::KEY_POSITIONS, []);
	}

	/**
	 * @return array
	 */
	private function getStates(){
		return get_option(self::KEY_STATES, []);
	}

	/**
	 * @return array
	 */
	public function getTasks(){
		if(is_null($this->tasks)){

			$tasks = Tasks::getAll();
			$tasks = $this->sortTasks($tasks);
			$tasks = $this->setStates($tasks);
			$tasks = $this->setActions($tasks);
			$tasks = $this->checkDependencies($tasks);

			$this->tasks = $tasks;

		}

		return $this->tasks;
	}

	/**
	 *
	 * @param array $tasks
	 *
	 * @return array
	 */
	private function setStates($tasks){
		$states = $this->getStates();

		foreach($tasks as $key => &$item){

			$state = isset($states[ $key ])? $states[ $key ] : Tasks::STATE_DEFAULT;
			$type  = $item['type'];

			if(Tasks::TYPE_AUTO === $type){

				$stateCallBack = isset($item['state_callback'])? $item['state_callback'] : null;

				if(is_callable($stateCallBack)){
					$auto = call_user_func($stateCallBack, $this->api);
				}else{
					$auto = $this->checkAuto($key);
				}

				if(Tasks::STATE_CHECKED === $auto){
					$state = $auto;
				}
			}


			$item['state'] = $state;
			$item['type']  = $type;
		}


		return $tasks;
	}

	/**
	 * @param $tasks
	 *
	 * @return array
	 */
	private function checkDependencies($tasks){
		foreach($tasks as $key => &$item){
			$dependencies = isset($item['depends'])? $item['depends'] : null;

			if(is_array($dependencies)){
				$resolved = true;
				foreach($dependencies as $dependency){
					if(isset($tasks[ $dependency ])){
						$depResolved = $tasks[ $dependency ]['state'] === Tasks::STATE_CHECKED;
						$resolved    = $resolved && $depResolved;
						if(!$depResolved){
							$item['unresolved'][ $dependency ] = $tasks[ $dependency ]['title'];
						}
					}
				}

				if(!$resolved){
					$item['locked'] = true;
				}
			}
		}

		return $tasks;
	}

	/**
	 * @param array $tasks
	 *
	 * @return array
	 */
	private function setActions($tasks){
		foreach($tasks as $key => &$item){

			$action = $this->getActions($key);

			if(!is_null($action)){
				$item['actions'][] = $action;
			}

		}

		return $tasks;
	}

	/**
	 * Sort tasks by user positions
	 *
	 * @param $tasks
	 *
	 * @return array
	 */
	private function sortTasks($tasks){
		$ordered   = [];
		$positions = $this->getPositions();

		foreach($positions as $item){
			if(isset($tasks[ $item ])){
				$ordered[ $item ] = $tasks[ $item ];
				unset($tasks[ $item ]);
			}
		}

		return array_merge($tasks, $ordered);
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 */
	public function getActions($key){
		if(strpos($key, Tasks::MAGIC_INSTALL_PLUGIN) === 0){
			$plugin    = str_replace(Tasks::MAGIC_INSTALL_PLUGIN, '', $key);
			$installed = $this->api->isInstalledPlugin($plugin);
			if(!$installed){
				return [
					'label' => __('Install', 'premmerce'),
					'data'  => [
						'handler' => 'premmerce.plugins',
						'option'  => 'install',
						'action'  => 'premmerce_actions',
						'value'   => $plugin,
					],
				];
			}elseif($installed && !$this->api->isActivePlugin($plugin)){
				return [
					'label' => __('Activate', 'premmerce'),
					'data'  => [
						'handler' => 'premmerce.plugins',
						'option'  => 'activate',
						'action'  => 'premmerce_actions',
						'value'   => $plugin,
					],
				];
			}
		};
		if(strpos($key, Tasks::MAGIC_INSTALL_THEME) === 0){
			$theme       = str_replace(Tasks::MAGIC_INSTALL_THEME, '', $key);
			$isInstalled = $this->api->isInstalledTheme($theme);
			if(!$isInstalled){
				return [
					'label' => __('Install', 'premmerce'),
					'data'  => [
						'handler' => 'wp.updates',
						'action'  => 'install-theme',
						'slug'    => $theme,
					],
				];
			}elseif($isInstalled && !$this->api->isActiveTheme($theme)){
				return [
					'label' => __('Activate', 'premmerce'),
					'data'  => [
						'handler' => 'premmerce.plugins',
						'option'  => "activate_theme",
						'action'  => 'premmerce_actions',
						'value'   => $theme,
					],
				];
			}
		}
	}

	/**
	 * @param string $key
	 *
	 * @return null|string
	 */
	public function checkAuto($key){

		if(strpos($key, Tasks::MAGIC_INSTALL_PLUGIN) === 0){
			$plugin = str_replace(Tasks::MAGIC_INSTALL_PLUGIN, '', $key);

			return $this->api->isInstalledPlugin($plugin) && $this->api->isActivePlugin($plugin)? Tasks::STATE_CHECKED : Tasks::STATE_DEFAULT;
		}

		if(strpos($key, Tasks::MAGIC_INSTALL_THEME) === 0){
			$theme = str_replace(Tasks::MAGIC_INSTALL_THEME, '', $key);

			return $this->api->isInstalledTheme($theme) && $this->api->isActiveTheme($theme)? Tasks::STATE_CHECKED : Tasks::STATE_DEFAULT;
		}

		return null;
	}

}