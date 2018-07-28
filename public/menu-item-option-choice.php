
<?php
    $security = ['minUserType' => 4 ];
    require_once "bootstrap.php";
    require_once RESOURCE_PATH . "/validate-signin.php";
?>


<html>
<head>
    <title><?php echo isset($title) ?  "$title-" : "" ; ?>Avon Indian Grill</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css?v2">
    <link rel="stylesheet" href="css/base.css?v175"/>
    <link rel="stylesheet" href="css/font.css?v14"/>
    <script src="vendor/jquery/jquery-3.3.1.min.js"></script>
</head>
<body>

    <?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/6/2018
 * Time: 1:15 PM
 */


    require_once RESOURCE_PATH . "/session.php";
    $KoolControlsFolder = KOOL_CONTROLS_PATH;
    require_once  RESOURCE_PATH . "/MenuOptionDAO.class.php";
    require_once  RESOURCE_PATH . "/mysqlidb.php";
	require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
	require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
    require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

    $koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";

    $itemProfileOptionChoiceGrid = null;

//menu-option-id=' +optionId + '&menu-profile-option-id='+ profileOptionId)

    $menuOptionId = 0;
    if (isset($_GET["menu-option-id"]))
        $menuOptionId = $_GET["menu-option-id"];

    $itemProfileOptionId = 0;
    if (isset($_GET["item-profile-option-id"]))
        $itemProfileOptionId = $_GET["item-profile-option-id"];

    $menuOptionDAO = new MenuOptionDAO();
    $menuOption = $menuOptionDAO->getMenuOptionById( $db,$menuOptionId);

    $dsMenuItemOptionChoice = new MySQLiDataSource($iDbCon);

    $dsMenuItemOptionChoice->SelectCommand = "SELECT * FROM menu_item_profile_option_choice WHERE menu_item_profile_option_id = ".$itemProfileOptionId;
    $dsMenuItemOptionChoice->UpdateCommand = "UPDATE menu_item_profile_option_choice
                                        SET 
                                           menu_item_profile_option_id  = " .$itemProfileOptionId. ",menu_option_choice_id ='@menu_option_choice_id'
                                        WHERE  
                                           item_profile_option_choice_id ='@item_option_choice_id '";




    $dsMenuItemOptionChoice->InsertCommand = "INSERT INTO menu_item_profile_option_choice
                                          (menu_item_profile_option_id,menu_option_choice_id) 
                                        VALUES 
                                          (" .$itemProfileOptionId. ",'@menu_option_choice_id')";

    $dsMenuItemOptionChoice->DeleteCommand = "DELETE FROM 
                                          menu_item_profile_option_choice 
                                        WHERE  
                                          item_profile_option_choice_id='@item_option_choice_id'";

    $itemProfileOptionChoiceGrid = new KoolGrid("itemProfileOptionChoiceGrid");
    $itemProfileOptionChoiceGrid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";

    $itemProfileOptionChoiceGrid->styleFolder  = R_KOOL_CONTROLS_PATH . "/KoolGrid/styles/office2010blue";
    $itemProfileOptionChoiceGrid->RowAlternative = true;
    $itemProfileOptionChoiceGrid->AllowSelecting = true;
    $itemProfileOptionChoiceGrid->AllowScrolling = true;
    $itemProfileOptionChoiceGrid->SingleColumnSorting = true;
    $itemProfileOptionChoiceGrid->AllowInserting = true;
    $itemProfileOptionChoiceGrid->AllowSorting = true;
    $itemProfileOptionChoiceGrid->AllowEditing = true;
    $itemProfileOptionChoiceGrid->AllowDeleting = true;

    $itemProfileOptionChoiceGrid->AjaxEnabled = true;
    $itemProfileOptionChoiceGrid->DataSource = $dsMenuItemOptionChoice;
    $itemProfileOptionChoiceGrid->MasterTable->DataSource = $dsMenuItemOptionChoice;
    $itemProfileOptionChoiceGrid->Width = "100%";
    $itemProfileOptionChoiceGrid->ColumnWrap = true;

    $column = new GridEditDeleteColumn();
    $column->ShowDeleteButton = true;
    $column->Width = "6rem";
    $column->Width = "6rem";
    $column->Align = "center";
    $itemProfileOptionChoiceGrid->MasterTable->AddColumn($column);

    $column = new GridDropDownColumn();
    $column->DataField = "menu_option_choice_id";
    $column->HeaderText = "Menu Option Choice";

    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);

    foreach ($menuOption->getOptionChoices() as $optionChoice) {
        $column->additem($optionChoice['optionChoiceCode'],$optionChoice['optionChoiceId']);
    }
    $itemProfileOptionChoiceGrid->MasterTable->AddColumn($column);


//Set edit mode to "form"
    $itemProfileOptionChoiceGrid->MasterTable->EditSettings->Mode = "form";
    $itemProfileOptionChoiceGrid->MasterTable->EditSettings->InputFocus = "None";

    //Show Function Panel
    $itemProfileOptionChoiceGrid->MasterTable->ShowFunctionPanel = true;
    //Insert Settings
    $itemProfileOptionChoiceGrid->MasterTable->InsertSettings->Mode = "form";
    $itemProfileOptionChoiceGrid->MasterTable->InsertSettings->InputFocus = "None";
    $itemProfileOptionChoiceGrid->MasterTable->InsertSettings->ColumnNumber = 1;

    $itemProfileOptionChoiceGrid->Process();
?>

    <form id="menu-item-option-form" method="post">
        <h5>Menu Profile Option Choices</h5>
        <?php echo $koolajax->Render();?>
        <?php echo $itemProfileOptionChoiceGrid->Render();?>
    </form>


</body>

</html>
