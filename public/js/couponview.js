
function CouponView(couponsViewModel, data) {
    var self = this;

    this.couponsViewModel = couponsViewModel;
    this.customerCouponId = ko.observable(data.customerCouponId);
    this.discountType  = ko.observable(data.discountType);
    this.discountValue = ko.observable(data.discountValue);
    this.customerFirstName = ko.observable(data.customerFirstName);
    this.customerLastName = ko.observable(data.customerLastName);
    this.startDate = ko.observable(convertISO8601toDate(data.startDate));
    this.expirationDate = ko.observable(convertISO8601toDate(data.expirationDate));

    this.getCustomerDisplayName = ko.computed(function() {
        return self.customerLastName() + ',' + self.customerFirstName()
    },this);

    this.getDisplayReservationNumber = ko.computed(function() {
        return '#' + self.reservationId();
    },this);

    this.getDisplayOccasionType = ko.computed(function() {
        if (self.occasionId())
            return self.reservationsViewModel.getOccasionTypeCode(self.occasionId());
        else
            return 'Not specified';

    },this);

    this.getDisplayGuestCount = ko.computed(function() {
        return self.guestCount() + ' Guests';
    },this);

    this.hasOrder = ko.computed(function() {
        return this.orderHeaderId();
    },this);

    this.getDisplayRequestDate = ko.computed(function() {
        return formatDate(self.requestDate());
    },this);

    this.orderURL   = ko.observable("orderview?orderId=" + self.orderHeaderId());
    this.reservationURL   = ko.observable("reservationview?reservationId=" + self.reservationId());
}

function CouponsViewModel() {
    var self = this;
    this.periods = ['Any Date','1 Week','2 Weeks','1 Month', '3 Months','6 Months','1 Year'];
    this.periodTypes = ['and Newer','and Older'];

    this.selPeriod = ko.observable('Any Date');
    this.selPeriodType = ko.observable('and Newer');
    this.paginationToken = ko.observable('');

    this.coupons = ko.observableArray([]);

    this.getPeriods = ko.computed(function() {
        return self.periods;
    },this);

    this.getPeriodTypes = ko.computed(function() {
        return self.periodTypes;
    },this);


    this.search = function (reset) {
        var startDate = '';
        var endDate = '';
        var resetCurrentList = reset ;

        if (!self.paginationToken()) {
            var dateRange = getDateRange(self.selPeriod(),self.selPeriodType());
            startDate = dateRange.startDate;
            endDate = dateRange.endDate;
        }

        $.ajax({
            type: "GET",
            async:false,
            url: 'rest-api/coupons.php',
            resetCurrentList : resetCurrentList,
            headers: {
                'paginationToken':self.paginationToken(),
            },
            data: {
                startDate: startDate,expirationDate:endDate
            },
            success: function (coupons, textStatus, xhr) {
                self.paginationToken(xhr.getResponseHeader('paginationToken'));
                var mappedCoupons = $.map(coupons, function(coupon) {
                    return new CouponView(self,coupon);
                });

                if (this.resetCurrentList)
                    self.coupons(mappedCoupons);
                else
                    self.coupons(self.coupons().concat(mappedCoupons));
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