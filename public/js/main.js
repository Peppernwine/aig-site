function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
}

function disableBodyScroll() {
    $('body').addClass('noscroll');
}


function reEnableBodyScroll() {
    $('body').removeClass('noscroll');
}

function showError(error) {
    var errorContent = "<div style='text-align:center;margin:10px' class='alert alert-danger' role='alert'> " +
        "<span class='glyphicon glyphicon-exclamation-sign'></span> " + error
    "</div>";

    bootbox.alert(errorContent);
}


function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function getUrlParam(parameter, defaultvalue){
    var urlparameter = defaultvalue;
    if(window.location.href.indexOf(parameter) > -1){
        urlparameter = getUrlVars()[parameter];
    }
    return urlparameter;
}

function scrollToCenter(id) {

var $window = $(window),
    $element = $('#'+id),
    elementTop = $element.offset().top,
    elementHeight = $element.height(),
    viewportHeight = $window.height(),
    scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);

    $('html, body').animate({scrollTop : scrollIt},800);

    //$window.scrollTop(scrollIt);

    $element.focus();
}

function allowNumbersOnly(selector,allowDecimal) {
    $(selector).keydown(function(e) {

        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode == 8 || event.keyCode == 13 || event.keyCode == 9 || event.keyCode == 46
            || event.keyCode == 37 || event.keyCode == 39 || ((event.keyCode == 110 || event.keyCode == 190) && allowDecimal ) ) {
            return true;
        }
        else if (key < 48 || key > 57) {
            return false;
        }
        else return true;
    });
}


$(document).ready(function() {
    var lastScrollPosition = 0;
    var scrollTopBtnVisible = false;

    // When the user scrolls down 60px from the top of the document, show the button
    $(window).scroll(
        function() {
            var newScrollPosition = window.scrollY;

            if (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) {

                if (!scrollTopBtnVisible) {
                    scrollTopBtnVisible = true;
                    $('#scroll-to-top-btn').fadeIn(1000);
                    setTimeout(function(){
                        scrollTopBtnVisible = false;
                        $('#scroll-to-top-btn').fadeOut(1000);
                    }, 3000);
                }
            } else
                $('#scroll-to-top-btn').fadeOut(500);

            lastScrollPosition = newScrollPosition;
        });
    $(document).ready(function() {


    });

    $('#scroll-to-top-btn').click(function(){
        $("#scroll-to-top-btn").blur()
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });
});

ko.extenders.numeric = function(target, precision) {
    //create a writable computed observable to intercept writes to our observable
    var result = ko.pureComputed({
        read: target,  //always return the original observables value
        write: function(newValue) {
            var current = target();
            if (current || newValue) {
                var roundingMultiplier = Math.pow(10, precision),
                    newValueAsNum = isNaN(newValue) ? 0 : +newValue,
                    valueToWrite = Math.round(newValueAsNum * roundingMultiplier) / roundingMultiplier;

                //only write if it changed
                if (valueToWrite !== current) {
                    target(valueToWrite);
                } else {
                    //if the rounded value is the same, but a different value was written, force a notification for the current field
                    if (newValue !== current) {
                        target.notifySubscribers(valueToWrite);
                    }
                }
            }
        }
    }).extend({ notify: 'always' });

    //initialize with current value to make sure it is rounded appropriately
    result(target());

    //return the new computed observable
    return result;
};


ko.bindingHandlers.groupoptionradio = {
    init: function (element, valueAccessor, allBindings,viewModel, bindingContext)
    {
        ko.utils.registerEventHandler(element,"click",
            function ()
            {
                var observable = valueAccessor();
                var checkedVal = allBindings.get('checkedValue');

                observable(checkedVal);
            });
    },
    update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
        var observable = valueAccessor();
        var currOptions = ko.unwrap(observable);
        var checkedVal = allBindings.get('checkedValue');

        var foundObj = currOptions.find(function (sel) {
            return (checkedVal === sel);
        });

        element.checked = foundObj;
    }
};

function getUniqueId() {
    return Math.random().toString(36).substr(2, 16);
}

function formatCurrency(value) {
    return '$' + value.toFixed(2);
}

function convertISO8601toDate(dtstr) {

    // replace anything but numbers by spaces
    dtstr = dtstr.replace(/\D/g," ");

    // trim any hanging white space
    dtstr = dtstr.replace(/\s+$/,"");

    // split on space
    var dtcomps = dtstr.split(" ");

    // not all ISO 8601 dates can convert, as is
    // unless month and date specified, invalid
    if (dtcomps.length < 3) return "invalid date";
    // if time not provided, set to zero
    if (dtcomps.length < 4) {
        dtcomps[3] = 0;
        dtcomps[4] = 0;
        dtcomps[5] = 0;
    }

    // modify month between 1 based ISO 8601 and zero based Date
    dtcomps[1]--;

    var convdt = new Date(Date.UTC(dtcomps[0],dtcomps[1],dtcomps[2],dtcomps[3],dtcomps[4],dtcomps[5]));

    return convdt;
}

function ISOFormatDate(value) {
    return value.getFullYear() + '-' + (value.getMonth() + 1)  + '-' + value.getDate();
}

function formatDate(value) {
    var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "May", "Jun", "Jul",
        "Aug", "Sep", "Oct",
        "Nov", "Dec"
    ];

    var dayName = days[ value.getUTCDay() ];
    var day = value.getUTCDate();
    var monthName = monthNames[value.getUTCMonth()];
    var year = value.getUTCFullYear();

    return dayName + ', ' + monthName + ' ' + day + ' ' + year;
}


function getDateRange(period,periodType) {
    var dateRange = {startDate:'',endDate:''};

    var startDate = null;
    var endDate = null;
    if (period != 'Any Date') {

        var today = new Date();
        var relativeDt = new Date();
        switch (period) {
            case '1 Week':  relativeDt.setDate(relativeDt.getDate() - 7);
                            break;
            case '2 Weeks': relativeDt.setDate(relativeDt.getDate() - 14);
                            break;
            case  '1 Month': relativeDt.setMonth(relativeDt.getMonth() - 1);
                            break;
            case  '3 Months': relativeDt.setMonth(relativeDt.getMonth() - 3);
                            break;
            case  '6 Months': relativeDt.setMonth(relativeDt.getMonth() - 6);
                            break;
            case  '1 Year': relativeDt.setFullYear(relativeDt.getFullYear() - 1);
                            break;
        }

        if (periodType == 'and Older') {
            startDate = new Date(1900,today.getMonth() , today.getDate());
            endDate =  relativeDt;
        } else {
            startDate = relativeDt;
            endDate =  new Date();
            endDate.setDate(today.getDate() + 180);
        }

        dateRange.startDate = ISOFormatDate(startDate);
        dateRange.endDate = ISOFormatDate(endDate);;
    }
    return dateRange;
}

function isObject(val) {
    return val instanceof Object;
}


function isArray(val) {
    return val instanceof Array;
}


function _copyObject(source, destination) {
    for (var p in source) {
        if (source.hasOwnProperty(p) && (!isArray(source[p]) && !isObject(source[p])) ) {
                destination[p] = source[p];
        }
    };
};

function BaseObject () {
    this.copyObject = function (source, destination) {
        _copyObject(source,destination);
        };
};




