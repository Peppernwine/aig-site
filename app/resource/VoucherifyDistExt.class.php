<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/12/2019
 * Time: 8:54 PM
 */

use Voucherify\Distributions;

class VoucherifyDistExt extends Distributions
{
    /**
     * @var \Voucherify\ApiClient
     */
    private $client;

    /**
     * @param \Voucherify\ApiClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Create publications.
     *
     * @param array|stdClass $params
     *
     * @throws \Voucherify\ClientException
     */
    public function createPublications($params)
    {
        return $this->client->post("/publications", $params);
    }
}