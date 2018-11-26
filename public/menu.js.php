<script src="https://js.stripe.com/v3/"></script>
<script type="application/javascript">

    var menu = null;
    var mv = null;
    var ovm = null;

    $(function() {

        ko.components.register('bag-summary', {
            template:
            '<div class="charges"> ' +
            ' <div class="tbl-order-totals" > ' +
            ' <div> ' +
            ' <div class="charge-summary-label">Subtotal</div> ' +
            ' <div class="charge-summary-value" data-bind="text:orderVM.orderCheckout.getDisplaySubTotal" >$1000.00</div> ' +
            ' </div> ' +

            ' <div> ' +
            ' <div class="charge-summary-label">Sales Tax(6.35%)</div> ' +
            ' <div class="charge-summary-value"  data-bind="text:orderVM.orderCheckout.salesTax().getDisplayAmount">$62.50</div> ' +
            ' </div> ' +

            ' <div> ' +
            ' <div class="charge-summary-label">Tips <i data-bind="visible:orderVM.orderCheckout.tips().amount() > 0, click: orderVM.showTipSelection" style="padding-left:4px;padding-bottom:4px;color:rgba(183,0,56)"class="click-icon fas fa-pencil-alt"></i></div> ' +
            ' <button data-bind="click: orderVM.showTipSelection,visible:orderVM.orderCheckout.tips().amount() === 0" type="button" class="btn btn-big btn-primary charge-summary-value" >Add Tip </button>' +
            ' <div class="charge-summary-value" data-bind="text:orderVM.orderCheckout.tips().getDisplayAmount,visible:orderVM.orderCheckout.tips().amount() > 0" >$99.50</div> ' +
            ' </div> ' +
            ' <div> ' +
            ' <div class="charge-summary-label">Total</div> ' +
            ' <div class="charge-summary-value" data-bind="text:orderVM.orderCheckout.getDisplayTotal" style="font-weight:800;padding:2px 5px;display: inline-block;width:5rem;margin-left:5%;margin-top:5px;border-top:1px solid #333;border-bottom:1px solid #333;text-align:right">$162.50</div> ' +
            ' </div> ' +
            ' </div> ' +
            ' </div> '
        });

        $.ajax({
            type: "GET",
            async:false,
            url: 'rest-api/shoppingbag/checkout',
            success: function (data) {
            $.ajax({
                    type: "GET",
                    async:false,
                    url: 'rest-api/menu.php',
                    data: {
                typeId: getUrlParam('typeId',1)
                    },
                    success: function (pMenu) {
                menu = pMenu;

                mv = new MenuViewModel(menu);

                ovm = new OrderViewModel(mv.menu,data.checkoutDefaults);
                mv.setOrderViewModel(ovm);

                ko.applyBindings(mv);
                ovm.loadFromSessionBag();

                $('.menu-category h3').click(
                    function() {
                        scrollToCenter('search-menu-item');
                    });

                if (!ovm.isBagEmpty() && getUrlParam('showbag',0) == 1 ) {
                    ovm.showOrderBag();
                }


            }
                });


            }
        });
    });
</script>