<?php namespace Premmerce\ProductComparison\Frontend;

use Premmerce\ProductComparison\ProductComparisonPlugin;
use Premmerce\ProductComparison\Models\ProductComparisonModel;
use Premmerce\ProductComparison\ProductComparisonStorage;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\ProductComparison\Integration\OceanWpIntegration;

/**
 * Class Frontend
 *
 * @package Premmerce\ProductComparison\Frontend
 */
class Frontend
{

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
     * @var string
     */
    private $pageSlug;

    /**
     * Frontend constructor.
     *
     * @param FileManager $fileManager
     * @param ProductComparisonModel $model
     * @param ProductComparisonStorage $storage
     */
    public function __construct(
        FileManager $fileManager,
        ProductComparisonModel $model,
        ProductComparisonStorage $storage
    ) {
        $this->fileManager = $fileManager;
        $this->model       = $model;
        $this->storage     = $storage;

        add_action('woocommerce_single_product_summary', [$this, 'renderComparisonBtn'], 36);
        add_action('woocommerce_after_shop_loop_item', [$this, 'renderComparisonBtn'], 5);

        add_shortcode('comparisons_page', [$this, 'comparisonPage']);

        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);

        //Theme integration
        add_action('init', [$this, 'checkIntegration']);
    }


    public function enqueueScripts(){
        wp_enqueue_style('comparision-style',
            $this->fileManager->locateAsset('frontend/css/premmerce-product-comparison.css'));
        wp_enqueue_script('comparision-script',
            $this->fileManager->locateAsset('frontend/js/premmerce-product-comparison.js'));
    }
    /**
     *  Render comparison page with categories or products
     *
     * @return string
     */
    public function comparisonPage()
    {
        $products = [];

        $url = site_url('wp-json/premmerce/comparison/delete/');

        if (isset($_GET['category']) && ! empty($_GET['category']) &&
            isset($_GET['products']) && ! empty($_GET['products'])) {
            $productsIds = $_GET['products'] ? explode(',', $_GET['products']) : [];
            $data        = $this->prepareComparisonDataCategory((int)$_GET['category'], $productsIds);

            if ($data) {
                $products = $data['products'];
            }

            $tpl = 'comparison-page-products';
        } else {
            if (is_user_logged_in()) {
                $comparison = $this->model->getComparisonByUserId(get_current_user_id());

                if ($comparison) {
                    $products = $comparison['products'];
                }
            } elseif ($this->storage->cookieIsSet()) {
                $products = $this->storage->cookieGet();
            }

            $data = $this->prepareComparisonData($products);

            $tpl = 'comparison-page-categories';
        }

        return $this->fileManager->renderTemplate('frontend/' . $tpl . '.php', [
            'productsIds' => $products,
            'data'        => $data,
            'urlDelete'   => $url,
        ]);
    }

    /**
     * @return string
     */
    private function getPageSlug()
    {
        if (is_null($this->pageSlug)) {

            $this->pageSlug = 'comparison';
            if ($pageId = get_option(ProductComparisonPlugin::OPTION_PAGE)) {
                $post = get_post($pageId);

                if ($post) {
                    $this->pageSlug = $post->post_name;
                }
            }
        }

        return $this->pageSlug;
    }

    /**
     * Prepare comparison data for compare page
     *
     * @param array $productIds
     *
     * @return array
     */
    private function prepareComparisonData($productIds)
    {
        $data = [];

        sort($productIds);

        foreach ($productIds as $i => $productId) {
            if ($product = wc_get_product($productId)) {
                $category = $this->getProductCategory($productId);

                if ( ! in_array($category['term_id'], array_keys($data))) {
                    $data[$category['term_id']] = $this->buildNewCategory(
                        $category['slug'],
                        $category['name'],
                        $category['description']
                    );
                }

                $data[$category['term_id']]['products'][$productId] = $product;
            }
        }

        foreach ($data as $catId => $category) {
            $data[$catId]['url'] = $this->buildCategoryUrl($catId, $category['products']);

            foreach ($category['products'] as $prodId => $product) {
                $attributes = $product->get_attributes();

                foreach ($attributes as $slug => $attribute) {
                    if ($attribute->get_visible() && $attribute->get_id() !== 0) {
                        $keys = isset($data[$catId]['attributes']) ? $data[$catId]['attributes'] : [];

                        if ( ! in_array($slug, array_keys($keys))) {
                            $data[$catId]['attributes'][$slug] = $this->buildNewAttribute(
                                $slug,
                                $attribute,
                                array_keys($category['products'])
                            );
                        }

                        $data[$catId]['attributes'][$slug]['values'][$prodId] = $product->get_attribute($slug);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Prepare comaprison date for category page
     *
     * @param int $categoryId
     * @param array $productIds
     *
     * @return array
     */
    private function prepareComparisonDataCategory($categoryId, $productIds)
    {
        $data = [];

        sort($productIds);

        foreach ($productIds as $i => $productId) {
            if ($product = wc_get_product($productId)) {
                $category = $this->getProductCategory($productId);

                if ($categoryId == $category['term_id']) {
                    if ( ! $data) {
                        $data = $this->buildNewCategory(
                            $category['slug'],
                            $category['name'],
                            $category['description']
                        );
                    }

                    $data['products'][$productId] = $product;
                }
            }
        }

        if ( ! $data) {
            $category = get_term_by('id', $categoryId, 'product_cat', 'ARRAY_A');

            if ($category) {
                $data = $this->buildNewCategory(
                    $category['slug'],
                    $category['name'],
                    $category['description']
                );
            }
        }

        $data['url'] = $this->buildCategoryUrl($categoryId, $data['products']);

        foreach ($data['products'] as $prodId => $product) {
            $attributes = $product->get_attributes();

            foreach ($attributes as $slug => $attribute) {
                if ($attribute->get_visible() && $attribute->get_id() !== 0) {
                    $keys = isset($data['attributes']) ? $data['attributes'] : [];

                    if ( ! in_array($slug, array_keys($keys))) {
                        $data['attributes'][$slug] = $this->buildNewAttribute(
                            $slug,
                            $attribute,
                            array_keys($data['products'])
                        );
                    }

                    $data['attributes'][$slug]['values'][$prodId] = $product->get_attribute($slug);
                }
            }
        }

        return $data;
    }

    /**
     * Return url with compare category and products
     *
     * @param int $categoryId
     * @param array $productsIds
     *
     * @return string
     */
    private function buildCategoryUrl($categoryId, $productsIds)
    {
        return site_url(
            $this->getPageSlug() . '?' . http_build_query([
                'category' => $categoryId,
                'products' => implode(',', array_keys($productsIds)),
            ])
        );
    }

    /**
     * Return array with category data
     *
     * @param string $slug
     * @param string $name
     * @param string $description
     *
     * @return array
     */
    private function buildNewCategory($slug, $name, $description)
    {
        return [
            'slug'        => $slug,
            'name'        => $name,
            'url'         => '',
            'description' => $description,
            'products'    => [],
            'attributes'  => [],
        ];
    }

    /**
     * Return array with attribute date
     *
     * @param string $slug
     * @param array $attribute
     * @param array $productsIds
     *
     * @return array
     */
    private function buildNewAttribute($slug, $attribute, $productsIds)
    {
        $label             = $attribute->get_name();
        $attributeTaxonomy = get_taxonomy($slug);


        if ($attributeTaxonomy) {
            $label = $attributeTaxonomy->labels->singular_name;
        }

        return [
            'title'  => $label,
            'values' => array_fill_keys($productsIds, ''),
        ];
    }

    /**
     * Return product category from Yoast SEO or category (same as bread crumb)
     *
     * @param int $id
     *
     * @return array
     */
    private function getProductCategory($id)
    {
        $isWPSEO = false;
        if (class_exists('WPSEO_Primary_Term')) {
            $primaryTerm = new \WPSEO_Primary_Term('product_cat', $id);
            $primaryTerm = $primaryTerm->get_primary_term();
            $term        = get_term($primaryTerm);

            if ($term) {
                $isWPSEO = ! is_wp_error($term);
            }
        }

        if ($isWPSEO) {
            return [
                'slug'        => $term->slug,
                'term_id'     => $term->term_id,
                'name'        => $term->name,
                'description' => $term->description,
            ];
        } else {
            $data = wc_get_product_terms($id, 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);

            if ($data) {
                return [
                    'slug'        => $data[0]->slug,
                    'term_id'     => $data[0]->term_id,
                    'name'        => $data[0]->name,
                    'description' => $data[0]->description,
                ];
            }
        }

        return [
            'term_id'     => 0,
            'slug'        => 'uncategorized',
            'name'        => __('Uncategorized', ProductComparisonPlugin::DOMAIN),
            'description' => '',
        ];
    }

    /**
     * Render button "Add to comparison"
     */
    public function renderComparisonBtn()
    {
        global $product;

        $url = $this->getComparisonAddUrl();

        $this->fileManager->includeTemplate('frontend/comparison-btn.php', [
            'url'       => $url,
            'productId' => $product->get_ID(),
        ]);
    }


    /**
     * Return url add to comparison
     * @return mixed
     */
    public function getComparisonAddUrl()
    {
        return add_query_arg(['wc-ajax' => 'premmerce_comparison_add'], get_the_permalink());
    }

    /**
     * Return url for comparison group by product id
     *
     * @param string $productId
     *
     * @return string
     */
    public function getUrlCategoryByProductId($productId)
    {
        if ($productId) {
            $products = [];

            if (is_user_logged_in()) {
                $comparison = $this->model->getComparisonByUserId(get_current_user_id());

                if ($comparison) {
                    $products = $comparison['products'];
                }
            } elseif ($this->storage->cookieIsSet()) {
                $products = $this->storage->cookieGet();
            }

            $category = $this->getProductCategory($productId);
            $data     = $this->prepareComparisonData($products);
            if ($category && $data) {
                return $data[$category['term_id']]['url'];
            }
        }

        return '';
    }

    /**
     * Run theme Integration if exists
     */
    public function checkIntegration()
    {
        $theme = wp_get_theme();

        if ('oceanwp' == $theme->get_template()) {
            new OceanWpIntegration($this, $this->fileManager);
        }
    }
}
