<?php namespace Premmerce\ProductComparison\RestApi;

use Premmerce\ProductComparison\Models\ProductComparisonModel;
use Premmerce\ProductComparison\ProductComparisonStorage;
use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class ComparisonRestApi
 * @package Premmerce\ProductComparison\RestApi
 */
class ComparisonRestApi{
	const APINAMESPACE = 'premmerce/comparison';

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var ProductComparisonModel
	 */
	private $model;

	/**
	 * @var ProductComparisonStorage
	 */
	private $storage;

	/**
	 * API constructor.
	 *
	 * @param FileManager $fileManager
	 * @param ProductComparisonModel $model
	 * @param ProductComparisonStorage $storage
	 */
	public function __construct(FileManager $fileManager, ProductComparisonModel $model, ProductComparisonStorage $storage){
		$this->fileManager = $fileManager;
		$this->model       = $model;
		$this->storage     = $storage;

		add_action('wc_ajax_premmerce_comparison_add', [$this, 'comparisonAddAjax']);
		add_action('rest_api_init', [$this, 'registerRestRoutes']);
	}

	/**
	 *  Register REST API routes
	 */
	public function registerRestRoutes(){

		register_rest_route(self::APINAMESPACE, '/add', [
			'methods'  => \WP_REST_Server::CREATABLE,
			'callback' => [$this, 'comparisonAdd'],
		]);

		register_rest_route(self::APINAMESPACE, '/delete/(?P<productId>\d+)', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [$this, 'comparisonDelete'],
		]);
	}

	/**
	 * Route add function
	 *
	 * Backward compatibility for saleszone template
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function comparisonAdd(\WP_REST_Request $request){
		$this->comparisonAddHandler($request->get_params());
	}


	/**
	 * Route add function
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function comparisonAddAjax(){
		$this->comparisonAddHandler($_REQUEST);
	}

	protected function comparisonAddHandler($data){
		$result = $this->addProductsToComparison([$this->productIdToComparison($data)]);

		if($this->isAjaxRequest()){
			return rest_ensure_response(['success' => $result? $result : false]);
		}else{
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	/**
	 * Route delete function
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function comparisonDelete(\WP_REST_Request $request){
		$result = null;
		$data   = $request->get_params();


		$oldUrl = $_SERVER['HTTP_REFERER'];

		$productsIds = isset($_GET['products'])? explode(',', $_GET['products']) : [];

		$key = array_search((int)$data['productId'], $productsIds);
		unset($productsIds[ $key ]);

		parse_str(parse_url($oldUrl, PHP_URL_QUERY), $urlData);

		if($urlData){
			$urlData['products'] = implode(',', $productsIds);
			$newUrl              = strstr($oldUrl, '?', true) . '?' . http_build_query($urlData);
		}else{
			$newUrl = $oldUrl;
		}

		$this->deleteProductFromComparison($data['productId'], $productsIds);

		if($this->isAjaxRequest()){
			return rest_ensure_response(['success' => $result? $result : false]);
		}else{
			wp_redirect($newUrl);
			exit;
		}
	}

	/**
	 * Add product to comparison or create new comparison and add product
	 *
	 * @param array $productsIds
	 *
	 * @return bool
	 */
	public function addProductsToComparison($productsIds){
		if(is_user_logged_in()){
			$userId     = get_current_user_id();
			$comparison = $this->model->getComparisonByUserId($userId);

			if($comparison){
				foreach($productsIds as $productId){
					if(!in_array($productId, $comparison['products'])){
						$comparison['products'][] = $productId;
					}
				}

				$this->model->updateComparisonProducts($comparison['ID'], $comparison['products']);
			}else{
				$this->model->createComparison($userId, $productsIds);
			}
		}else{
			$products = [];

			if($this->storage->cookieIsSet()){
				$products = $this->storage->cookieGet();
			}

			foreach($productsIds as $productId){
				if(!in_array($productId, $products)){
					$products[] = $productId;
				}
			}

			$this->storage->cookieSet($products);
		}
	}

	/**
	 * Delete product from comparison
	 *
	 * @param int $productId
	 * @param array $productsIds
	 */
	private function deleteProductFromComparison($productId, $productsIds){
		if(is_user_logged_in()){
			$userId     = get_current_user_id();
			$comparison = $this->model->getComparisonByUserId($userId);

			if($comparison){
				$key = array_search($productId, $comparison['products']);
				unset($comparison['products'][ $key ]);

				foreach($productsIds as $id){
					if(!in_array($id, $comparison['products'])){
						$comparison['products'][] = $id;
					}
				}

				$this->model->updateComparisonProducts($comparison['ID'], $comparison['products']);
			}else{
				if($productsIds){
					$this->model->createComparison($userId, $productsIds);
				}
			}
		}else{
			$products = [];

			if($this->storage->cookieIsSet()){
				$products = $this->storage->cookieGet();
			}

			$key = array_search($productId, $products);
			unset($products[ $key ]);

			foreach($productsIds as $id){
				if(!in_array($id, $products)){
					$products[] = $id;
				}
			}

			$this->storage->cookieSet($products);
		}
	}

	/**
	 * Checks the type of request
	 *
	 * @return bool
	 */
	private function isAjaxRequest(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true;
		}

		return false;
	}

	/**
	 * Get product or variable id
	 *
	 * @param $data
	 *
	 * @return int
	 */
	private function productIdToComparison($data){
		$defaults = [
			'comparison_product_id'   => '',
			'comparison_variation_id' => '',
		];
		$defaults = array_replace($defaults, $data);

		return (int)$defaults['comparison_variation_id']? $defaults['comparison_variation_id'] : $defaults['comparison_product_id'];
	}
}
