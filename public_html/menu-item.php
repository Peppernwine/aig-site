<?php
    $stylesheets = ['css/home.css?v35','css/form-container.css'];
    $title = "Menu Items";
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
    require_once  RESOURCE_PATH . "/MenuTypeDAO.class.php";
    require_once  RESOURCE_PATH . "/MenuCategoryDAO.class.php";
	require_once  KOOL_CONTROLS_PATH.'/KoolAjax/koolajax.php';
	require_once  KOOL_CONTROLS_PATH."/KoolGrid/koolgrid.php";
    require_once  KOOL_CONTROLS_PATH."/KoolGrid/ext/datasources/MySQLiDataSource.php";

    $koolajax->scriptFolder = R_KOOL_CONTROLS_PATH."/KoolAjax";

    ?>


<?php
class MyEditTemplate implements GridTemplate
{

    private $menuCategories;
    private $menuItemProfiles;
    private $isInsert;

    public function __construct($menuCategories, $menuItemProfiles,$isInsert){
        $this->menuCategories = $menuCategories;
        $this->menuItemProfiles = $menuItemProfiles;
        $this->isInsert = $isInsert;
    }

    function Render($_row)    {
        $itemCategoryId  = $_row->DataItem['menu_category_id'];
        $itemCode  = $_row->DataItem['item_code'];
        $itemDescription  = $_row->DataItem['item_description'];
        $itemBasePrice  = $_row->DataItem['base_price'];
        $menuItemProfileId = $_row->DataItem['menu_item_profile_id'] ;
        $isChefsSpecialChecked = $_row->DataItem['is_chefs_special'] === '1' ? 'checked': '';
        $isGlutenFreeChecked = $_row->DataItem['is_gluten_free'] === '1' ? 'checked': '';
        $isNutFreeChecked = $_row->DataItem['is_nut_free'] === '1' ? 'checked': '';

        $catOpts = '';
        foreach($this->menuCategories as $menuCategory) {
            $catId = $menuCategory->getCategoryId();
            $catCode = $menuCategory->getCategoryCode();

            $selected = $catId == $itemCategoryId ? 'selected' : '';
            $catOpts .= "<option $selected value='$catId'>$catCode</option>";
        }


        $itemProfileOpts = "<option value='0'>None</option>";
        foreach($this->menuItemProfiles as $menuItemProfile) {


            $profileId = $menuItemProfile->getItemProfileId();
            $profileCode = $menuItemProfile->getItemProfileCode();

            $selected = $profileId == $menuItemProfileId ? 'selected' : '';
            $itemProfileOpts .= "<option $selected value='$profileId'>$profileCode</option>";
        }

        $insertOkCancelHTML  =
            <<<EOT
                <div class="kgrFormFooter">
                    <span class="kgrConfirm">
                        <input type="button" onclick="grid_confirm_insert(this)" title="Confirm Insert" class="nodecor">
                        <a href="javascript:void 0" onclick="grid_confirm_insert(this)" title="Confirm Insert">Confirm</a>
                    </span> 
                    <span class="kgrCancel">
                        <input type="button" onclick="grid_confirm_insert(this)" title="Cancel Edit" class="nodecor">
                        <a href="javascript:void 0" onclick="grid_confirm_insert(this)" title="Cancel Insert">Cancel</a>
                    </span> 
                  </div>
EOT;

        $editOkCancelHTML  =
            <<<EOT
        
                <div class="kgrFormFooter">
                    <span class="kgrConfirm">
                        <input type="button" onclick="grid_confirm_edit(this)" title="Confirm Edit" class="nodecor">
                        <a href="javascript:void 0" onclick="grid_confirm_edit(this)" title="Confirm Edit">Confirm</a>
                    </span> 
                    <span class="kgrCancel">
                        <input type="button" onclick="grid_cancel_edit(this)" title="Cancel Edit" class="nodecor">
                        <a href="javascript:void 0" onclick="grid_cancel_edit(this)" title="Cancel Edit">Cancel</a>
                    </span> 
                  </div>
EOT;

        if ($this->isInsert)
            $okCancelHTML = $insertOkCancelHTML;
        else
            $okCancelHTML = $editOkCancelHTML;

        $html  =
            <<<EOT
                  <div class='container-fluid'>
  
                  <div class='row'>  
                      <div class='col-xs-6'>
                        <div class='form-group'>
                           <label for='sel-menu-category'>Category</label>
                            <select class='form-control' id='menu_category_id' name='menu_category_id'>
                                $catOpts
                            </select>
                        </div>
                      </div>
                      
                      <div class='col-xs-6'>
                        <div class='form-group'>
                            <label for='txt-item-name'>Name</label>
                            <input type='text' class='form-control' id='item_code' name='item_code' value='$itemCode'/>
                        </div>
                      </div>
                  </div>
                 
                  <div class='row'>  
                      <div class='col-xs-12'>
                          <div class='form-group'>
                                <label for='txt-item-description'>Description</label>
                                <textarea class='form-control' rows='5' name='item_description' id='item_description'>$itemDescription</textarea>
                          </div>
                       </div>   
                  </div>     
                          
                  <div class='row'>  
                    
                      <div class='col-xs-6'>
                          <div class='form-group'>
                               <label for='txt-item-baseprice'>Base Price</label>
                               <input type='text' class='form-control' id='base_price' name='base_price' value="$itemBasePrice"/>
                          </div>
                      </div>
                      
                      <div class='col-xs-6 '>
                          <div class='form-group'>
                            <label for="sel-item-profile">Profile</label>
                            <select class="form-control" id="menu_item_profile_id" name='menu_item_profile_id'> 
                                $itemProfileOpts
                            </select>
                          </div>
                       </div>   
                  </div>   
          
                  <div class="form-group">
                      <div class='checkbox'>
                          <label>
                            Chef's Special ?
                            <input type="checkbox" id='is_chefs_special' name='is_chefs_special' $isChefsSpecialChecked  value='1'>
                          </label>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <div class='checkbox'>
                          <label>
                            Gluten Free ?
                            <input type='checkbox' id='is_gluten_free' name='is_gluten_free' $isGlutenFreeChecked value='1'>
                          </label>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <div class='checkbox'>
                          <label>Nut Free ? 
                          <input type='checkbox' id='is_nut_free' name='is_nut_free' $isNutFreeChecked value='1' ></label>
                      </div>
                  </div>
                    
                   $okCancelHTML          
                  
			    </div>

EOT;

        return $html;
    }
    function GetData($_row)
    {
        $menuCategoryId = null;
        if (isset($_POST['menu_category_id']))
            $menuCategoryId  = $_POST['menu_category_id'];

        $itemCode = '';
        if (isset($_POST['item_code']))
            $itemCode  = $_POST['item_code'];

        $itemDescription = '';
        if (isset($_POST['item_description']))
            $itemDescription  = $_POST['item_description'];

        $itemBasePrice = 0;
        if (isset($_POST['base_price']))
            $itemBasePrice  = $_POST['base_price'];

        $menuItemProfileId = 0;
        if (!empty($_POST['menu_item_profile_id']))
            $menuItemProfileId = $_POST['menu_item_profile_id'];

        $isChefsSpecial = 0;
        if (!empty($_POST['is_chefs_special']))
            $isChefsSpecial = intval($_POST['is_chefs_special']);

        $isGlutenFree = 0;
        if (!empty($_POST['is_gluten_free']))
            $isGlutenFree = intval($_POST['is_gluten_free']);

        $isNutFree = 0;
        if (!empty($_POST['is_nut_free']))
            $isNutFree = intval($_POST['is_nut_free']);

        $data = array('menu_category_id' => $menuCategoryId,
            'item_code' => $itemCode,
            'item_description' =>  $itemDescription,
            'base_price' => floatval($itemBasePrice),
            'is_chefs_special'  =>  $isChefsSpecial,
            'is_gluten_free'  =>  $isGlutenFree,
            'is_nut_free'  =>  $isNutFree);

         $data['menu_item_profile_id'] = $menuItemProfileId;

        return $data;
    }


}
?>

<?php
    $menuTypeDAO = new MenuTypeDAO();
    $menuTypes = $menuTypeDAO->getAllMenuTypes($db);

    $selMenuTypeId = -1;
    if (isset($_GET['sel-menu-type'])) {

        $selMenuTypeId = intval($_GET['sel-menu-type']);
    } else if (sizeof($menuTypes) > 0) {
        $selMenuTypeId = $menuTypes[0]->getTypeId();
    }

    $menuCategoryDAO = new MenuCategoryDAO();
    $menuCategories = $menuCategoryDAO->getMenuCategoriesByMenuType($db,$selMenuTypeId);


    $menuItemProfileDAO = new MenuItemProfileDAO();
    $menuItemProfiles = $menuItemProfileDAO->getAllMenuItemProfiles($db);

    $dsMenuItem = new MySQLiDataSource($iDbCon);

    $dsMenuItem->SelectCommand = "SELECT * FROM menu_item WHERE menu_type_id = ".$selMenuTypeId;


    $dsMenuItem->UpdateCommand = "UPDATE menu_item 
                                    SET 
                                        menu_category_id = '@menu_category_id',
                                        item_code = '@item_code',item_description = '@item_description', 
                                        base_price = '@base_price',is_chefs_special = '@is_chefs_special',
                                        is_gluten_free = '@is_gluten_free' ,is_nut_free = '@is_nut_free',
                                        menu_item_profile_id = IF(@menu_item_profile_id > 0 ,@menu_item_profile_id, NULL) 
                                    WHERE  
                                        item_id='@item_id' ";

    $dsMenuItem->InsertCommand = "INSERT INTO menu_item
                                        (menu_type_id,menu_category_id,item_code,item_description,
                                        base_price,is_chefs_special,is_gluten_free ,is_nut_free,
                                        menu_item_profile_id) 
                                    VALUES ($selMenuTypeId,'@menu_category_id','@item_code', '@item_description', 
                                      '@base_price', '@is_chefs_special','@is_gluten_free' ,'@is_nut_free',
                                      IF(@menu_item_profile_id > 0 ,@menu_item_profile_id, NULL) )";

    $dsMenuItem->DeleteCommand = "DELETE FROM menu_item where item_id='@item_id'";

    $grid = new KoolGrid("menuGrid");
	$grid->scriptFolder = R_KOOL_CONTROLS_PATH . "/KoolGrid";
	$grid->styleFolder  = R_KOOL_CONTROLS_PATH . "/KoolGrid/styles/office2010blue";
    $grid->RowAlternative = true;
    $grid->AllowSelecting = true;
    $grid->AllowScrolling = true;
    $grid->SingleColumnSorting = true;
    $grid->AllowInserting = true;
    $grid->AllowSorting = true;
    $grid->AllowEditing = true;
    $grid->AllowDeleting = true;

    $grid->AjaxEnabled = true;
    $grid->DataSource = $dsMenuItem;
    
    $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
    $grid->Width = "100%";
    $grid->ColumnWrap = true;

    $column = new GridEditDeleteColumn();
    $column->ShowDeleteButton = true;
    $column->Align = "center";
    $grid->MasterTable->AddColumn($column);


    $column = new GridBoundColumn();
    $column->DataField = "menu_type_id";
    $column->HeaderText = 'Menu Type';
    $column->DefaultValue = $selMenuTypeId;
    $column->ReadOnly = true;
    $column->Visible = false;
    $grid->MasterTable->AddColumn($column);


    $column = new GridDropDownColumn();
    $column->DataField = "menu_category_id";
    $column->HeaderText = "Menu Category";
    //Add required field validator to make sure the input is not empty.
     $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    foreach ($menuCategories as $menuCategory) {
        $column->additem($menuCategory->getCategoryCode(),$menuCategory->getCategoryId());
    }
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "item_code";
    $column->HeaderText = 'Item Name ';
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "item_description";
    $column->HeaderText = 'Description';
    $column->Wrap = true;
    $column->MaxLength = 1024;
    $column->ReadOnly = false;
    //Add required field validator to make sure the input is not empty.
    $validator = new RequiredFieldValidator();
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);

    $column = new GridBoundColumn();
    $column->DataField = "base_price";
    $column->HeaderText = 'Base Price';
    $column->ReadOnly = false;
    $validator = new RegularExpressionValidator();
    $validator->ValidationExpression = "/^([0-9,.])+$/";
    $validator->ErrorMessage = "Please input an decimal";
    $column->AddValidator($validator);
    $grid->MasterTable->AddColumn($column);


    $column = new GridDropDownColumn();
    $column->DataField = "menu_item_profile_id";
    $column->DefaultValue = null;
    $column->HeaderText = "Menu Item Profile";

    $column->additem('None',0);
    foreach ($menuItemProfiles as $menuItemProfile) {
        $column->additem($menuItemProfile->getItemProfileCode(),$menuItemProfile->getItemProfileId());
    }
    $grid->MasterTable->AddColumn($column);


    $column = new GridBooleanColumn();
	$column->DataField = "is_chefs_special";
    $column->HeaderText = 'Chefs Special?';
	$column->ReadOnly = false;
	$column->TrueText = "1";
	$column->FalseText = "0";
    $column->UseCheckBox = true;
	$grid->MasterTable->AddColumn($column);

	$column = new GridBooleanColumn();
	$column->DataField = "is_gluten_free";
    $column->HeaderText = 'Gluten Free?';
	$column->ReadOnly = false;
	$column->TrueText = "1";
	$column->FalseText = "0";
    $column->UseCheckBox = true;
	$grid->MasterTable->AddColumn($column);

	$column = new GridBooleanColumn();
	$column->DataField = "is_nut_free";
    $column->HeaderText = 'Nut Free?';
	$column->ReadOnly = false;
	$column->TrueText = "1";
	$column->FalseText = "0";
    $column->UseCheckBox = true;
	$grid->MasterTable->AddColumn($column);

    //Show Function Panel
    $grid->MasterTable->ShowFunctionPanel = true;

    $grid->MasterTable->EditSettings->InputFocus = "HideGrid";
    $grid->MasterTable->EditSettings->Mode = "Template";
    $grid->MasterTable->EditSettings->Template = new MyEditTemplate($menuCategories,$menuItemProfiles,false);

    $grid->MasterTable->InsertSettings->InputFocus = "HideGrid";
    $grid->MasterTable->InsertSettings->Mode = "Template";
    $grid->MasterTable->InsertSettings->Template = new MyEditTemplate($menuCategories,$menuItemProfiles,true);

    $grid->ClientSettings->ClientEvents["OnRowSelect"] = "Handle_MenuItem_OnRowSelect";



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
        <h2 class="header-underline group-title">Menu</h2>

        <form id="menu-item-form" method="get">

            <div class="form-group">
                <label for="sel-menu-type" >Select Menu Type</label>
                <select onchange="this.form.submit()" id="sel-menu-type" name="sel-menu-type" >
                    <?php
                        foreach ($menuTypes as $menuType) {
                            $selected  = $menuType->getTypeId() == $selMenuTypeId ? 'selected' : '';
                            echo "<option $selected value='".$menuType->getTypeId()."'>" . $menuType->getTypeCode() . "</option>";
                        }
                    ?>
                </select>
            </div>
            <?php echo $koolajax->Render();?>
            <?php echo $grid->Render();?>
         </form>

    </article>
</div>

<?php
require_once "footer-html.php";
?>