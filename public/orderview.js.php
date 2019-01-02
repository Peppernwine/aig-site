<script type="text/javascript">
    $(function() {
        var ovm = new OrdersViewModel();
        ko.applyBindings(ovm);
        ovm.search(true);
    });
</script>