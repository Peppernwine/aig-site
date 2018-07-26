<?php
    $stylesheets = ['css/home.css?v35','css/form-container.css'];
    $title = "Menu Type Hours";
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

    $KoolControlsFolder = KOOL_CONTROLS_PATH;
    require_once  RESOURCE_PATH . "/database.php";
    require_once  RESOURCE_PATH . "/mysqlidb.php";
	require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
	require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
    require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";
    require_once  RESOURCE_PATH . "/MenuTypeDAO.class.php";

    $koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";


    $menuTypeDAO = new MenuTypeDAO();
    $menuTypes = $menuTypeDAO->getAllMenuTypes($db);


    $ds = new MySQLiDataSource($iDbCon);
	$ds->SelectCommand = "SELECT * FROM menu_type_hour ORDER BY menu_type_id";
	$ds->UpdateCommand = "UPDATE menu_type_hour set menu_type_id='@menu_type_id',hours_description='@hours_description' where hour_id='@hour_id'";
    $ds->InsertCommand = "INSERT INTO menu_type_hour(menu_type_id,hours_description) values ('@menu_type_id','@hours_description')";
    $ds->DeleteCommand = "DELETE FROM menu_type_hour where hour_id='@hour_id'";

    $grid = new KoolGrid("grid");
	$grid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";
	$grid->styleFolder= R_KOOL_CONTROLS_PATH . "/KoolGrid/styles/office2010blue";
    $grid->RowAlternative = true;
    $grid->AllowSelecting = true;
    $grid->AllowScrolling = true;
    $grid->SingleColumnSorting = true;
    $grid->AllowInserting = true;
    $grid->AllowSorting = true;
    $grid->AllowEditing = true;
    $grid->AllowDeleting = true;

    $grid->AjaxEnabled = true;
    $grid->DataSource = $ds;
    $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
    $grid->Width = "100%";
    $grid->ColumnWrap = true;

    $column = new GridEditDeleteColumn();
    $column->ShowDeleteButton = true;
    $column->Align = "center";
    $column->Width = "6rem";
    $grid->MasterTable->AddColumn($column);

    $column = new GridDropDownColumn();
    $column->DataField = "menu_type_id";
    $column->HeaderText = "Menu Type";
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);

    foreach ($menuTypes as $menuType) {
        $column->additem($menuType->getTypeCode(),$menuType->getTypeId());
    }
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "hours_description";
    $column->HeaderText = 'Hours';
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
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
        <h2 class="header-underline group-title">Menu Type Hours</h2>
        <form id="form1" method="post">
            <div style="font-family: 'EB Garamond', serif">
            <?php echo $koolajax->Render();?>
            <?php echo $grid->Render();?>
            </div>
        </form>
    </article>
</div>


<?php
    require_once "footer-html.php";
?>