function ReferralViewModel(data) {
    var self = this;
    this.customerId = ko.observable(data.customerId);
    this.pendingReward = ko.observable(0);
    this.availableReward = ko.observable(0);
    this.recipientEmailId = ko.observable("");

    this.getDisplayPendingReward = ko.computed(function() {
        return formatCurrency(self.pendingReward());
    },this);

    this.getDisplayAvailableReward = ko.computed(function() {
        return formatCurrency(self.availableReward());
    },this);

    this.isValidRecipientEmail = function() {
        return self.recipientEmailId() != "";
    };

    this.sendInvite = function () {

        var popup = bootbox.dialog({
            title: 'Sending Invite',
            closeButton:false,
            message: '<p><i style="text-align: center;color:green" class="fa fa-spin fa-spinner"></i>Sending Invite</p>'
        });

        var referralData = JSON.stringify({"customerId":self.customerId(),"recipient": self.recipientEmailId()});

        popup.init(function () {
            $.ajax({
                "type": "POST",
                "async":true,
                "popup":popup,
                "url": 'rest-api/referral.php',
                "contentType": 'application/json;charset=UTF-8',
                "data":referralData,
                "error": function(error) {
                    popup.modal('hide');
                    showError('System error. Failed to send invite to '+ self.recipientEmailId());
                },

                "success": function () {
                    popup.modal('hide');
                }
            });
        });
    }
}
