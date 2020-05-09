<?php namespace Premmerce\ProductComparison\Frontend;

use Premmerce\ProductComparison\ProductComparisonStorage;
use Premmerce\ProductComparison\Models\ProductComparisonModel;
use Premmerce\ProductComparison\ProductComparisonPlugin;

class ComparisonFunctions
{
    /**
     * @var ProductComparisonModel
     */
    private $model;

    /**
     * @var ProductComparisonStorage
     */
    private $storage;

    /**
     * @var ComparisonFunctions
     */
    private static $_instance = null;

    /**
     * ComparisonFunctions constructor.
     */
    private function __construct()
    {
        $this->model = new ProductComparisonModel();
        $this->storage = new ProductComparisonStorage($this->model);
    }

    /**
     * Static class initialization
     *
     * @return ComparisonFunctions
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Check product in comparison
     *
     * @param int $productId
     *
     * @return bool
     */
    public function checkInComparison($productId)
    {
        $products = [];

        if (is_user_logged_in()) {
            $comparison = $this->model->getComparisonByUserId(get_current_user_id());

            if ($comparison) {
                $products = $comparison['products'];
            }
        } elseif ($this->storage->cookieIsSet()) {
            $products = $this->storage->cookieGet();
        }

        if ($products && in_array($productId, $products)) {
            return true;
        }

        return false;
    }

    public function getUrlGroupByProductId($productId)
    {
        global $premmerce_comparison_frontend;

        if ($premmerce_comparison_frontend) {
            return $premmerce_comparison_frontend->getUrlCategoryByProductId($productId);
        }

        return '';
    }

    public function getUrlComparison()
    {
        return get_permalink(get_post(get_option(ProductComparisonPlugin::OPTION_PAGE)));
    }

    /**
     * Return product counts in comparison
     *
     * @return int
     */
    public function totalProducts()
    {
        $total = 0;

        if (is_user_logged_in()) {
            $comparison = $this->model->getComparisonByUserId(get_current_user_id());

            if ($comparison) {
                $total = count($comparison['products']);
            }
        } elseif ($this->storage->cookieIsSet()) {
            $total = count($this->storage->cookieGet());
        }

        return $total;
    }
}
