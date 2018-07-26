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
    require_once  RESOURCE_PATH . "/mysqlidb.php";
	require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
	require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
    require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

    $koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";

    $optionChoiceGrid = null;

    $optionId = 0;
    if (isset($_GET["option-id"]))
        $optionId = $_GET["option-id"];

    $dsMenuItemOption = new MySQLiDataSource($iDbCon);

    $dsMenuItemOption->SelectCommand = "SELECT * FROM menu_option_choice 
                                          WHERE menu_option_id = " . $optionId .
                                          " ORDER BY option_choice_value";
    $dsMenuItemOption->InsertCommand = "INSERT INTO menu_option_choice
                                              (menu_option_id,
                                              option_choice_value,option_choice_code,option_choice_description,price_delta)
                                          VALUES("
                                              .$optionId.
                                              ",'@option_choice_value','@option_choice_code','@option_choice_description','@price_delta')";

    //echo $dsMenuOptionChoice->InsertCommand;
    $dsMenuItemOption->UpdateCommand = "UPDATE 
                                              menu_option_choice
                                          SET 
                                              menu_option_id = " . $optionId . ", " .
                                              "option_choice_value = '@option_choice_value',
                                              option_choice_code = '@option_choice_code',
                                              option_choice_description = '@option_choice_description' ,
                                              price_delta = '@price_delta'
                                          WHERE 
                                              option_choice_id='@option_choice_id'";
    $dsMenuItemOption->DeleteCommand = "DELETE FROM menu_option_choice WHERE option_choice_id='@option_choice_id'";

    $optionChoiceGrid = new KoolGrid("optionChoiceGrid".$optionId);
    $optionChoiceGrid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";
    $optionChoiceGrid->styleFolder  = R_KOOL_CONTROLS_PATH . "/KoolGrid/styles/Office2010Blue";
    $optionChoiceGrid->RowAlternative = true;
    $optionChoiceGrid->AllowSelecting = true;
    $optionChoiceGrid->AllowScrolling = true;
    $optionChoiceGrid->SingleColumnSorting = true;
    $optionChoiceGrid->AllowInserting = true;
    $optionChoiceGrid->AllowSorting = true;
    $optionChoiceGrid->AllowEditing = true;
    $optionChoiceGrid->AllowDeleting = true;

    $optionChoiceGrid->AjaxEnabled = true;
    $optionChoiceGrid->DataSource = $dsMenuItemOption;
    $optionChoiceGrid->MasterTable->DataSource = $dsMenuItemOption;
    $optionChoiceGrid->Width = "100%";
    $optionChoiceGrid->ColumnWrap = true;

    $column = new GridEditDeleteColumn();
    $column->ShowDeleteButton = true;
    $column->Align = "center";
    $optionChoiceGrid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "menu_option_id";
    $column->HeaderText = "Option Id";
    $column->DefaultValue = $optionId;
    $column->Visible = false;
    $column->ReadOnly = true;
    $optionChoiceGrid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "option_choice_code";
    $column->HeaderText = 'Choice Code';
    $optionChoiceGrid->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $optionChoiceGrid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "option_choice_value";
    $column->HeaderText = 'Choice Value';
    $column->ReadOnly = false;
    $validator = new RegularExpressionValidator();
    $validator->ValidationExpression = "/^([0-9,.])+$/";
    $validator->ErrorMessage = "Please input an Integer Value";
    $column->AddValidator($validator);
    $optionChoiceGrid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "option_choice_description";
    $column->HeaderText = 'Choice Description';
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $optionChoiceGrid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "price_delta";
    $column->HeaderText = 'Price Delta';
    $column->ReadOnly = false;
    $validator = new RegularExpressionValidator();
    $validator->ValidationExpression = "/^([0-9,.])+$/";
    $validator->ErrorMessage = "Please input an decimal";
    $column->AddValidator($validator);
    $optionChoiceGrid->MasterTable->AddColumn($column);

    //Set edit mode to "form"
    $optionChoiceGrid->MasterTable->EditSettings->Mode = "form";
    $optionChoiceGrid->MasterTable->EditSettings->InputFocus = "None";

    //Show Function Panel
    $optionChoiceGrid->MasterTable->ShowFunctionPanel = true;
    //Insert Settings
    $optionChoiceGrid->MasterTable->InsertSettings->Mode = "form";
    $optionChoiceGrid->MasterTable->InsertSettings->InputFocus = "None";
    $optionChoiceGrid->MasterTable->InsertSettings->ColumnNumber = 1;
    $optionChoiceGrid->Process();
?>

<form id="optionChoice" method="post">
    <h5>Menu Option Choices</h5>
    <?php echo $koolajax->Render();?>
    <?php echo $optionChoiceGrid->Render();?>
</form>

</body>

</html>
