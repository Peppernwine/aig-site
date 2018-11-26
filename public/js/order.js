function OrderItem(menuItem,uniqueId) {
    var self = this;

    if (uniqueId)
        this.uniqueId = uniqueId;
    else
        this.uniqueId ='';

    this.setUniqueId =  function() {
        if (!self.uniqueId)
            self.uniqueId = getUniqueId();
    };

    this.isNew =  function() {
        return self.uniqueId === '';
    };

    this._selectedOptions = ko.observable([]);

    this.selectedOptions = ko.computed({
        read: function () {
            return this._selectedOptions();
        },
        write: function (selectKey) {

            var newArray = [];
            if (selectKey) {
                newArray = this._selectedOptions();
                var idx = newArray.findIndex(function (opt) {return opt.optionId === selectKey.optionId});
                if (idx >= 0)
                    newArray.splice(idx, 1);

                 newArray.push(selectKey);
            };
            this._selectedOptions(newArray);
        },
        owner: this
    });

    this.menuItem = menuItem;//ko.observable(menuItem);

    this.qty = ko.observable(1).extend({numeric: 0});

    this.instructions = ko.observable('');

    this.getBasePrice = function () {
        return self.menuItem.getBasePrice();
    };

    this.getBaseDisplayPrice = ko.computed(function () {
        return self.menuItem.getBaseDisplayPrice();
    }, this);

    this.getPrice = function () {
        var price = self.menuItem.basePrice;

        for (var index = 0; index < self.menuItem.options.length; index++) {
            var miOption = self.menuItem.options[index];
            if (miOption.option.hasAdditionalCost()) {
                var selOption = self.selectedOptions().find(function(selOption){return selOption.optionId === miOption.option.optionId });
                if (selOption) {
                    price += self.menuItem.getOptionPriceDelta(selOption.optionId,selOption.optionChoiceId);
                }
            }
        }
        return price;
    }

    this.getAmount = function () {
        var price = self.getPrice();

        var amount = price * self.qty();

        amount = Math.round(amount*100)/100;

        return amount;
    };

    this.getDisplayAmount = ko.computed(function () {
        return "$" + self.getAmount();
    },this);


    this.getEditButtonDisplayText = function () {
        if (self.isNew()) {
            return "Add To Bag - " + self.getDisplayAmount();
        } else
            return "Update Bag - " + self.getDisplayAmount();
    };

    this.getSelectedOptionsText = function () {
        var selOptionText = "";
        for (var index = 0; index < self.menuItem.options.length; index++) {
            var miOption = self.menuItem.options[index];
            var selOption = self.selectedOptions().find(
                function (selOption) {
                    return selOption.optionId === miOption.option.optionId
                }
            );
            if (selOption) {
                if (selOptionText == "")
                    selOptionText = self.menuItem.getOptionChoiceCode(selOption.optionId, selOption.optionChoiceId);
                else
                    selOptionText = selOptionText + '/' + self.menuItem.getOptionChoiceCode(selOption.optionId, selOption.optionChoiceId);
            }
        }

        return selOptionText;
    }

    this.getSelectedOptionsDisplayText = ko.computed(function () {
        var selOptionText = self.getSelectedOptionsText();
        if (selOptionText) selOptionText = 'Options : ' + selOptionText;
        return selOptionText;
    },this) ;

    this.getInstructionsDisplayText = ko.computed(function () {
        var notes = self.instructions();
        if (notes && notes != '')
            return 'Notes:' + notes ;
        else
            return null;
    },this);

    this.incrementQty = function () {
        self.qty(self.qty() + 1);
    };

    this.decrementQty = function () {
        if (self.qty() > 1)
            self.qty(self.qty() - 1);
    };

    this.canDecrementQty = ko.computed(function () {
        return self.qty() > 1
    }, this);

    this.canDecrementQty = ko.computed(function () {
        return self.qty() > 1
    }, this);

    this.hasRequiredOptions =  function() {
        for (var index = 0; index < this.menuItem.options.length; index++) {
            var miOption = this.menuItem.options[index];
            if (miOption.option.isRequired) {
                var selOption = this.selectedOptions().find(function(selOption){return selOption.optionId === miOption.option.optionId });
                if (!selOption)
                    return false;
            }
        }
        return true;
    };

    this.isValid = ko.computed(function () {
        return self.qty() > 0 && self.hasRequiredOptions();
    }, this);

    this.getData = function () {
        return {"uniqueId":self.uniqueId,"menuItemId":self.menuItem.itemId,
                "menuItemCode":self.menuItem.itemCode,"price":self.getPrice(),
                "qty":self.qty(),"amount":self.getAmount(),"instructions":self.instructions(),
                "options":self._selectedOptions(),"optionsText":self.getSelectedOptionsText()};
    }
//,,
//

    /*
    defaults for testing...
    var og1 = menuItem.getOptionChoice(1, 2);
    if (og1)
        this.selectedOptions(og1.selectKey);

    var og2 = menuItem.getOptionChoice(2, 5);
    if (og2)
        this.selectedOptions(og2.selectKey);

        */
}

function SalesTax(orderVM) {
    var self = this;
    this.orderVM = orderVM;
    this.getAmount = function () {
            var tax = self.orderVM.subTotal() * .0635;
            tax = Math.round(tax*100)/100;
            return tax;
        };

    this.getDisplayAmount = ko.computed(
        function () {
            return '$' + self.getAmount().toFixed(2);
        }, this);
}

function Tips(orderCheckout)  {
    var self = this;

    this.orderCheckout = orderCheckout;

    this._amount = ko.observable(null).extend({numeric: 2});
    this.amount = ko.computed({
        read: function () {
            var amt = self._amount();
            if (!amt) amt = 0;
            return amt;
        },
        write: function (value) {
            self._amount(value);
        },
        owner: this
    });

    this.getDisplayAmount = ko.computed( function() {
        var amt = self.amount();
        if (!amt) amt = 0;
        return '$' + amt.toFixed(2);
    },this);

    this.calculatePC = function(data,e) {

        var value = parseInt(e.target.value);

        var amt = self.orderCheckout.subTotal() * (value/100);
        amt = Math.round(amt*100)/100;
        self.amount(amt);

    }
}

function OrderHoursViewModel(location) {
    this.self = this;

    this.availableDates = [];

    var weekday = new Array(7);
    weekday[0] = "Sun";
    weekday[1] = "Mon";
    weekday[2] = "Tue";
    weekday[3] = "Wed";
    weekday[4] = "Thu";
    weekday[5] = "Fri";
    weekday[6] = "Sat";

    var opsDaySchedule;
    var availableDate;
    var endDate = new Date();
    endDate.setDate(endDate.getDate() + 8);
    var currTime = new Date();
    for (var d = new Date() ; d <= endDate; d.setDate(d.getDate() + 1)) {

        availableDate = {};

        opsDaySchedule = location.opsSchedule.find(function(sch){ return sch.weekDay === d.getDay() } );

        if (opsDaySchedule && opsDaySchedule.shifts && opsDaySchedule.shifts.length > 0) {
            var s;

            if (d.getDay() == currTime.getDay() && d.getMonth() == currTime.getMonth() && d.getDate() == currTime.getDate())
                s = 'Today ' + (d.getMonth() + 1) + '/' + d.getDate() ;
            else
                s = weekday[d.getDay()] + ' ' + (d.getMonth() + 1) + '/' + d.getDate() ;

            availableDate.date = new Date(d);
            availableDate.displayDate = s;
            availableDate.weekDay = d.getDay();
            availableDate.hours = [];
            this.availableDates.push(availableDate);
        }
    }

    var hours;

    for (var i = 0; i < this.availableDates.length;i++) {

        availableDate = this.availableDates[i];

        opsDaySchedule = location.opsSchedule.find(function(sch){ return sch.weekDay === availableDate.weekDay } );

        hours = [];

        if (availableDate.date.getDay() == currTime.getDay() && availableDate.date.getMonth() == currTime.getMonth()
                                                             && availableDate.date.getDate() == currTime.getDate()) {
            hours.push('ASAP');
        }

        for (var j = 0; j < opsDaySchedule.shifts.length;j++) {

           var startTime = (opsDaySchedule.shifts[j].startHour * 60) + opsDaySchedule.shifts[j].startMinute;
           var endTime = (opsDaySchedule.shifts[j].endHour * 60) + opsDaySchedule.shifts[j].endMinute;


           if (availableDate.date.getDay() == currTime.getDay() && availableDate.date.getMonth() == currTime.getMonth()
                                                                && availableDate.date.getDate() == currTime.getDate()) {
               var currentTimeMins = (currTime.getHours() * 60)  + currTime.getMinutes();
               while (startTime  <= endTime && startTime < currentTimeMins)
                   startTime += 5;
           }

            for (var mins = startTime ; mins <= endTime;mins += 5) {

                var hh   = Math.floor( mins/ 60);
                var mm = mins % 60;
                var am_pm = '';

                if (hh >= 12) {
                    hh = hh - 12;
                    am_pm = "PM";
                } else
                    am_pm = "AM";

                if (hh == 0) {
                    hh = 12;
                }

                mm = mm < 10 ? "0" + mm : mm;

                hours.push(hh + ':' + mm + ' ' + am_pm);
           }
        }
        availableDate.hours = hours;
    }
}


function OrderCheckoutViewModel(orderVM,checkoutDefaults) {
    var self = this;

    this.checkoutDefaults = checkoutDefaults;

    this.orderVM = orderVM;
    this.orderHours = new OrderHoursViewModel(new LocationMaster());
    this.reserveOccasions = [];

    self.subTotal = ko.observable(0);

    self.requestDate = ko.observable(self.orderHours.availableDates[0]);//;
    self.requestTime = ko.observable(self.orderHours.availableDates[0].hours[0]);//();

    self.orderTypeId = ko.observable(1);

    self.customerId = ko.observable(null);
    self.customerFirstName = ko.observable(null);
    self.customerLastName = ko.observable(null);
    self.customerEmailId = ko.observable(null);
    self.customerCellPhone = ko.observable(null);

    self.reservationName = ko.observable(null);
    self.reserveOccasionId = ko.observable(null);
    self.guestCount = ko.observable(null).extend({numeric: 0});

    self.instructions = ko.observable(null);

    self.paymentTypeId = ko.observable(null);

    this.tips = ko.observable(new Tips(this));
    this.tipsCopy = new Tips(this);

    this.setTips = function (value) {
        self.tips().amount(value);
    };

    this.setDefaults = function () {
        self.subTotal(0);

        self.requestDate(self.orderHours.availableDates[0]);//;
        self.requestTime(self.orderHours.availableDates[0].hours[0]);//();

        self.orderTypeId = ko.observable(1);

        self.customerId(null);
        self.customerFirstName (null);
        self.customerLastName (null);
        self.customerEmailId(null);
        self.customerCellPhone(null);

        self.reservationName(null);
        self.reserveOccasionId(null);
        self.guestCount(0);

        self.instructions(null);

        self.paymentTypeId(1);

        if (self.checkoutDefaults) {
            self.customerId(checkoutDefaults.customerId);
            self.customerFirstName(checkoutDefaults.customerFirstName);
            self.customerLastName(checkoutDefaults.customerLastName);
            self.customerEmailId(checkoutDefaults.customerEmailId);
            self.customerCellPhone(checkoutDefaults.customerCellPhone);
        }

        self.setTips(0);
    }

    this.setDefaults();

    this.salesTax = ko.observable(new SalesTax(this));

    this.notifyOccasionsLoad = function (occasions) {
        self.reserveOccasions = occasions;
    }

    this.total = ko.computed(function () {
        return self.subTotal() + self.salesTax().getAmount() + self.tips().amount();
    }, this);


    this.backupTips = function () {
        self.tipsCopy.amount(self.tips().amount());
    };

    this.restoreTips = function () {
        self.tips().amount(self.tipsCopy.amount());
    };

    this.getDisplaySubTotal = ko.computed(function () {
        return '$' + self.subTotal().toFixed(2);
    }, this);

    this.getDisplayTotal = ko.computed(function () {
        return '$' + self.total().toFixed(2);
    }, this);


    this.calculateSubTotal = function () {
        var subTotal = 0;
        for (var idx = 0; idx < self.orderVM.orderList().length; idx++) {
            var orderItem = self.orderVM.orderList()[idx];
            subTotal += orderItem.getAmount();
        }
        self.subTotal(subTotal);
    };

    this.getAvailableDates = ko.computed(function () {
            return self.orderHours.availableDates;
        }
        , this);

    this.getAvailableHours = ko.computed(function () {
        if (self.requestDate())
            return self.requestDate().hours;
        else
            return [];
    }, this);

    this.isCreditCardPayment = ko.computed(function () {
        return (self.paymentTypeId() == 2);
    }, this);

    this.isDineIn = ko.computed(function () {
        return (self.orderTypeId() == 3);
    }, this);

    this.stripePayment = null;
    this.initializeCreditCardSection = function () {
        'use strict';

        var elementStyles = {
            base: {
                color: '#32325D',
                fontWeight: 400,
                fontFamily: 'lato-reg-webfont,Lato, sans-serif',
                fontSize: '14px',
                fontSmoothing: 'antialiased',
                '::placeholder': {
                    color: '#a9a9a9', //'#CFD7DF',
                },
                ':-webkit-autofill': {
                    color: '#e39f48',
                },
            },
            invalid: {
                color: '#E25950',

                '::placeholder': {
                    color: '#FFCCA5',
                },
            },
        };

        var elementClasses = {
            focus: 'focused',
            empty: 'empty',
            invalid: 'invalid',
        };

        self.stripePayment = new StripePayment({
            fonts: [{cssSrc: "css/styles.min.css"}],
            elementStyles: elementStyles,
            elementClasses: elementClasses,
            ccSection: "#credit-card-section",
            form: "#order-checkout-form"
        });
    }

    this.initializeCreditCardSection();

    this.getData = function () {
        return {"orderTypeId" : self.orderTypeId(),"tips" : self.tips().amount(),"paymentTypeId" : self.paymentTypeId(),
         "requestDate" : ISOFormatDate(self.requestDate().date),"requestTime" : self.requestTime(),
         "customerId" : self.customerId(),"customerFirstName" : self.customerFirstName(),
         "customerLastName" : self.customerLastName(),"customerEmailId" : self.customerEmailId(),
         "customerCellPhone" : self.customerCellPhone(),'reservationName': self.reservationName(),
         "reserveOccasionId" : self.reserveOccasionId(),'guestCount':self.guestCount(),
         "instructions" :self.instructions()
        }
    }

    function showSuccess(data){
        var primaryMsg = "Thanks for your Order. Your Order# is " + data.order.orderHeaderId ;
        var secMsg = data.message;

        var msgContent =
            "<div style='text-align: center;margin:10px' class='alert alert-success' role='alert'> " +
                "<span class='glyphicon glyphicon-ok-sign'></span> " + primaryMsg +
                "<div>" +
                    "<a target='_blank' href=" + data.location + ">Click here for Order details</a>" +
                "</div>" +
            "</div>";

        if (secMsg && secMsg != '') {
            msgContent +=
                          "<div style='text-align: center;margin:10px' class='alert alert-warning' role='alert'> " +
                           "<span class='glyphicon glyphicon-warning-sign'></span> " + secMsg +
                          "</div>" ;
        }

        bootbox.alert(msgContent);
    }

    function stripeResponseHandler(response,params) {

        if (!response.success) {
            params.dialog.modal('hide');
            showError(response.message);
            return;
        }

        params.data.cctoken = response.token;
        //PROCESS/POST response.token
        postOrder(params);
    }

    function postOrder(params) {

       var strData = JSON.stringify(params.data);
        $.ajax({
            "method": "POST",
            "async":true,
            "url": "rest-api/shoppingbag/checkout",
            "data": strData,
            "contentType": 'application/json;charset=UTF-8',
            "success": function (data) {
                params.dialog.modal('hide');
                showSuccess(data);
                params.orderComplete(data);
            },
            "error":function(error) {
                params.dialog.modal('hide');
                showError(error.responseJSON.message);
            }
        });
    }


    this.placeOrder = function(orderComplete) {
        var checkoutData = self.getData();

        var popup = bootbox.dialog({
            title: 'Submitting Order',
            closeButton:false,
            message: '<p style="text-align: center"><i style="color:green" class="fa fa-spin fa-spinner"></i>Placing Order</p>'
        });

        popup.init(function(){
            var msg = 'Sending order to Restaurant. Please wait...';
            if (self.isCreditCardPayment()) {
                msg = 'Charging Credit card & sending order to Restaurant. Please wait..';
            }
             popup.find('.bootbox-body').html('<p style="text-align: center"><i style="color:green;padding:5px" class="fa fa-spin fa-spinner"></i>' + msg +'</p>');

            if (self.isCreditCardPayment())
                self.stripePayment.processPayment(stripeResponseHandler,{dialog:popup,data:checkoutData,orderComplete:orderComplete});
            else
                postOrder({dialog:popup,data:checkoutData,orderComplete:orderComplete});

        });
    }
}

function OrderViewModel(menu,checkoutDefaults) {
    var self = this;
    this.menu = menu;
    var menuItem = null;

    if (menu.items.length > 0) menuItem = menu.items[0];

    this.currentOrderItem = ko.observable(null);
    this.currentOrderItemRawCopy = null;

    this.orderList = ko.observableArray(null);

    this.itemCount = ko.computed(function(){
                return self.orderList().length
            },this);
    this.orderCheckout = new OrderCheckoutViewModel(this,checkoutDefaults);

    getOccasions(this.orderCheckout.notifyOccasionsLoad);

    this.loadFromSessionBag = function () {

        $.ajax({
            type: "GET",
            async:false,
            url: "rest-api/shoppingbag",
            dataType : 'json',
            contentType: 'application/json;charset=UTF-8',
            success: function (data) {
                if (!data) return;
                self.orderCheckout.setTips(data.tips);
                self.orderCheckout.instructions(data.instructions);
                if (data.paymentTypeId) self.orderCheckout.paymentTypeId(data.paymentTypeId);
                if (data.orderTypeId) self.orderCheckout.orderTypeId(data.orderTypeId);
                for (var index = 0; index < data.items.length; index++) {
                    var sessionItem = data.items[index];
                    var menuItem = self.menu.getItem(sessionItem.menuItemId);
                    var item = new OrderItem(menuItem,sessionItem.uniqueId);
                    item._selectedOptions(sessionItem.options);
                    item.instructions(sessionItem.instructions);
                    self.orderList.push(item);
                }
                self.calculateSubTotal();

            },
            error:function(error) {
                showError('System Error - Unable to fetch Bag');
            }
        });
    }

    this.addToSessionBag = function (orderItem) {
        var orderItemData = JSON.stringify(orderItem.getData());

        $.ajax({
            type: "POST",
            async:false,
            url: "rest-api/shoppingbag",
            data: orderItemData,
            contentType: 'application/json;charset=UTF-8',
            success: function () {
                //alert('Success - Item was added to your Bag');
            },
            error:function(error) {
                showError('System Error - Item was not added to your Bag');
            }
        });
    }

    this.updateSessionBag = function (orderItem) {
        var orderItemData = JSON.stringify(orderItem.getData());

        $.ajax({
            "type": "PUT",
            "async":false,
            "url": 'rest-api/shoppingbag/' + orderItem.uniqueId,
            "data": orderItemData ,
            "contentType": 'application/json;charset=UTF-8',
            "success": function () {
                                    //alert('Success - Item in your Bag was updated ');
                                 },
            "error":function(error) {
                showError('System Error - Item in your Bag could not be updated');
                                  }
        })
    }

    this.deleteFromSessionBag = function (orderItem) {
        var orderItemData = orderItem.getData();

        $.ajax({
            "type": "DELETE",
            "async":false,
            "url": 'rest-api/shoppingbag/' + orderItem.uniqueId,
            "data": {} ,
            "success" : function () {
                                        //alert('Success - Item was removed from your Bag');
                                    },
            "error" : function(error) {

                                        showError('System Error - Item in your Bag could not be removed');
                                       }
            });
    }

    this.clearSessionBag = function () {

        $.ajax({
            "type": "DELETE",
            "async":false,
            "url": 'rest-api/shoppingbag',
            "success": function () {
                                     //alert('Success - Your bag was cleared');
                                 },
            "error" : function(error) {
                           showError('System Error - Your Bag could not be cleared');
            }
        })

    }

    this.addItem = function() {
        self.currentOrderItem().setUniqueId();
        self.addToSessionBag(self.currentOrderItem());
        self.orderList.push(self.currentOrderItem());
        self.currentOrderItem(null);
        self.calculateSubTotal();
        self.hideOrderItemSelection();
    };

    this.updateItem = function() {
        self.updateSessionBag(self.currentOrderItem());
        self.calculateSubTotal();
        self.currentOrderItem(null);
        self.hideOrderItemSelection();
    };


    this.removeItem = function (item) {
        bootbox.confirm({
            title:"Remove Item ?",
            message: "You are trying to remove " + item.menuItem.itemName + " from your Bag. Are you sure?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    self.deleteFromSessionBag(item);
                    self.orderList.remove(item);
                    self.calculateSubTotal();
                    if (self.isBagEmpty())
                        self.hideOrderBag();
                }
            }
        });
    };

    this.isBagEmpty = ko.computed(function () {
        return self.orderList().length === 0;
    }, this);


    this.clearBag = function () {
        bootbox.confirm({
            title:"Empty your Shopping Bag ?",
            message: "You have " + self.itemCount() + " item(s) in your Bag. Are you sure you want to clear your Bag ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    self.clearSessionBag();
                    self.clear();
                    self.hideOrderBag();
                }
            }
        });
    };

    this.clear = function () {
        self.orderList.removeAll();
        self.orderCheckout.setDefaults();
        self.calculateSubTotal();
    }

    this.calculateSubTotal = function () {
        self.orderCheckout.calculateSubTotal();
    };

    this.getEditButtonDisplayText = ko.computed(function () {
        if (self.currentOrderItem())
            return self.currentOrderItem().getEditButtonDisplayText();
        else
            return 'Add';
    },this);

    /*
    this.hideOrderSummary = function () {
        $('.order-summary').hide();
        $('.order-summary-icon').css('display', 'inline-block');
    }

    this.showOrderSummary = function () {
        $('.order-summary').show();
        $('.order-summary-icon').hide();
    }

*/


    this.showOrderBag = function () {
        disableBodyScroll();
        $('#order-bag-container').removeClass("hide");
        $('#order-bag-container').addClass("show");
    }

    this.hideOrderBag = function() {
        reEnableBodyScroll();
        $('#order-bag-container').removeClass("show");
        $('#order-bag-container').addClass("hide");
    }

    this.showOrderCheckout = function () {
        self.hideOrderBag();
        disableBodyScroll();
        $('#order-checkout-container').removeClass("hide");
        $('#order-checkout-container').addClass("show");
    }

    this.hideOrderCheckout = function () {
        reEnableBodyScroll();
        $('#order-checkout-container').removeClass("show");
        $('#order-checkout-container').addClass("hide");
    }

    this.hideOrderItemSelection = function () {
        reEnableBodyScroll();
        $('#order-selection-container').fadeOut(500).removeClass("show").addClass("hide");
    };


    this.showOrderItemSelection = function () {
        disableBodyScroll();
        $('#order-selection-container').fadeIn(500).removeClass("hide").addClass("show");
    };

    this.showNewOrderItem = function (menuItem) {
        self.currentOrderItemRawCopy = null;
        self.currentOrderItem(new OrderItem(menuItem));
       self.showOrderItemSelection();
    };

    this.showEditOrderItem = function (item) {
        self.currentOrderItemRawCopy = ko.toJS(item);
        self.currentOrderItem(item);
        self.showOrderItemSelection();
    };

    this.cancelOrderItemSelection = function () {
        if (!self.currentOrderItem().isNew()) {
            var mapping = {
                'copy': ["menuItem"]
            }
            self.orderList.replace(self.currentOrderItem(), ko.mapping.fromJS(self.currentOrderItemRawCopy,mapping));
        }

        self.currentOrderItem(null);
        self.hideOrderItemSelection();
    };


    this.hideTipSelection = function () {
        $('#tip-selection-container').hide();
    };

    this.cancelTipSelection = function () {
        self.orderCheckout.restoreTips();
        //self.tips().amount(self.tipsCopy.amount());
        self.hideTipSelection();
    };

    this.showTipSelection = function (data, e) {

        $('#tip-selection-dialog').css({left:'-400px',top:'-400px'});

        $('#tip-selection-container').show('fast',
            function() {

                var contHeight = $('#tip-selection-container').height();
                var contWidth = $('#tip-selection-container').width();

                var height = $('#tip-selection-dialog').height();
                var width = $('#tip-selection-dialog').width();

                var leftVal = (contWidth - width)/2 + "px";
                var topVal = (contHeight - height)/2 + "px";


                $('#tip-selection-dialog').css({left:leftVal,top:topVal});
                $('#txt-tip-amount').focus().select();
            }
        );

        self.orderCheckout.backupTips();
        //self.tipsCopy.amount(self.tips().amount());
  };

    this.updateTip = function () {
        self.hideTipSelection();
    };

    this.orderComplete = function() {
        self.clear();
        self.hideOrderCheckout();
    }

    this.placeOrder = function() {
        self.orderCheckout.placeOrder(self.orderComplete);
    };


    allowNumbersOnly('#order-selection-container #quantity.number-input');
    allowNumbersOnly('#tip-selection-container #custom-tip.number-input');
    allowNumbersOnly('#guest-count.number-input');

}
