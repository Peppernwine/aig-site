<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 9:54 AM
 */
    $stylesheets = ['css/home.css?v34','css/form-container.css?v3'];
    $title = "My Orders";
    $security = ['minUserType' => 1 ];
    require_once "bootstrap.php";
    require_once RESOURCE_PATH . "/validate-signin.php";
?>

<?php
require_once RESOURCE_PATH . "/configuration.class.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/http-helper.php";
require_once RESOURCE_PATH . "/user-session.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";
?>

<?php
    require_once "header-html.php";
?>

<div  class="container-flex form-container">

    <?php
        require_once "popup-header.html.php";
    ?>

    <?php echo Configuration::instance()->getInternalJSTag("js/orderview.js?v25")?>

    <article class= "group form-section full-size">
        <h2 class="header-underline group-title">My Orders</h2>

        <section id="orders-section" class="sub-group">
            <div class="container-flex">
                <div style="align-items: center;flex-direction:column;display: flex;justify-content: center"  >
                    <div style="margin:5px" class="form-group">
                        <label for="period">Find Orders from the last:</label>
                    </div>

                    <div style="display:flex;flex-direction:row;justify-content: center;align-items: center" class = "clear-fix" >
                                <div style="margin:5px" class="form-group">
                                    <select data-bind="options:getPeriods,value:selPeriod" style="padding:2px" class="form-control" id="period" name="period" aria-describedby="periodHelp">
                                    </select>
                                </div>

                                <div style="margin:5px" class="form-group">
                                    <select data-bind="options:getPeriodTypes,value:selPeriodType" style="padding:2px" class="form-control" id="period=type" name="period=type" aria-describedby="period=typeHelp">
                                    </select>
                                </div>

                            <div style="margin:5px" class="form-group">
                                <button data-bind = "click:newSearch" class="btn btn-primary">Search</button>
                            </div>
                    </div>
                    <div data-bind="foreach:orders" style="flex-flow: row wrap;flex-direction:row;display: flex;justify-content: center">
                       <div style="font-size:.80rem" class="panel report-panel">
                            <div data-bind="text:getDisplayRequestDate" style="font-family:novecentowide-demibold" class="panel-heading report-panel-heading">Sat, Jun 16 2018</div>
                            <div data-bind="text:getCustomerDisplayName" style="font-family:novecentowide-demibold;font-weight:bold;color:#b70038;text-align: center">#100</div>
                            <div style="font-family:novecentowide-demibold;font-weight:bold;color:#b70038;text-align: center">
                                <span data-bind="text:getDisplayOrderType">Express Dine-in</span>
                                <span data-bind="text:getDisplayOrderNumber">#355</span>
                            </div>
                            <div data-bind="text:getDisplayTotal" style="font-family:novecentowide-demibold;font-weight:bold;color:#b70038;text-align: center">$120.95</div>
                            <div class="panel-body report-panel-body" style="padding:5px;min-height: 125px;max-height: 125px">
                                <ul data-bind="foreach:items" style="list-style: none;margin: 0px;padding: 0px;text-align: center;font-size: .75rem">
                                    <li data-bind="text:menuItem" >Chicken 65</li>
                                </ul>
                            </div>

                            <div class='panel-footer report-panel-footer' style="margin:10px 0;text-align:center">
                                <a data-bind="attr: { href: orderURL}" target="_blank" class="btn btn-info">View</a>
                                <a data-bind="click:reOrder" target="_blank" class="btn btn-info">Reorder</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center;margin: 10px 0px">
                <button data-bind = "click:loadMore,enable:moreDataAvailable" class="btn btn-primary">SEE MORE</button>
            </div>
        </section>

    </article>
</div>

<?php
require_once "footer-html.php";
?>