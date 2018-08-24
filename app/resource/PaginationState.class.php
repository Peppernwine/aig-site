<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/20/2018
 * Time: 12:14 PM
 */

require_once RESOURCE_PATH .'/session.php';
require_once RESOURCE_PATH .'/user-session.php';

class PaginationState
{
    private $token;

    public function getToken() {
        return $this->token;
    }

    public static function withNewToken($context, $searchParams,$batchSize){
        $userId = getSignedInUserId();
        if (empty($userId))
            throw new Exception('Unable to generate Pagination Token, User is not signed in');
        $newToken = uniqid( 'pageinfo:' . $userId .':' .$context);

        return new PaginationState($newToken,$searchParams,$batchSize);
    }

    public static function withExitingToken($token){
        return new PaginationState($token,null,-1);
    }

    public function __construct($token,$searchParams,$batchSize){
        $this->token = $token;
        $pageInfo = null;

        if (!empty($_SESSION[$token]))
            $pageInfo = $_SESSION[$token];

        if (empty($pageInfo))
            $_SESSION[$token] = ['searchParams'=>$searchParams,'batchSize' => $batchSize,'lastPage'=> 0];
    }

    private function setPageInfo($property,$value){
        $pageInfo = $_SESSION[$this->token];
        $pageInfo[$property] = $value;
        $_SESSION[$this->token] = $pageInfo;
    }

    private function getPageInfo($property){
        $pageInfo = $_SESSION[$this->token];
        return $pageInfo[$property];
    }

    public function getSearchParams(){
        return $this->getPageInfo('searchParams');
    }

    public function setLastPage($lastPage){
        $this->setPageInfo('lastPage',$lastPage);
    }


    public function getLastPage(){
        return $this->getPageInfo('lastPage');
    }

    public function getBatchSize(){
        return $this->getPageInfo('batchSize');
    }
}