
<?php
$stylesheets = ['css/catering.css?v1'];
$title = "Catering Menu";
require_once "header-html.php";
?>

    <section class="container">

    <div class="sec-page-empty-content">

    </div>

    <div class="sec-page-header">

    </div>

    <div class="sec-page-header-caption">
        <h2>CATERING</h2>
    </div>

    <div class="sec-page-header-overlay">

    </div>

    <?php echo Configuration::instance()->getInternalJSTag("js/catering.js?v28")?>

    <article class= "group form-section full-size">

        <h2 class="header-underline group-title">Packages</h2>

        <div style="color:rgb(183,0,56);margin:10px 0;text-align:center">
            <ul style="list-style: none;font-weight: bold;">
                <li>Price starts at $11.50 per person</li>
                <li>Call us for price and for volume discounts</li>
                <li>Min. order size – 20 persons</li>
                <li>We offer a variety of items in addition to the regular dine-in menu</li>
            </ul>
        </div>

        <section id="orders-section" class="sub-group">
            <div class="container-flex">
                <div style="align-items: center;flex-direction:column;display: flex;justify-content: center"  >

                    <div data-bind="foreach:packageOptions" style="flex-flow: row wrap;flex-direction:row;display: flex;justify-content: center">
                       <div class="panel report-panel">
                           <div data-bind="text:optionName" style="font-family:novecentowide-demibold" class="panel-heading report-panel-heading">Option 1</div>
                           <div class="panel-body report-panel-body" style="min-height: 260px;max-height: 260px">
                                <ul data-bind="foreach:items" style="list-style: none;margin: 0px;padding: 0px;font-size: 1rem">
                                    <li>
                                        <span data-bind="text:itemCount"></span>
                                        <span data-bind="text:categoryCode"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <h2 class="header-underline group-title">Menu Items</h2>

        <div data-bind="foreach:menuCategories" style="flex-flow: row wrap;flex-direction:row;display: flex;justify-content: center">
            <div class="panel report-panel">
                <div data-bind="text:categoryCode" style="font-family:novecentowide-demibold" class="panel-heading report-panel-heading">Option 1</div>
                <div class="panel-body report-panel-body" style="min-height: 305px;max-height: 305px">
                    <ul data-bind="foreach:items" style="list-style: none;margin: 0px;padding: 0px;font-size: 1rem">
                        <li>
                            <span data-bind="text:itemCode"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="color:rgb(183,0,56);margin:10px 0;text-align:center">
            <ul style="list-style: none;font-weight: bold;">
                <li>Spice level can be adjusted to your taste</li>
                <li>Choose your option and customize to suit your needs</li>
                <li>We cater for all occasions</li>
            </ul>
        </div>

    </article>


    </section>


<?php
require_once "footer-html.php";
?>