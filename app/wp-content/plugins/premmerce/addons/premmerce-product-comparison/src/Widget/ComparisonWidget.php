<?php namespace Premmerce\ProductComparison\Widget;

use Premmerce\ProductComparison\ProductComparisonPlugin;
use Premmerce\ProductComparison\Models\ProductComparisonModel;
use Premmerce\ProductComparison\ProductComparisonStorage;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Wishlist\Models\WishlistModel;
use Premmerce\Wishlist\WishlistStorage;

class ComparisonWidget extends \WP_Widget{
	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var WishlistModel
	 */
	private $model;

	/**
	 * @var WishlistStorage
	 */
	private $storage;

	/**
	 * ComparisonWidget constructor.
	 *
	 * @param FileManager $fileManager
	 * @param ProductComparisonModel $model
	 * @param ProductComparisonStorage $storage
	 */
	public function __construct(FileManager $fileManager, ProductComparisonModel $model, ProductComparisonStorage $storage){
		$this->fileManager = $fileManager;
		$this->model       = $model;
		$this->storage     = $storage;

		parent::__construct(
			'premmerce_comparison_widget',
			__('Premmerce Comparison', ProductComparisonPlugin::DOMAIN),
			['description' => __('List of Comparison', ProductComparisonPlugin::DOMAIN)]
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance){
		$this->fileManager->includeTemplate('widget/comparison-widget.php', [
			'args'  => $args,
			'title' => isset($instance['title'])? $instance['title'] : '',
		]);
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form($instance){
		$this->fileManager->includeTemplate('widget/comparison-widget-form.php', [
			'title'  => isset($instance['title'])? $instance['title'] : '',
			'widget' => $this,
		]);
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $newInstance
	 * @param array $oldInstance
	 *
	 * @return array
	 */
	public function update($newInstance, $oldInstance){
		$instance['title'] = (!empty($newInstance['title']))? strip_tags($newInstance['title']) : '';

		return $instance;
	}
}
