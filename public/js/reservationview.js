
function ReservationView(reservationsViewModel, data) {
    var self = this;

    this.reservationsViewModel = reservationsViewModel;
    this.reservationId = ko.observable(data.reservationId);
    this.orderHeaderId = ko.observable(data.orderHeaderId);
    this.customerFirstName = ko.observable(data.customerFirstName);
    this.customerLastName = ko.observable(data.customerLastName);
    this.requestDate = ko.observable(convertISO8601toDate(data.requestDate));
    this.occasionId = ko.observable(data.occasionId);
    this.guestCount = ko.observable(data.guestCount);

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

function ReservationsViewModel() {
    var self = this;
    this.periods = ['Any Date','1 Week','2 Weeks','1 Month', '3 Months','6 Months','1 Year'];
    this.periodTypes = ['and Newer','and Older'];

    this.selPeriod = ko.observable('Any Date');
    this.selPeriodType = ko.observable('and Newer');
    this.paginationToken = ko.observable('');

    this.reservations = ko.observableArray([]);
    this.occasions = [];

    this.notifyOccasionsLoad = function (occasions) {
        self.occasions = occasions;
    };

    this.getOccasionTypeCode = function (occasionId) {
        var occasion = self.occasions.find(function(occasion) {return occasionId === occasion.occasionId });
        if (occasion)
            return occasion.occasionCode;
        else
            return '';

    }

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
            url: 'rest-api/reservations.php',
            resetCurrentList : resetCurrentList,
            headers: {
                'paginationToken':self.paginationToken(),
            },
            data: {
                startDate: startDate,endDate:endDate
            },
            success: function (reservations, textStatus, xhr) {
                self.paginationToken(xhr.getResponseHeader('paginationToken'));
                var mappedReservations = $.map(reservations, function(reservation) {
                    return new ReservationView(self,reservation);
                });

                if (this.resetCurrentList)
                    self.reservations(mappedReservations);
                else
                    self.reservations(self.reservations().concat(mappedReservations));
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

