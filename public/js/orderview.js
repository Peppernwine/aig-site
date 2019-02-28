
function OrderViewItem(data) {
    var self = this;
    if (data.optionsText && data.optionsText != '')
        this.menuItem =  ko.observable(data.menuItemCode + '(' + data.optionsText +')') ;
    else
        this.menuItem =  ko.observable(data.menuItemCode)  ;
}

function OrderView(data) {
    var self = this;
    this.orderHeaderId = ko.observable(data.orderHeaderId);
    this.customerFirstName = ko.observable(data.customerFirstName);
    this.customerLastName = ko.observable(data.customerLastName);
    this.requestDate = ko.observable(convertISO8601toDate(data.requestDate));
    this.orderTypeId = ko.observable(data.orderTypeId);
    this.items = ko.observableArray([]);

    var mappedItems = $.map(data.items, function(item) {
        return new OrderViewItem(item);
    });
    this.items(mappedItems);

    this.total = ko.observable(data.total);

    this.getCustomerDisplayName = ko.computed(function() {
        return self.customerLastName() + ',' + self.customerFirstName()
    },this);

    this.getDisplayOrderNumber = ko.computed(function() {
        return '#' + self.orderHeaderId();
    },this);

    this.getDisplayTotal = ko.computed(function() {
        return formatCurrency(self.total());
    },this);

    this.getDisplayOrderType = ko.computed(function() {
        if (self.orderTypeId() == 1)
            return 'Pickup';
        else
            return 'Express Dine-in';
    },this);


    this.getDisplayRequestDate = ko.computed(function() {
        return formatDate(self.requestDate());
    },this);

    this.reOrder = function () {

        var popup = bootbox.dialog({
            title: 'Reordering',
            closeButton:false,
            message: '<p><i style="text-align: center;color:green" class="fa fa-spin fa-spinner"></i>Adding items to Shopping Bag</p>'
        });

        var strData = JSON.stringify({"orderHeaderId": self.orderHeaderId()});

        popup.init(function () {
            $.ajax({
                "type": "POST",
                "async":true,
                "popup":popup,
                "url": 'rest-api/orderbag.php',
                "contentType": 'application/json;charset=UTF-8',
                "data":strData,
                "error": function(error) {
                    popup.modal('hide');
                    showError('System error. Failed to reorder');
                },
                "success": function () {
                    popup.find('.bootbox-body').html('<p style="text-align: center">Items successfully added to Bag.</p>' +
                        '<p style="text-align: center">Redirecting to <a href="menu.php?typeId=1&showbag=1"> Shopping Bag</a> ' +
                        '<i style="color:green;padding:5px" class="fa fa-spin fa-spinner"></i> </p>');

                    setTimeout(function(){
                        popup.modal('hide');
                        window.location.href = 'menu.php?typeId=1&showbag=1';
                    }, 1500);
                }
            });
        });
    }


    this.orderURL   = ko.observable("orderview?orderId=" + self.orderHeaderId());
}

function OrdersViewModel() {
    var self = this;
    this.periods = ['Any Date','1 Week','2 Weeks','1 Month', '3 Months','6 Months','1 Year'];
    this.periodTypes = ['and Newer','and Older'];

    this.selPeriod = ko.observable('Any Date');
    this.selPeriodType = ko.observable('and Newer');
    this.paginationToken = ko.observable('');

    this.orders = ko.observableArray([]);

    this.getPeriods = ko.computed(function() {
        return self.periods;
    },this);

    this.getPeriodTypes = ko.computed(function() {
        return self.periodTypes;
    },this);

    this.search = function (reset) {
        var startDate = '';
        var endDate = '';
        var resetcurrentList = reset ;

        if (!self.paginationToken()) {
            var dateRange = getDateRange(self.selPeriod(),self.selPeriodType());
            startDate = dateRange.startDate;
            endDate = dateRange.endDate;
        }

        $.ajax({
            type: "GET",
            async:false,
            url: 'rest-api/orders.php',
            resetCurrentList : resetcurrentList,
            headers: {
                'paginationToken':self.paginationToken(),
            },
            data: {
                startDate: startDate,endDate:endDate
            },
            success: function (orders, textStatus, xhr) {
                self.paginationToken(xhr.getResponseHeader('paginationToken'));
                var mappedOrders = $.map(orders, function(order) {
                    return new OrderView(order);
                });
                if (this.resetCurrentList)
                    self.orders(mappedOrders);
                else
                    self.orders(self.orders().concat(mappedOrders));
            }
        });
    }

    this.moreDataAvailable = ko.computed(function(){return self.paginationToken()},this);

    this.newSearch = function () {
        self.paginationToken(''); //reset pagination token
        this.search(true);
    }

    this.loadMore = function () {
        this.search(false);
    }
}

