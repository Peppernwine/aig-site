<?php
    $title = "Menu Option";
    $security = ['minUserType' => 4 ];
    require_once "bootstrap.php";
    require_once RESOURCE_PATH . "/validate-signin.php";
?>


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
    require_once  RESOURCE_PATH . "/database.php";
    require_once  RESOURCE_PATH . "/MenuTypeDAO.class.php";
	require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
	require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
    require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

    $koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";

    $dsMenuOption = new MySQLiDataSource($iDbCon);

    $dsMenuOption->SelectCommand = "SELECT * FROM menu_option";
    $dsMenuOption->UpdateCommand = "UPDATE menu_option 
                                    SET 
                                        option_code='@option_code', option_description='@option_description', 
                                        option_label='@option_label',sort_order='@sort_order', is_required='@is_required', 
                                        has_extra_cost='@has_extra_cost', display_all_options='@display_all_options'
                                    WHERE  
                                        option_id='@option_id'";

    $dsMenuOption->InsertCommand =
                                    "INSERT INTO menu_option
                                        (option_code, option_description, option_label,  
                                        sort_order, is_required, has_extra_cost, display_all_options) 
                                        VALUES ('@option_code','@option_description','@option_label','@sort_order','@is_required','@has_extra_cost','@display_all_options')";

    $dsMenuOption->DeleteCommand = "DELETE FROM menu_option where option_id='@option_id'";

    $grid = new KoolGrid("OptionGrid");
	$grid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";
    $grid->styleFolder  = R_KOOL_CONTROLS_PATH . "/KoolGrid/styles/Office2010Blue";
    $grid->RowAlternative = true;
    $grid->AllowSelecting = true;
    $grid->AllowScrolling = true;
    $grid->SingleColumnSorting = true;
    $grid->AllowInserting = true;
    $grid->AllowSorting = true;
    $grid->AllowEditing = true;
    $grid->AllowDeleting = true;

    $grid->AjaxEnabled = true;
    $grid->DataSource = $dsMenuOption;
    
    $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
    $grid->Width = "100%";
    $grid->ColumnWrap = true;

    $column = new GridEditDeleteColumn();
    $column->ShowDeleteButton = true;
    $column->Align = "center";
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "option_code";
    $column->HeaderText = 'Code';
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "option_description";
    $column->HeaderText = 'Description';
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "option_label";
    $column->HeaderText = 'Label';
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);


    $column = new GridBoundColumn();
    $column->DataField = "sort_order";
    $column->HeaderText = 'Sort Order';
    $column->ReadOnly = false;
    $validator = new RegularExpressionValidator();
    $validator->ValidationExpression = "/^([0-9])+$/";
    $validator->ErrorMessage = "Please input an integer";
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);


    $column = new GridBooleanColumn();
	$column->DataField = "is_required";
    $column->HeaderText = 'Required?';
	$column->ReadOnly = false;
	$column->TrueText = "1";
	$column->FalseText = "0";
    $column->UseCheckBox = true;
	$grid->MasterTable->AddColumn($column);

	$column = new GridBooleanColumn();
	$column->DataField = "has_extra_cost";
    $column->HeaderText = 'Extra Cost?';
	$column->ReadOnly = false;
	$column->TrueText = "1";
	$column->FalseText = "0";
    $column->UseCheckBox = true;
	$grid->MasterTable->AddColumn($column);

	$column = new GridBooleanColumn();
	$column->DataField = "display_all_options";
    $column->HeaderText = 'Display All Options?';
	$column->ReadOnly = false;
	$column->TrueText = "1";
	$column->FalseText = "0";
    $column->UseCheckBox = true;
	$grid->MasterTable->AddColumn($column);

    //Set edit mode to "form"
	$grid->MasterTable->EditSettings->Mode = "form";
    $grid->MasterTable->EditSettings->InputFocus = "HideGrid";

    //Show Function Panel
    $grid->MasterTable->ShowFunctionPanel = true;
    //Insert Settings
    $grid->MasterTable->InsertSettings->Mode = "Form";
    $grid->MasterTable->InsertSettings->InputFocus = "HideGrid";
    $grid->MasterTable->InsertSettings->ColumnNumber = 1;

    $grid->ClientSettings->ClientEvents["OnRowSelect"] = "Handle_OnRowSelect";


	$grid->Process();
?>

<?php
    require_once "header-html.php";
?>


<div class="container-flex form-container">
    <?php
        require_once "popup-header.html.php";
    ?>

    <article class= "group form-section" style="width:70%">
        <h2 class="header-underline group-title">Menu Options & Choices</h2>

        <script type="text/javascript">
            function Handle_OnRowSelect(sender,args)
            {
                //Prepare to refresh the grid_order.
                var _row = args["Row"];
                var optionId = _row.getDataItem()["option_id"];
                $("#optionChoiceFrame").attr("src", 'menu-option-choice.php?option-id=' +optionId);
            }
        </script>


        <form id="form1" method="post">
            <h5>Menu Option</h5>
            <?php echo $koolajax->Render();?>
            <?php echo $grid->Render();?>
         </form>

        <iframe id="optionChoiceFrame" style="border:none;width: 100%;height: 400px">

        </iframe>

    </article>
</div>


<?php
require_once "footer-html.php";
?>