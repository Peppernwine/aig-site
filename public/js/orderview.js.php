$(function() {
    var ovm = new OrdersViewModel();
    ko.applyBindings(ovm);
    ovm.search(true);
});