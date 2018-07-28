
<?php
    $stylesheets = ['css/home.css?v35','css/form-container.css'];
    $title = "Menu Profile Option";
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
    require_once RESOURCE_PATH . "/MenuItemProfileDAO.class.php";
    require_once  RESOURCE_PATH . "/MenuOptionDAO.class.php";
    require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
	require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
    require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

    $koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";

    $menuOptionDAO = new MenuOptionDAO();
    $menuOptions = $menuOptionDAO->getAllMenuOptions( $db);

    $menuItemProfileDAO = new MenuItemProfileDAO();
    $menuItemProfiles = $menuItemProfileDAO->getAllMenuItemProfiles($db);


    $selItemProfileId = -1;
    if (isset($_GET['sel-item-profile'])) {
        $selItemProfileId = intval($_GET['sel-item-profile']);
    } else if (sizeof($menuItemProfiles) > 0) {
        $selItemProfileId = $menuItemProfiles[0]->getItemProfileId();
    }

    $dsItemProfileOption = new MySQLiDataSource($iDbCon);

    $dsItemProfileOption->SelectCommand = "SELECT * FROM menu_item_profile_option WHERE menu_item_profile_id = " .$selItemProfileId;
    $dsItemProfileOption->UpdateCommand = "UPDATE menu_item_profile_option 
                                            SET 
                                               menu_item_profile_id = " .$selItemProfileId. ",menu_option_id='@menu_option_id'
                                            WHERE  
                                               profile_option_id='@profile_option_id'";


    $dsItemProfileOption->InsertCommand = "INSERT INTO menu_item_profile_option
                                          (menu_item_profile_id,menu_option_id) 
                                        VALUES 
                                          (" .$selItemProfileId. ",'@menu_option_id')";


    $dsItemProfileOption->DeleteCommand = "DELETE FROM 
                                              menu_item_profile_option 
                                           WHERE  
                                              profile_option_id='@profile_option_id'";

    $itemProfileOptionGrid = new KoolGrid("itemProfileOptionGrid");
    $itemProfileOptionGrid->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolGrid";
    $itemProfileOptionGrid->styleFolder  = R_KOOL_CONTROLS_PATH . "/KoolGrid/styles/office2010blue";
    $itemProfileOptionGrid->RowAlternative = true;
    $itemProfileOptionGrid->AllowSelecting = true;
    $itemProfileOptionGrid->AllowScrolling = true;
    $itemProfileOptionGrid->SingleColumnSorting = true;
    $itemProfileOptionGrid->AllowInserting = true;
    $itemProfileOptionGrid->AllowSorting = true;
    $itemProfileOptionGrid->AllowEditing = true;
    $itemProfileOptionGrid->AllowDeleting = true;

    $itemProfileOptionGrid->AjaxEnabled = true;
    $itemProfileOptionGrid->DataSource = $dsItemProfileOption;
    $itemProfileOptionGrid->MasterTable->DataSource = $dsItemProfileOption;
    $itemProfileOptionGrid->Width = "100%";
    $itemProfileOptionGrid->ColumnWrap = true;

    $column = new GridEditDeleteColumn();
    $column->ShowDeleteButton = true;
    $column->Width = '6rem';
    $column->Align = "center";
    $itemProfileOptionGrid->MasterTable->AddColumn($column);

    $column = new GridDropDownColumn();
    $column->DataField = "menu_option_id";
    $column->HeaderText = "Menu Option";
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    foreach ($menuOptions as $menuOption) {
        $column->additem($menuOption->getOptionCode(),$menuOption->getOptionId());
    }
    $itemProfileOptionGrid->MasterTable->AddColumn($column);

    //Set edit mode to "form"
    $itemProfileOptionGrid->MasterTable->EditSettings->Mode = "form";
    $itemProfileOptionGrid->MasterTable->EditSettings->InputFocus = "None";

    //Show Function Panel
    $itemProfileOptionGrid->MasterTable->ShowFunctionPanel = true;
    //Insert Settings
    $itemProfileOptionGrid->MasterTable->InsertSettings->Mode = "form";
    $itemProfileOptionGrid->MasterTable->InsertSettings->InputFocus = "None";
    $itemProfileOptionGrid->MasterTable->InsertSettings->ColumnNumber = 1;

    $itemProfileOptionGrid->ClientSettings->ClientEvents["OnRowSelect"] = "Handle_Profile_Item_Option_OnRowSelect";

    $itemProfileOptionGrid->Process();
?>

<?php
    require_once "header-html.php";
?>

<div class="container-flex form-container">

    <?php
        require_once "popup-header.html.php";
    ?>

    <article class= "group form-section" style="width:70%">
        <h2 class="header-underline group-title">Menu Item Profile - Options & Choices</h2>

        <script type="text/javascript">
            function Handle_Profile_Item_Option_OnRowSelect(sender,args)
            {
                //Prepare to refresh the option grid.
                var _row = args["Row"];

                var optionId = _row.getDataItem()["menu_option_id"];
                var itemProfileOptionId = _row.getDataItem()["item_profile_option_id"];

                $("#menu-item-profile-choice-frame").attr("src", 'menu-item-option-choice.php?menu-option-id=' +optionId + '&item-profile-option-id='+ itemProfileOptionId);
            }
        </script>

        <form id="menu-option-profile-form" method="get">
            <div class="form-group">
                <label for="sel-item-profile" >Select Item Profile</label>
                <select onchange="this.form.submit()" id="sel-item-profile" name="sel-item-profile" >
                    <?php
                        foreach ($menuItemProfiles as $menuItemProfile) {
                            $selected  = $menuItemProfile->getItemProfileId() == $selItemProfileId ? 'selected' : '';
                            echo "<option $selected value='".$menuItemProfile->getItemProfileId()."'>" . $menuItemProfile->getItemProfileCode() . "</option>";
                        }
                    ?>
                </select>
            </div>

            <h5>Menu Profile Option</h5>
            <?php echo $koolajax->Render();?>
            <?php echo $itemProfileOptionGrid->Render();?>
         </form>

        <iframe id="menu-item-profile-choice-frame" style="border:none;width: 100%;height:300px">

        </iframe>

    </article>
</div>

<?php
require_once "footer-html.php";
?>