<?php
    require_once "bootstrap.php";
    require_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
?>

<?php
    $title = "Menu";
    require_once "header-html.php";
?>

        <section class="container">

        <div class="sec-page-empty-content">

        </div>

        <div class="sec-page-header">

        </div>

        <div class="sec-page-header-caption">
            <h2>MENU</h2>
        </div>

        <div class="sec-page-header-overlay">

        </div>

        <article id="menu-list" class= "group menu">
           <h2 data-bind="text:menu.typeCode" class="header-underline group-title">Lunch & Dinner Menu</h2>
           <section class="sub-group">
               <div class="op-hours" data-bind="foreach: menu.hourDescriptions"  >
                   <p data-bind="text:$data">Lunch Hours : Tue - Fri 11:30am - 2:30pm, Sat &amp; Sun 11:30am - 3pm</p>
                  <!-- <p>Dinner Hours : Tue - Thu &amp; Sun 4:30pm - 9:30pm, Fri & Sat 4:30pm - 10pm</p> -->
               </div>
            </section>

            <div data-bind="visible:menu.isFood()" style="border: 1px solid #b70038 ;font-weight:bold;text-align: center;margin-top:1rem;padding:5px 0 10px 0">
                <span style="vertical-align:middle;padding-right:.15rem" class="chefs-special">Chef's Special</span>
                <span style="vertical-align:middle;padding-right:.15rem" class="hot">Hot</span>
                <span style="vertical-align:middle;padding-right:.15rem" class="extra-hot">Exta Hot</span>
            </div>

            <div style="margin:30px 0 0 0;width:100%"  class="input-group">

                <div style="font-size:1rem" class="inner-addon">
                    <i class="left-addon glyphicon glyphicon-search"></i>
                    <input
                            id = "search-menu-item"
                            data-bind="value:menufilterValue,valueUpdate: 'afterkeydown'"
                            style="font-size:1rem;width:100%;border-radius:500rem"
                            class="form-control" placeholder="Search..."
                            aria-label="search menu item">
                </div>

                <div>
                    <i style="z-index:5;position: absolute;top:10px;right:10px;border-radius:500rem" data-toggle="dropdown"  class="fas fa-arrow-circle-down"></i>

                    <ul data-bind="foreach: categories"  class="dropdown-menu dropdown-menu-right">
                        <li><a data-bind="text: code, click: gotoMenuCategory"></a></li>
                    </ul>
                </div>
            </div>


            <!--Load Menu Category  & Items -->
            <!-- ko foreach: categories -->
            <section data-bind="attr: { id: id}" class="sub-group menu-category">
                <h3 data-bind="text: code"></h3>
                <div data-bind="foreach: items" class="bag">
                    <div data-bind="click: $root.orderVM.menu.isTypeAvailableOnline() ? $root.orderVM.showNewOrderItem : null ,attr: { id: itemId}"  class='menu-item-group'>
                        <div class='menu-name-group'>
                            <span data-bind="text:itemCode" class='menu-name'> </span>
                            <span class='menu-flags'>
                             <i class="chefs-special" data-bind="visible:isChefSpecial == 1"></i>
                             <i class="hot" data-bind="visible:minSpiceLevel == 2"></i>
                             <i class="extra-hot" data-bind="visible: minSpiceLevel > 2" ></i>
                            </span>
                        </div>
                        <span data-bind="text: getBaseDisplayPrice()" class='menu-price'></span>
                        <span data-bind ="visible:menu.isTypeAvailableOnline()" class='add-to-bag'><i class='fas fa-shopping-bag'></i> </span>
                        <div  data-bind ="text: itemDescription" class='menu-description'> </div>
                    </div>
                </div>
            </section>
            <!-- /ko -->
        </article>


        <section data-bind="with:orderVM.currentOrderItem()" id= "order-selection-container" class="dialog-container hide">
            <div id= "order-selection-dialog" class="dialog" >
                <header class="dialog-header dialog-big-header">
                    <nav>
                        <span data-bind="click: $root.orderVM.cancelOrderItemSelection" class="dialog-close-btn"></span>
                        <div class="dialog-title">
                            <p data-bind="text:menuItem.itemCode" class="no-margin">Dal Makhani</p>
                            <p data-bind="text:getBaseDisplayPrice" class="no-margin">$14.00</p>
                        </div>
                    </nav>


                    <!-- ko with: $root.orderVM-->
                    <div id="order-selection-toolbar" class="dialog-toolbar">
                        <button data-bind="enable:currentOrderItem().isValid,text:getEditButtonDisplayText, click:currentOrderItem().isNew() ? addItem : updateItem" type="button" class="btn btn-big btn-primary pull-left" id="btn-addtobag" name="btn-addtobag" value="Add">Add to Bag : $11.95</button>
                        <button data-bind="click: cancelOrderItemSelection" type="button"  class="btn btn-big btn-cancel pull-left " id="btn-cancel-item" name="btn-cancel-item" value="">Cancel</button>
                    </div>
                    <!-- /ko -->

                </header>

                <main>
                    <form id="order-selection-form" class="clear-fix">

                        <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >

                        <div class="form-group sub-group">
                            <h4>Item</h4>
                            <p data-bind="text:menuItem.itemDescription">Black lentils simmered overnight in a slow oven and cooked with ginger, garlic, tomato, and herbs. Vegetarian.</p>
                        </div>

                        <div class="form-group sub-group">
                            <h4>Quantity</h4>
                            <button data-bind = "enable:canDecrementQty ,click:decrementQty" class="btn-inc" type="button">
                                <i class="fas fa-minus"></i>
                            </button>

                            <input data-bind = "value:qty" class="number-input form-control input-auto" type="text" id="quantity" name="quantity" aria-describedby="quanityHelp">

                            <button data-bind = "click:incrementQty"  class="btn-inc" type="button">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>


                        <!-- ko with: menuItem-->
                        <div data-bind="visible:hasOptions" >
                            <!-- ko foreach: options -->
                            <div class="form-group sub-group">

                                <!-- ko with: option-->
                                    <h4 data-bind="text:optionLabel">Choose your protein</h4>
                                <!-- ko foreach: optionChoices -->
                                    <label data-bind="visible:$parents[1].canDisplayOption(optionChoiceId)" class="menu-option radio-inline">
                                        <input data-bind="enable:$parents[1].isOptionAvailable(optionChoiceId),groupoptionradio:$root.orderVM.currentOrderItem().selectedOptions,checkedValue:$data.selectKey,attr:{id: $parents[2].getUniqueOptionChoiceId(option.optionId,optionChoiceId),name:$parents[2].getOptionCode(option.optionId)} ,value:optionChoiceId" type="radio" name="option" >
                                        <span data-bind="text:optionChoiceCode"></span>
                                        <small class="muted" data-bind="visible:$parents[2].hasAdditionalCost(option.optionId,optionChoiceId), text:$parents[2].getDisplayOptionPriceDelta(option.optionId,optionChoiceId)">$12</small>
                                    </label>
                                    <!-- /ko -->
                                <!-- /ko -->
                            </div>
                            <!-- /ko -->
                        </div>
                        <!-- /ko -->

                        <div class="form-group sub-group">
                            <h4>Special Instructions</h4>
                            <textarea data-bind="value:instructions" class="form-control" rows="4" id="special-instructions" name="special-instructions"
                                      aria-describedby="specialinstructionsHelp"
                                      placeholder="Extra condiments ? Make me cry spicy ? Additional Dietary restrictions? Let us know here"></textarea>
                        </div>


                    </form>
                </main>
            </div>
        </section>

        <!-- ko with: $root.orderVM-->
        <section id="tip-selection-container" class="dialog-container" style="display:none;position: fixed;z-index: 20">
            <div style="background:#EF8D24;position:absolute;width: 14rem;height:auto;border-radius: 15px;padding: .25rem;box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26), 0 2px 10px 0 rgba(0, 0, 0, 0.22);border: 1px solid black;" id= "tip-selection-dialog" >
                <main>
                    <form style="margin:0px;width:100%;max-height:80%;">
                        <div style="width:100%;margin:1px;" class="btn-group" style="position:static">
                            <button data-bind="click:orderCheckout.tips().calculatePC" style="width:33%" value="0" type="button" class="btn btn-select btn-cancel">0%</button>
                            <button data-bind="click:orderCheckout.tips().calculatePC" style="width:33%" value="5" type="button" class="btn btn-select btn-cancel">5%</button>
                            <button data-bind="click:orderCheckout.tips().calculatePC" style="width:33%" value="10" type="button" class="btn btn-select btn-cancel">10%</button>
                        </div>
                        <div style="width:100%;" class="btn-group" style="position:static">
                            <button data-bind="click:orderCheckout.tips().calculatePC" style="width:33%" value="15" type="button" class="btn btn-select btn-cancel">15%</button>
                            <button data-bind="click:orderCheckout.tips().calculatePC" style="width:33%" value="18" type="button" class="btn btn-select btn-cancel">18%</button>
                            <button data-bind="click:orderCheckout.tips().calculatePC" style="width:33%" value="20" type="button" class="btn btn-select btn-cancel">20%</button>
                        </div>

                        <div style="width:100%;margin:4px 2px 2px 2px;height:2rem;padding:5px" class="input-group">
                            <span style="background: rgba(133, 187, 101,.4);" class="input-group-addon">$</span>
                            <input data-bind="value:orderCheckout.tips().amount" id = "txt-tip-amount" class="form-control number-input" type="text" id="custom-tip" name="custom-tip" value="0">
                        </div>
                        <div style="width:100%;text-align;center;margin:5px 1px 1px 1px" >
                            <button style="width:48%;margin:1px;" data-bind="click: updateTip" type="submit" class="btn btn-big btn-primary" id="btn-updateTip" name="btn-updateTip" value="Update Tip">Ok</button>
                            <button style="width:48%;margin:1px;" data-bind="click: cancelTipSelection" type="button"  class="btn btn-big btn-cancel" id="btn-cancelTip" name="btn-cancelTip" value="">Cancel</button>
                        </div>
                    </form>
                </main>
            </div>
        </section>
        <!-- /ko -->

        <section id= "order-bag-container" class="dialog-container hide">
            <div id= "order-bag-dialog" class="dialog">
                <header class="dialog-header">
                    <nav>
                        <span data-bind="click: orderVM.hideOrderBag" class="dialog-close-btn"></span>
                        <div class="dialog-title">
                            <p class="no-margin">My Bag</p>
                        </div>
                    </nav>
                    <div class="dialog-toolbar">
                        <button data-bind="click: orderVM.showOrderCheckout"  type="button" class="btn btn-big btn-2-wide btn-primary pull-left" id="btn-showcheckout" name="btn-showcheckout" value="Show Checkout">Checkout</button>
                        <button data-bind="click: orderVM.hideOrderBag" type="button"  class="btn btn-big btn-2-wide btn-cancel pull-left " id="btn-addmore" name="btn-addmore" value="Add">Add more Food</button>
                        <button data-bind="click: orderVM.clearBag" type="button" class="btn btn-big btn-cancel pull-left" id="btn-emptybag" name="btn-emptybag" value="Empty">Empty Bag</button>
                    </div>
                </header>

                <main>
                    <form id="order-bag-form" class="clear-fix">
                        <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >

                        <div class="form-group sub-group">
                            <h4>Order Summary</h4>

                            <!-- ko component: {name: "bag-summary",params: { orderVM: $root.orderVM}} -->
                            <!-- /ko -->
                        </div>


                        <div class="form-group sub-group">
                            <h4>Order Items</h4>
                            <div id="tbl-orders">
                                <!-- ko foreach:orderVM.orderList -->
                                <div style="height:auto;padding:2px 0px;border-bottom: 1px dotted #ccc">
                                    <div data-bind="click:$root.orderVM.removeItem" style="display:inline-block;width:10%;padding-top:2px;color:#d9534f"> <i class="click-icon fas fa-trash-alt"></i></div>
                                    <div data-bind="click: $root.orderVM.showEditOrderItem" style="display:inline-block;width:10%;padding-top:2px;color:#5cb85c"> <i class="click-icon fas fa-pencil-alt"></i></div>
                                    <div data-bind="text:qty" style="display:inline-block;width:10%">99</div>
                                    <div style="vertical-align:top;display:inline-block;width:42%">
                                        <p data-bind="text:menuItem.itemCode" style="margin:0px">Dal Makhani</p>
                                        <p style="margin:0px">
                                            <small class="muted" data-bind="text:getSelectedOptionsDisplayText" >Mild</small>
                                        </p>
                                        <p style="margin:0px">
                                            <small class="muted" data-bind="visible:getInstructionsDisplayText(), text:getInstructionsDisplayText" >no nuts, No onions please & i need it fast</small>
                                        </p>
                                    </div>
                            <!--
                                    <div data-bind="text:getDisplayAmount"  style="display:inline-block;text-align:right;width:22%">$9999.00</div>
    -->
                                </div>
                                <!-- /ko -->
                            </div>
                        </div>

                    </form>

                </main>

            </div>
        </section>

        <button data-bind="disable:orderVM.isBagEmpty,click: orderVM.showOrderBag"
                class="btn btn-primary" id="btnShowOrderBag"
                style="">
            MY BAG
            <i style="padding-left:5px;font-size:2rem" class="fas fa-shopping-bag"></i>
            <span data-bind="text:orderVM.itemCount" style="position:relative;font-weight:bold;color:darkred;top:.1rem;left:-1.4rem;">0</span>
        </button>

        <!-- ko with: $root.orderVM-->

        <section id="order-checkout-container" class="dialog-container hide">
        <div id="order-checkout-dialog" class="dialog" >
            <header class="dialog-header">
                <nav>
                    <span data-bind="click:hideOrderCheckout" class="dialog-close-btn"></span>
                    <div class="dialog-title">
                        <p class="no-margin">Checkout</p>
                    </div>
                </nav>

                <div class="dialog-toolbar">
                    <button data-bind="click: placeOrder" type="button" class="btn btn-big btn-wide btn-primary pull-left" id="btn-checkout" name="btn-checkout" value="Checkout">Place your Order</button>
                    <button data-bind="click: hideOrderCheckout" type="button" class="btn btn-big btn-2-wide btn-cancel pull-left " id="btn-addmore" name="btn-addmore" value="Add">Add more Food</button>
                </div>
            </header>
            <main>
                <form id="order-checkout-form" class="clear-fix">
                    <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >

                    <div class="form-group sub-group" >
                        <h4>Order Summary</h4>

                        <!-- ko component: {name: "bag-summary",params: { orderVM: $root.orderVM}} -->
                        <!-- /ko -->

                    </div>


                    <div class="form-group sub-group" >
                        <h4>Order Type</h4>

                        <label style="width:auto" class="radio-inline">
                            <input data-bind="checked:orderCheckout.orderTypeId, checkedValue:1" type="radio" name="order-type" id="order-type-takeout" value="1">
                            <span>Take Out</span>
                        </label>
                        <!--
                        <label style="width:auto" class="radio-inline">
                            <input data-bind="checked:orderCheckout.orderType, checkedValue:2" type="radio" name="order-type" id="order-type-delivery" value="2">
                            <span>Delivery</span>
                        </label>
                        -->
                        <label style="width:auto" class="radio-inline">
                            <input data-bind="checked:orderCheckout.orderTypeId, checkedValue:3" type="radio" name="order-type" id="order-type-dinein" value="3">
                            <span>Express Dine In</span>
                        </label>
                    </div>

                    <div id="customer-section" class="container-flex sub-group">
                        <h4>Customer Information</h4>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="sr-only" for="first-name">First Name</label>
                                <input data-bind="value:orderCheckout.customerFirstName" type="text" class="form-control" id="first-name" name="first-name" aria-describedby="cellphoneHelp" placeholder="First Name" value="">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="sr-only" for="last-name">Last Name</label>
                                <input data-bind="value:orderCheckout.customerLastName" type="text" class="form-control" id="last-name" name="last-name" aria-describedby="lastnameHelp" placeholder="Last Name" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6 ">
                                <label class="sr-only" for="email-id">Email address</label>
                                <input data-bind="value:orderCheckout.customerEmailId" type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp" placeholder="Email address" value="">
                            </div>

                            <div class="form-group col-sm-6 ">
                                <label class="sr-only" for="cell-phone">Cell Phone#</label>
                                <input data-bind="value:orderCheckout.customerCellPhone" type="text" class="form-control" id="cell-phone" name="cell-phone" aria-describedby="cellphoneHelp" placeholder="Cell phone#" value="">
                            </div>
                        </div>

                    </div>

                    <div id="order-section" class="container-flex sub-group">
                        <h4>Order Information</h4>
                            <div class="row">
                                 <div class="form-group col-xs-6">
                                    <label class="sr-only" for="order-request-day">Request Day</label>
                                    <select data-bind="options:orderCheckout.getAvailableDates ,optionsText:'displayDate',value: orderCheckout.requestDate" class="form-control" id="order-request-day" name="order-request-day" aria-describedby="order-request-dayHelp"> </select>
                                </div>
                                 <div class="form-group col-xs-6">
                                    <label class="sr-only" for="order-request-time">Request Time</label>
                                    <select data-bind="optionsCaption: '--Request Time--*',options:orderCheckout.getAvailableHours ,value: orderCheckout.requestTime" class="form-control" id="order-request-time" name="order-request-time" aria-describedby="order-request-timeHelp"></select>
                                </div>
                             </div>

<!--
                            <h4>Express Dine In</h4>
    -->
                            <div data-bind="visible:orderCheckout.isDineIn()" class="row">
                                <div class="form-group  col-xs-12">
                                    <label class="sr-only" for="dinein-type">Dine in Occasion</label>
                                    <select data-bind="optionsCaption: '--Select Dine-in Occasion--',options:$root.menu.getReserveOccasions ,optionsValue:'occasionId',optionsText:'occasionCode',value: orderCheckout.reserveOccasionId" class="form-control" id="dinein-occasion" name="dinein-occasion" aria-describedby="dinein-occasionHelp"></select>
                                </div>
                            </div>

                            <div data-bind="visible:orderCheckout.isDineIn()" class="row">
                                <div class="form-group  col-xs-6">
                                    <label class="sr-only" for="reservation-name">Reservation Name</label>
                                    <input data-bind="value:orderCheckout.reservationName" type="text" class="form-control" id="reservation-name" name="reservation-name" aria-describedby="reservationNameHelp" placeholder="Reservation Name" value="">
                                </div>
                                <div class="form-group  col-xs-6">
                                    <label class="sr-only" for="guest-count">Guest Count</label>
                                    <input data-bind="value:orderCheckout.guestCount" type="text" class="form-control number-input" id="guest-count" name="guest-count" aria-describedby="guest-countHelp" placeholder="Dine-in Guest Count*" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="sr-only" for="special-instructions">Special Instructions</label>
                                <textarea data-bind="value:orderCheckout.instructions" class="form-control" rows="4" id="special-instructions" name="special-instructions"
                                          aria-describedby="specialinstructionsHelp"
                                          placeholder="Special Instructions - Extra condiments ? Make my cry spicy ? Additional Dietary restrictions? Let us know here"></textarea>
                            </div>
                        </div>
                    <div id="billing-section" class="container-flex sub-group">
                        <h4>Payment Type</h4>
                        <div class="form-group" >
                            <label style="width:auto" class="radio-inline">
                                <input data-bind="checked:orderCheckout.paymentTypeId, checkedValue:1"  type="radio" name="payment-type" id="payment-type-cash" value="1">
                                <span>Pay at Store</span>
                            </label>
                            <label style="width:auto" class="radio-inline">
                                <input data-bind="checked:orderCheckout.paymentTypeId, checkedValue:2" type="radio" name="payment-type" id="payment-type-cc" value="2">
                                <span>Credit Card</span>
                            </label>
                        </div>
                        <div id="credit-card-section" data-bind="visible:orderCheckout.isCreditCardPayment">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label class="sr-only" for="name-on-card">Name on Card</label>
                                        <input type="text" data-value-missing=”xxxx” required autocomplete="name"
                                               class="form-control" id="name-on-card" name="name-on-card" placeholder="Cardholder Name" aria-describedby="name-on-cardHelp">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label class="sr-only" for="street">Street</label>
                                        <input type="text" required autocomplete="address-line1"
                                               class="form-control" id="street" name="street" placeholder="Street Address. e.g 100 Main Street" aria-describedby="streetHelp">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label class="sr-only" for="zipcode">Zip Code</label>
                                        <input type="text" required autocomplete="postal-code"
                                               class="form-control" id="zipcode" name="zipcode" placeholder="Zip" aria-describedby="zipcodeHelp">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="sr-only" for="card-number">Card Number</label>
                                        <div class='cc-field' id="card-number"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="sr-only" for="card-expiration">Expiration</label>
                                        <div class='cc-field' id="card-expiration"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="sr-only" for="card-cvc">CVC</label>
                                        <div class='cc-field' id="card-cvc"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="cc-error" role="alert">
                                <span class="message"></span>
                            </div>
                         </div>
                    </div>
                </form>
            </main>
        </div>
        </section>
        <!-- /ko -->
    </section><!--/.container -->



<?php
    $scripts = ["menu.js.php"];
    require_once "footer-html.php";
?>

