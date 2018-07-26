<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/18/2018
 * Time: 9:57 AM
 */

class PDFGenerator
{
    public function generate($html,$fileName=null) {

        if (empty($fileName))
            $fileName = PUBLIC_TEMP_PATH . '/' . uniqid() . '.pdf';

        $ch = curl_init();

        $params = ['sandbox' => Configuration::instance()->getPDFShiftSandboxFlag(),
                   'source' => $html,
                   'margin' => ['top' => '60px','bottom' => '30px','left' => '30px','right' => '30px']];

        curl_setopt($ch, CURLOPT_URL,PDFSHIFT_API_HOST);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, PDFSHIFT_API_KEY);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        file_put_contents($fileName, $server_output);

        return $fileName;

    }
}