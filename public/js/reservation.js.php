$(function() {
    var rvm = new ReservationsViewModel();
    getOccasions(rvm.notifyOccasionsLoad);
    ko.applyBindings(rvm);
    rvm.search(true);

});