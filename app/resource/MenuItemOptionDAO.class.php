<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuItemOption.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuItemOptionDAO extends BaseDAO
{
   private function getMenuItemOptionsFromSQLRow($rows)
    {
        $idx = 0;

        $menuItemOptions = [];
        while ($idx <= count($rows) - 1) {
            $menuOptionData = [];
            $itemId = $rows[$idx]['item_id'];
            $menuOptionData['menuItemId'] = $itemId;
            $optionsData = [];
            while (($idx <= count($rows) - 1) && $itemId === $rows[$idx]['item_id']) {
                $optionId = $rows[$idx]['menu_option_id'];
                if (isset($optionId)) {
                    $optionData['optionId'] = $optionId;
                    $optionChoicesData = [];
                    while (($idx <= count($rows) - 1) && ($itemId === $rows[$idx]['item_id'])
                                                      && ($optionId === $rows[$idx]['menu_option_id'])) {
                        if (isset($rows[$idx]['menu_option_choice_id']))
                            $optionChoicesData[] = $rows[$idx]['menu_option_choice_id'];
                        $idx++;
                    }
                    $optionData['optionChoices'] = $optionChoicesData;
                    $optionsData[] = $optionData;
                } else
                    $idx++;
            }
            $menuOptionData['options'] = $optionsData;
            $menuItemOptions[] = new MenuItemOption($menuOptionData);
        }
        return $menuItemOptions;
    }

    public function getMenuItemOptionsByMenuTypeId($dbConnection, $menuTypeId)
    {
        $selectMenuOptionSQL =
            'SELECT 
                        i.item_id,ipo.menu_option_id,ipoc.menu_option_choice_id 
                     FROM 
                        menu_item i LEFT OUTER JOIN menu_item_profile ip 
                          ON (i.menu_item_profile_id = ip.item_profile_id) 
                     LEFT OUTER JOIN menu_item_profile_option ipo 
                        ON (ip.item_profile_id = ipo.menu_item_profile_id) 
                     LEFT OUTER JOIN menu_item_profile_option_choice ipoc 
                        ON (ipo.item_profile_option_id = ipoc.menu_item_profile_option_id)
                     WHERE 
                          i.menu_type_id = :menuTypeId AND  ipo.item_profile_option_id IS NOT NULL
                     ORDER BY 
                          i.item_id,ipo.menu_option_id';

        $statement = $dbConnection->prepare($selectMenuOptionSQL);
        $statement->execute(array(':menuTypeId' => $menuTypeId));
        $rows = $statement->fetchAll();


        $menuItemOptions = $this->getMenuItemOptionsFromSQLRow($rows);
        return $menuItemOptions;
    }
}

/*

$x = new MenuItemOptionDAO();
echo '<br>';

echo 'JSON representation of list of all menuItemOptions for MenuType=1<br>';

$menuItemOptions  = $x->getMenuItemOptionsByMenuTypeId($db,1);

echo json_encode($menuItemOptions);
*/