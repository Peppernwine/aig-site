<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 12:58 AM
 */

class Configuration {
    // Hold an instance of the class
    private static $_instance;

    private static function createConfiguration() {

        $conf = new Configuration();
        return $conf;
    }

    // The singleton method
    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = self::createConfiguration();
        }
        return self::$_instance;
    }

    public function currentUrl(){
        //Figure out whether we are using http or https.
        $http = 'http';
        //If HTTPS is present in our $_SERVER array, the URL should
        //start with https:// instead of http://
        if(isset($_SERVER['HTTPS'])){
            $http = 'https';
        }
        //Get the HTTP_HOST.
        $host = $_SERVER['HTTP_HOST'];
        //Get the REQUEST_URI. i.e. The Uniform Resource Identifier.
        $requestUri = $_SERVER['REQUEST_URI'];
        //Finally, construct the full URL.
        //Use the function htmlentities to prevent XSS attacks.
        return $http . '://' . htmlentities($host) . htmlentities($requestUri);
    }


    public function getServer() {
        return SERVER_URL;
    }

    public function getServerURL($page) {
        return $this->getServer() .'/'. $page;
    }

    public function getInternalImageFullURL($image) {
        return $this->getServerURL($image);
    }

    public function getInternalImageURL($image) {
        return $image;
    }

    public function getInternalCSSTag($css) {
        return "<link type='text/css' rel='stylesheet' href='$css'>";
    }

    public function getInternalVendorCSSTag($css) {
        return "<link type='text/css' rel='stylesheet' href='$css'>";
    }

    public function getInternalJSTag($js) {
        return "<script src='$js'></script>";
    }

    public function getInternalVendorJSTag($js) {
        return "<script src='$js'></script>";
    }

    public function getPDFShiftSandboxFlag() {
        return PDFSHIFT_SANDBOX;
    }

}




