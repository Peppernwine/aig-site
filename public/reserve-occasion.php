
<?php
$stylesheets = ['css/home.css?v35','css/form-container.css'];
$title = "Reserve Occasion";
$security = ['minUserType' => 4 ];
require_once "path.php";
require_once RESOURCE_PATH . "/validate-signin.php";
?>

<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/6/2018
 * Time: 1:15 PM
 */

require_once "path.php";
$KoolControlsFolder = KOOL_CONTROLS_PATH;
require_once  RESOURCE_PATH . "/database.php";
require_once  RESOURCE_PATH . "/mysqlidb.php";
require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

$koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";



$ds = new MySQLiDataSource($iDbCon);
$ds->SelectCommand = "SELECT * FROM reserve_occasion ORDER BY occasion_code";
$ds->UpdateCommand = "UPDATE reserve_occasion
                      SET 
                            occasion_code='@occasion_code', occasion_description='@occasion_description'
                          WHERE 
                            occasion_id='@occasion_id'";
$ds->InsertCommand = "INSERT INTO 
                            reserve_occasion(occasion_code,occasion_description) 
                          VALUES 
                            ('@occasion_code','@occasion_description')";

$ds->DeleteCommand = "DELETE FROM reserve_occasion where occasion_id='@occasion_id'";

$grid = new KoolGrid("grid");
$grid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";
$grid->styleFolder="office2010blue";
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

$column = new GridBoundColumn();
$column->DataField = "occasion_code";
$column->HeaderText = 'Code';
$column->ReadOnly = false;
//Add required field validator to make sure the input is not empty.
$validator = new RequiredFieldValidator();
$column->AddValidator($validator);
$grid->MasterTable->AddColumn($column);

$column = new GridBoundColumn();
$column->DataField = "occasion_description";
$column->HeaderText = 'Description';
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
        <h2 class="header-underline group-title">Reserve Occasion</h2>
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