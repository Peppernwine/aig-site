<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/17/2018
 * Time: 11:11 PM
 */

class SendFax
{
    public function send($toNumber, $docPath) {
        $params = array(
            'to' => $toNumber,
            'file' => array(
                # Use open file handles to upload files
                fopen($docPath, 'r')),
            'batch_delay' => PHAXIO_API_BATCH_DELAY,
            'batch_collision_avoidance' => true
        );

        $phaxio = new Phaxio(PHAXIO_API_KEY_TEST, PHAXIO_API_SECRET_TEST,PHAXIO_API_HOST);
        $result = $phaxio->sendFax($params);

    }

    public function sendToRestaurant($docPath) {
        $this->send(FAX_TO,$docPath);
    }

}