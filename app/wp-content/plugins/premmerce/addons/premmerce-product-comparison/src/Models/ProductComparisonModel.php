<?php namespace Premmerce\ProductComparison\Models;

class ProductComparisonModel
{

    /**
     * Db table name without prefix
     */
    const TBL_NAME_COMPARISON = 'premmerce_comparison';

    /**
     * Table name with prefix
     *
     * @var string
     */
    private $tblComparison = '';

    /**
     * Charset for DB table
     *
     * @var string
     */
    private $charset = '';

    /**
     * Collate for DB table
     *
     * @var string
     */
    private $collate = '';


    /**
     * @var \wpdb
     */
    private $wpdb;

    /**
     * ProductComparisonModel constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->charset = $wpdb->charset;
        $this->collate = $wpdb->collate;

        $this->tblComparison = $wpdb->prefix . self::TBL_NAME_COMPARISON;
    }

    /**
     * Create plugin tables
     */
    public function createTables()
    {
        $sql = vsprintf(
            'CREATE TABLE IF NOT EXISTS %s (
              `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) unsigned DEFAULT NULL,
              `products` text,
              `date_created` datetime,
              `date_modified` datetime,
              PRIMARY KEY  (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARACTER SET %s COLLATE %s;',
            [
                $this->tblComparison,
                $this->charset,
                $this->collate
            ]
        );

        $this->runQuery($sql);
    }

    /**
     * Delete plugin tables.
     */
    public function deleteTables()
    {
        $sql = 'DROP TABLE IF EXISTS ' . $this->tblComparison;
        $this->runQuery($sql);
    }

    /**
     * Get comparison by user id
     *
     * @param $userId
     *
     * @return array|mixed
     */
    public function getComparisonByUserId($userId)
    {
        $data = [];

        if (!empty($userId)) {
            $sql = $this->prepare("SELECT * FROM `" . $this->tblComparison . "` WHERE `user_id` = %d;", [$userId]);

            $data = $this->getRow($sql);

            if ($data) {
                $data['products'] = $data['products'] ? json_decode($data['products']) : [];
            }
        }

        return $data;
    }

    /**
     * Create new comparison
     *
     * @param int $userId
     * @param array $products
     *
     * @return bool
     */
    public function createComparison($userId, $products)
    {
        $date = date('Y-m-d H:i:s');

        $sql = $this->prepare(
            "INSERT INTO `" . $this->tblComparison . "` (`user_id`,`products`,`date_created`,`date_modified`) 
                VALUES (%d,%s,%s,%s);",
            [
                $userId,
                json_encode($products),
                $date,
                $date,
            ]
        );

        return $this->runQuery($sql);
    }

    /**
     * Update comparison products
     *
     * @param int $id
     * @param array $products
     *
     * @return bool
     */
    public function updateComparisonProducts($id, $products)
    {
        if ($id) {
            $sql = $this->prepare(
                "UPDATE `" . $this->tblComparison . "` SET `products` = %s, `date_modified` = %s WHERE `ID` = %d;",
                [
                    json_encode(array_values($products)),
                    date('Y-m-d H:i:s'),
                    $id,
                ]
            );

            return $this->runQuery($sql);
        }

        return false;
    }

    /**
     * Get row data from table
     *
     * @param $sql
     *
     * @return array|null|object|void
     */
    private function getRow($sql)
    {
        return $this->wpdb->get_row($sql, ARRAY_A);
    }

    /**
     * Execute query
     *
     * @param string $sql
     *
     * @return bool
     */
    private function runQuery($sql)
    {
        if ($this->wpdb->query($sql) !== false) {
            return true;
        }
    }

    /**
     * Prepare query to execute
     *
     * @param string $sql
     * @param array $args
     *
     * @return string
     */
    private function prepare($sql, $args)
    {
        return $this->wpdb->prepare($sql, $args);
    }
}
