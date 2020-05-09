<?php namespace Premmerce\ProductComparison;

use Premmerce\ProductComparison\Models\ProductComparisonModel;

class ProductComparisonStorage
{
    const COOKIE_NAME = 'premmerce_comparison';

    const DAYS_IN_SECONDS = 60 * 60 * 24;

    /**
     * @var ProductComparisonModel
     */
    private $model;

    /**
     * ProductComparisonStorage constructor.
     *
     * @param ProductComparisonModel $model
     */
    public function __construct(ProductComparisonModel $model)
    {
        $this->model = $model;
    }

    /**
     * Check is set cookie with comparison
     *
     * @return bool
     */
    public function cookieIsSet()
    {
        return isset($_COOKIE[self::COOKIE_NAME]);
    }

    /**
     * Set comparison products to cookie
     *
     * @param array $values
     */
    public function cookieSet($values)
    {
        setcookie(self::COOKIE_NAME, json_encode(array_values($values)), time() + self::DAYS_IN_SECONDS * 31 * 6, COOKIEPATH, COOKIE_DOMAIN);
    }

    /**
     * Get comparison key from cookie
     *
     * @return string
     */
    public function cookieGet()
    {
        if ($this->cookieIsSet()) {
            $data = stripslashes($_COOKIE[self::COOKIE_NAME]);
            return $data ? json_decode($data) : [];
        }

        return '';
    }
}
