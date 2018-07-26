<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/19/2018
 * Time: 11:01 AM
 */

class OrderBagSession
{
    public static function getCurrentOrder () {
        if (!isset($_SESSION['current_order'])) {
            $currentOrder = new OrderHeader([]);
            $_SESSION['current_order'] = $currentOrder;
        } else
            $currentOrder = $_SESSION['current_order'] ;

        return $currentOrder;
    }

    public static function clearCurrentOrder() {
        unset($_SESSION['current_order']);
    }

    public static function reOrder($oldOrder) {
        $curOrder = self::getCurrentOrder();

        $orderCopy = clone $oldOrder;

        foreach ($orderCopy->getItems() as $item) {
            $curOrder->addItem($item->getData());
        }
   }


}