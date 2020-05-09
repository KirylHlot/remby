<?php namespace Premmerce\Toolkit\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;


/**
 * Class Admin
 * @package Premmerce\Toolkit\Admin
 */
class Admin{

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * Admin constructor.
	 *
	 * @param FileManager $fileManager
	 */
	public function __construct(FileManager $fileManager){
		$this->fileManager = $fileManager;

		$this->registerHooks();

	}

	/**
	 * Register admin hooks
	 */
	private function registerHooks(){
		if(get_option('premmerce_toolkit_product_video') == 'on'){
			add_action('add_meta_boxes', [$this, 'addVideoBox']);
			add_action('save_post', [$this, 'savePost']);
		}

		add_action('admin_init', function(){
			(new SettingsPage())->initSettings();
		}, 11);

	}

	/**
	 * Add video box in product backend
	 *
	 * @param string $postType
	 */
	public function addVideoBox($postType){
		if($postType == 'product'){
			add_meta_box(
				'product_video_box',
				__('Product video', 'premmerce-toolkit'),
				[$this, 'renderVideoBox'],
				$postType,
				'side',
				'low'
			);
		}
	}

	/**
	 * Save product video in backend
	 *
	 * @param string $postId
	 *
	 * @return mixed
	 */
	public function savePost($postId){
		if(!isset($_POST['product_video_box_nonce'])){
			return $postId;
		}

		$nonce = $_POST['product_video_box_nonce'];

		if(!wp_verify_nonce($nonce, 'product_video_box') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)){
			return $postId;
		}

		if('product' != $_POST['post_type']){
			return $postId;
		}

		if($_POST['product_video']){
			$videos = array_filter($_POST['product_video'], function($element){
				$parseUrl = parse_url($element);

				if($parseUrl['host'] != 'www.youtube.com' && $parseUrl['host'] != 'youtu.be' && $parseUrl['host'] != 'vimeo.com'){
					return false;
				}

				return !empty($element);
			});

			update_post_meta($postId, '_product_video_url', implode(',', $videos));
		}else{
			update_post_meta($postId, '_product_video_url', '');
		}
	}

	/**
	 * Add meta video box in backend product
	 */
	public function renderVideoBox(){
		wp_enqueue_style('premmerce-woocommerce-toolkit-admin', $this->fileManager->locateAsset('admin/css/premmerce-toolkit.css'));
		wp_enqueue_script('premmerce-woocommerce-toolkit-admin', $this->fileManager->locateAsset('admin/js/premmerce-toolkit.js'));
		wp_nonce_field('product_video_box', 'product_video_box_nonce');

		$urls = explode(',', get_post_meta(get_the_ID(), '_product_video_url', true));

		if(count($urls) == 1 && $urls[0] == ''){
			$urls = [];
		}

		$this->fileManager->includeTemplate('admin/product-video-box.php', [
			'urls' => $urls,
		]);
	}
}
