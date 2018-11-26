$(function() {
    var cvm = new CateringViewModel();
    cvm.load();
    ko.applyBindings(cvm);
});