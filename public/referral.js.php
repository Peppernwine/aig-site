<?php
$userId = getCurrentUserId($db);
?>


<script type="text/javascript">
$(function() {
    var rvm = new ReferralViewModel({"customerId":0});
    ko.applyBindings(rvm);
});
</script>

