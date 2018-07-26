<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuOption.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuOptionDAO extends BaseDAO
{
    private $entityTableMap = ['MenuOption' => 'menu_option'];

    private $idMap = ['MenuOption' => 'menuOptionId'];

    private $calculatedFields = ['MenuOption' => []];

    private $menuOptionMap = [
        'optionId' => 'option_id',
    //    'menuTypeId' => 'menu_type_id',
        'optionCode' => 'option_code',
        'optionDescription' => 'option_description',
        'optionLabel' => 'option_label',
        'sortOrder' => 'sort_order',
        'isRequired' => 'is_required',
        'hasExtraCost' => 'has_extra_cost',
        'displayAllOptions' => 'display_all_options'
    ];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['MenuOption' => $this->menuOptionMap];
        parent::__construct();
    }

    protected function getEntityTableMap() {
        return $this->entityTableMap;
    }

    protected function getIdMap() {
        return $this->idMap;
    }

    protected function getCalculatedFields() {
        return $this->calculatedFields;
    }

    protected function getFieldMap() {
        return $this->fieldMap;
    }

    public function getMenuOptionChoiceInsertSQL() {
        $insertMenuOptionChoiceInsertSQL = "INSERT INTO menu_option_choice
                                              (menu_option_id,option_choice_value,option_choice_code,option_choice_description,price_delta)
                                            VALUES
                                              (:menuOptionId,:optionChoiceValue,:optionChoiceCode,:optionChoiceDescription,:priceDelta)";
        return $insertMenuOptionChoiceInsertSQL;
    }

    public function getMenuOptionChoiceInsertParameters($menuOptionId,$optionChoiceValue,$optionChoiceCode,$optionChoiceDescription,$priceDelta) {
        return array(
            ':menuOptionId' => $menuOptionId,
            ':optionChoiceValue' => $optionChoiceValue,
            ':optionChoiceCode' => $optionChoiceCode,
            ':optionChoiceDescription' => $optionChoiceDescription,
            ':priceDelta' => $priceDelta
        );
    }

    private function gatherMenuOptionFields($row,&$menuOption) {
        $this->gatherEntityFields($row,'MenuOption',$menuOption);
    }

    private function gatherMenuOptionChoiceFields($row,&$menuOptionChoice) {
        $menuOptionChoice = [
                            'optionChoiceId' => $row['option_choice_id'],
                            'optionChoiceValue' => $row['option_choice_value'],
                            'optionChoiceCode' => $row['option_choice_code'],
                            'optionChoiceDescription' => $row['option_choice_description'],
                            'priceDelta' => $row['price_delta']
                            ];
    }

    private function getMenuOptionsFromSQLRow($rows) {
        $idx = 0;

        $menuOptions = [];
        while($idx <= count($rows) - 1) {
            $menuOption = [];
            $menuOptionId = $rows[$idx]['menu_option_id'];
            $this->gatherMenuOptionFields($rows[$idx],$menuOption);
            $optionChoices = [];
            while (($idx <= count($rows) - 1) && $menuOptionId === $rows[$idx]['menu_option_id']) {
                $this->gatherMenuOptionChoiceFields($rows[$idx],$optionChoice);
                $optionChoices[] = $optionChoice;
                $idx++;
            }
            $menuOption['optionChoices'] = $optionChoices;
            $menuOptions[] = new MenuOption($menuOption);
        }
        return $menuOptions;
    }

    public function getMenuOptionById($dbConnection, $menuOptionId)
    {
        $selectMenuOptionSQL =
            'SELECT * FROM 
                menu_option mo LEFT OUTER JOIN menu_option_choice moc ON (mo.option_id = moc.menu_option_id)
             WHERE
                mo.option_id = :menuOptionId';

        $statement = $dbConnection->prepare($selectMenuOptionSQL);
        $statement->execute(array(':menuOptionId' => $menuOptionId));
        $rows = $statement->fetchAll();
        $menuOptions = $this->getMenuOptionsFromSQLRow($rows);
        return $menuOptions[0];
    }

    public function getAllMenuOptions($dbConnection) //getMenuOptionsByMenuType($dbConnection, $menuTypeId)
    {
        $selectMenuOptionSQL =
            'SELECT * FROM 
                menu_option mo LEFT OUTER JOIN menu_option_choice moc ON (mo.option_id = moc.menu_option_id)';

        $statement = $dbConnection->prepare($selectMenuOptionSQL);
        $statement->execute();
        $rows = $statement->fetchAll();
        $menuOptions = $this->getMenuOptionsFromSQLRow($rows);
        return $menuOptions;
    }

    
    public function createMenuOption($dbConnection, $menuOption)
    {
        $insertMenuOptionSQL = $this->generateInsertSQL('MenuOption');
        $insertMenuOptionChoiceSQL = $this->getMenuOptionChoiceInsertSQL();


        $menuOptionStatement = $dbConnection->prepare($insertMenuOptionSQL);
        $menuOptionChoiceStatement = $dbConnection->prepare($insertMenuOptionChoiceSQL);

        $menuOptionStatement->execute($this->getTableInsertParameters('MenuOption', $menuOption,[]));
        $menuOptionID = $dbConnection->lastInsertId();

        $menuOptionChoices = $menuOption->getOptionChoices();
        foreach ($menuOptionChoices as $menuOptionChoice) {
            $menuOptionChoiceStatement->execute($this->getMenuOptionChoiceInsertParameters($menuOptionID,
                                                           $menuOptionChoice['optionChoiceValue'],
                                                           $menuOptionChoice['optionChoiceCode'],
                                                           $menuOptionChoice['optionChoiceDescription'],
                                                           $menuOptionChoice['priceDelta']
                                                ));
        }
        return $menuOptionID;
    }
}
/*

$x = new MenuOptionDAO();
$menuOptionId = $x->createMenuOption($db,MenuOption::createNewTestMenuOption());
echo 'Created Test Menu Option # ' . $menuOptionId . '<br>';

$menuOption  = $x->getMenuOptionById($db,$menuOptionId);
echo json_encode($menuOption);

echo '<br>';

echo 'JSON representation of list of all menuOptions<br>';

$menuOptions  = $x->getAllMenuOptions($db);

echo json_encode($menuOptions);
*/