<?php

if(!defined('WPINC')){
	die;
}

/**
 * @var $wizard \Premmerce\Premmerce\Api\Wizard
 */
$states         = [
	__('Incomplete', 'premmerce') => 'todo',
	__('Completed', 'premmerce')  => 'checked',
	__('Skipped', 'premmerce')    => 'skipped',
	__('All', 'premmerce')        => 'all',
];
$containerState = $wizard->getContainerState();
require_once __DIR__ . "/../functions.php";
?>
<div class="premmerce_wizard">
    <div class="state-switcher">
		<?php foreach($states as $label => $state): ?>
            <label class="state-switcher__label">
                <input type="radio" name="switche-state" data-set-container-state
                       value="<?=$state?>" <?=$state == $containerState? 'checked' : ''?>>
				<?=$label?>
            </label>
		<?php endforeach; ?>
    </div>

    <div class="task-container" data-container data-container-state="<?=$wizard->getContainerState()?>">
        <div class="spinner"></div>
		<?php foreach($wizard->getTasks() as $key => $task): ?>
			<?php
			$checked = $task['state'] == 'checked';
			$skipped = $task['state'] == 'skipped';
			?>
            <div id="<?=$key?>"
                 class="task-container__task <?=isset($task['locked'])? 'task-container__task--locked' : ''?> task-container__task--state-<?=$task['state']?> <?='task-container__task--type-' . $task['type']?>"
                 data-task="<?=$key?>"
                 data-task-state="<?=$task['state']?>"
            >
                <div class="task-container__task-content-row">
                    <!-- Check -->
                    <div class="task-container__task-aside">
                        <div class="task-container__task-action task-container__task-action--checked">
                            <label class="task-container__task-action-label task-container__task-action-label-check">
                                <input data-set-state class="task-container__task-action-checkbox" type="checkbox"
                                       data-check-task-checkbox
									<?php if($task['type'] == 'auto' || $task['type'] == 'premium' || ((isset($task['locked'])) && !$checked)) : ?>
                                        disabled
									<?php endif; ?>
									<?=$checked? 'checked' : ''?> value="checked" data-reset-value="unChecked">
                                <span class="task-container__task-action-checkbox-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 91 63"
                                         xml:space="preserve"><g id="Shape_2"><g><polygon class="st0"
                                                                                          points="91,5.5 85.5,0 33.8,52.1 5.5,23.5 0,29 33.7,63 33.8,62.8 34,63"/></g></g></svg>
                                </span>
                            </label>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="task-container__task-body">
                        <!-- Title -->
                        <div class="task-container__task-title">
							<?=$task['title']?>
                        </div>
                        <!-- description -->
                        <div class="task-container__task-description">
							<?=$task['description']?>
                        </div>
                        <!-- Links -->
                        <div class="task-container__task-footer">

                            <!-- unresolved -->
							<?php if(isset($task['unresolved'])): ?>
                                <div class="task-container__task-footer-row">
									<?php foreach($task['unresolved'] as $key => $item): ?>
                                        <a class="btn-notice btn-notice--unresolved" href="#<?=$key?>">
                                            <div class="btn-notice__icon">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 15 15" style="enable-background:new 0 0 15 15;"
                                                     xml:space="preserve"><g id="Ellipse_3_1_" class="st0">
                                                        <g id="Ellipse_3">
                                                            <g>
                                                                <path class="st1"
                                                                      d="M7.5,1C11.1,1,14,3.9,14,7.5S11.1,14,7.5,14S1,11.1,1,7.5S3.9,1,7.5,1 M7.5,0C3.4,0,0,3.4,0,7.5S3.4,15,7.5,15S15,11.6,15,7.5S11.6,0,7.5,0L7.5,0z"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <path d="M8,4H7v5h1V4L8,4z M8,10H7v1h1V10L8,10z"/>
                                                            </g>
                                                        </g>
                                                    </g></svg>
                                            </div>
                                            <div class="btn-notice__text-el">
												<?=$item?>
                                            </div>
                                        </a>
									<?php endforeach; ?>
                                </div>
							<?php endif; ?>

                            <!-- premium -->
							<?php if($task['type'] == 'premium'): ?>
                                <div class="task-container__task-footer-row">
                                    <div class="btn-notice btn-notice--premium">
                                        <div class="btn-notice__icon">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                 viewBox="0 0 15 15" style="enable-background:new 0 0 15 15;"
                                                 xml:space="preserve"><g id="Ellipse_3_1_" class="st0">
                                                    <g id="Ellipse_3">
                                                        <g>
                                                            <path class="st1"
                                                                  d="M7.5,1C11.1,1,14,3.9,14,7.5S11.1,14,7.5,14S1,11.1,1,7.5S3.9,1,7.5,1 M7.5,0C3.4,0,0,3.4,0,7.5S3.4,15,7.5,15S15,11.6,15,7.5S11.6,0,7.5,0L7.5,0z"/>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path d="M8,4H7v5h1V4L8,4z M8,10H7v1h1V10L8,10z"/>
                                                        </g>
                                                    </g>
                                                </g></svg>
                                        </div>
                                        <div class="btn-notice__text-el">
											<?php _e('Premium only', 'premmerce') ?>
                                        </div>
                                    </div>
                                </div>
							<?php endif; ?>

                            <!-- actions and links -->
							<?php if(isset($task['actions'])): ?>
                                <div class="task-container__task-footer-row task-container__task-footer-row--action">
									<?php foreach($task['actions'] as $action): ?>
                                        <button class="install-now button task-container__task-action--locked-hidden task-container__task-action--state-skipped-hidden"
                                                data-action="<?=arrayToDataAttr($action['data'])?>"
                                        >
                                                <span class="button__label">
                                                    <?=$action['label']?>
                                                </span>
                                        </button>
									<?php endforeach; ?>
                                </div>
							<?php endif; ?>
							<?php if(isset($task['links'])): ?>
                                <div class="task-container__task-footer-row task-container__task-footer-row--action">
									<?php foreach($task['links'] as $link): ?>
                                        <a class="task-container__task-link task-container__task-action--locked-hidden task-container__task-action--state-skipped-hidden"
                                           target="_blank"
                                           href="<?=$link['link']?>"><?=$link['label']?></a>
									<?php endforeach; ?>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                    <!-- Skip -->
                    <div class="task-container__task-aside">
                        <div class="task-container__task-action task-container__task-action--skipped">
                            <label class="task-container__task-action-label">
                                <input data-set-state class="task-container__task-action-checkbox" type="checkbox"
									<?=$skipped? 'checked' : ''?> value="skipped">
                                <div class="task-container__task-check-view">
                                    <span class="task-container__task-check-view--check">
                                        <span class="btn-skip">
                                            <?php _e('Skip', 'premmerce') ?>
                                        </span>
                                    </span>
                                    <span class="task-container__task-check-view--uncheck">
                                        <span class="btn-unskip">
                                            <?php _e('Unskip', 'premmerce') ?>
                                        </span>
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>

		<?php if($wizard->getContainerState() != 'all') : ?>
			<?php
			$containerState = $wizard->getContainerState();
			$stateTask      = array_filter($wizard->getTasks(), function($task) use ($containerState){
				return $task['state'] == $containerState;
			});
			?>
            <div class="task-container__show-all <?=count($stateTask) == 0? '' : 'hidden'?>"
                 data-show-all-task--container>
                <div class="task-container__description">
					<?php _e('There are no tasks', 'premmerce') ?>
                </div>
                <button class="button button-primary button-large task-container__show-all-button" data-show-all-task>
					<?php _e('Show all', 'premmerce') ?>
                </button>
            </div>
		<?php endif; ?>
    </div>
</div>