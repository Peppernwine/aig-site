
function LocationMaster(){
    this.self = this;

    var sunHours = {weekDay:0,shifts:
        [{startHour:11,startMinute:30,endHour:15,endMinute:0},{startHour:16,startMinute:30,endHour:21,endMinute:30}]};
    var monHours = {weekDay:1,shifts:[]};
    var tueHours = {weekDay:2,shifts:
        [{startHour:11,startMinute:30,endHour:14,endMinute:30},{startHour:16,startMinute:30,endHour:21,endMinute:30}]};
    var wedHours = {weekDay:3,shifts:
        [{startHour:11,startMinute:30,endHour:14,endMinute:30},{startHour:16,startMinute:30,endHour:21,endMinute:30}]};
    var thuHours = {weekDay:4,shifts:
        [{startHour:11,startMinute:30,endHour:14,endMinute:30},{startHour:16,startMinute:30,endHour:21,endMinute:30}]};
    var friHours = {weekDay:5,shifts:
        [{startHour:11,startMinute:30,endHour:14,endMinute:30},{startHour:16,startMinute:30,endHour:22,endMinute:0}]};
    var satHours = {weekDay:6,shifts:
        [{startHour:11,startMinute:30,endHour:15,endMinute:0},{startHour:16,startMinute:30,endHour:22,endMinute:0}]};

    this.opsSchedule = [sunHours,monHours,tueHours,wedHours,thuHours,friHours,satHours];
}

function ReserveOccasion(menu,data) {
    var self  = this;
    this.menu = menu;
    this.occasionId = 0;
    this.occasionCode = '';
    this.occasionDescription = '';
    _copyObject(data,this);
}

function MenuOptionChoice(option,data) {
    var self  = this;
    this.option = option;
    this.optionChoiceId = 0;
    this.optionChoiceCode = '';
    this.priceDelta = 0;
    this.getUniqueId = function () {
      return 'option-id-' + self.option.optionId + ':' + self.optionChoiceId;
    };

    this.hasAdditionalCost = function () {
        return self.option.hasAdditionalCost && (self.priceDelta > 0) ;
    };

    _copyObject(data,this);

    this.selectKey = {'optionId':this.option.optionId,'optionChoiceId':this.optionChoiceId};
}
MenuOptionChoice.prototype = Object.create(BaseObject.prototype);

function MenuOption(menu,data) {
    var self  = this;
    this.menu = menu;
    this.optionId = 0;
    this.optionCode = '';
    this.sortOrder = 0;
    this.hasExtraCost = 0;
    this.isRequired = 0;
    this.optionLabel = ''
    this.optionChoices = [];
    this.displayAllOptions = 0;

    _copyObject(data,this);


    if (data.optionChoices) {
        data.optionChoices.sort(
            function (moc1, moc2) {
                return moc1.optionChoiceValue - moc2.optionChoiceValue;
            });
        }

    var optMaster;
    for (var index = 0; index < data.optionChoices.length; index++) {
        optMaster = new MenuOptionChoice(this,data.optionChoices[index]);
        this.optionChoices.push(optMaster);
    }

    this.getOption = function(optionChoiceId) {
        return self.optionChoices.find(function(option) {return optionChoiceId === option.optionChoiceId });
    }

    this.hasAdditionalCost = function() {
        return (self.hasExtraCost === 1);
    };
}
MenuOption.prototype = Object.create(BaseObject.prototype);


function MenuItemOption(menuItem,option,data) {
    var self = this;
    this.menuItem = menuItem;
    this.option = option;
    this.availableOptionChoices = []
    this.optionsFilterFlag = data.optionsFilterFlag;

    if (data.optionChoices && data.optionChoices.length > 0) {
        for (var i = 0; i < data.optionChoices.length; i++) {
            var choiceId = data.optionChoices[i];
            this.availableOptionChoices.push(choiceId);
        }
    } else {
        for (var i = 0; i < option.optionChoices.length; i++) {
            var choiceId = option.optionChoices[i].optionChoiceId;
            this.availableOptionChoices.push(choiceId);
        }
        //RAJ
    }

    this.getOptionChoice = function(optionChoiceId) {
        return self.option.getOption(optionChoiceId);
    }

    this.getOptionCode = function() {
        return self.option.optionCode;
    }


    this.isOptionAvailable = function(pOptionChoiceId) {
        return (this.optionsFilterFlag === 1) || (self.availableOptionChoices.find(function(optionChoiceId) {return optionChoiceId === pOptionChoiceId}));
    }

    this.canDisplayOption = function (optionChoiceId) {
        if (this.option.displayAllOptions || this.isOptionAvailable(optionChoiceId))
            return true;
    };

    this.getPriceWithOption = function (optionChoiceId) {
        var price = self.menuItem.basePrice;

        var om = self.option.getOption(optionChoiceId);

        if (om && om.priceDelta)
            price += om.priceDelta;

        return price;
    };
}
MenuItemOption.prototype = Object.create(BaseObject.prototype);

function MenuItem(menu,data) {
    var self = this;

    this.menu = menu;
    this.menuCategoryId = 0;
    this.itemId = 0;
    this.itemCode = "";

    this.itemDescription = "";
    this.basePrice = 0;
    this.minSpiceLevel = 0;
    this.isChefSpecial = 0;
    this.isNutFree = 0;
    this.isGlutenFree = 0;

    _copyObject(data, this);

    this.getMenuItemOption = function (optionId) {
        if (!self.options) return [];
        return self.options.find(function (miOption) {
            return miOption.option.optionId === optionId;
        })
    };

    this.isOptionAvailable = function (optionId) {
        return self.getMenuItemOption(optionId);
    };

    this.options = [];
    if (data.options) {
        for (var i = 0; i < data.options.length; i++) {
            var menuOpt = data.options[i];
            var og = this.menu.getOption(menuOpt.optionId);
            var mo = new MenuItemOption(this, og, data.options[i]);
            this.options.push(mo);
        }

        this.options.sort(
            function (mog1, mog2) {
                return mog2.option.sortOrder - mog1.option.sortOrder;
            });
    }

    var spiceOptions = null;
    if (this.menu.spiceLevelOption) {
        spiceOptions = this.getMenuItemOption(this.menu.spiceLevelOption.optionId);
        if (spiceOptions &&spiceOptions.length > 0 )
            this.minSpiceLevel = spiceOptions.availableOptionChoices[0];
    }

    this.hasOptions = function () {
        return self.options;
    };

    this.hasExtraCostOptions = function () {
        if (!this.options) return false;

        for (var index = 0; index < this.options.length; index++) {
            var miOption = this.options[index];
            if (miOption.option.hasAdditionalCost()) {
               return true;
            }
            return false;
        }
    };

    this.getBaseDisplayPrice = function () {
        if (self.hasExtraCostOptions())
            return "$" + self.getBasePrice() + '+';
        else
            return "$" + self.getBasePrice() ;
    };

    this.getOptionChoice = function(optionId, optionChoiceId) {
        var mog = self.getMenuItemOption(optionId);
        var opm;
        if (mog && mog.option)
            return mog.getOptionChoice(optionChoiceId);
         else
            return null;
    };

    this.getOptionChoiceCode = function(optionId,optionChoiceId) {
        var opt = self.getOptionChoice(optionId,optionChoiceId);
        if(opt)
            return opt.optionChoiceCode;
        else
            return "";
    }


    this.getUniqueOptionChoiceId = function(optionId,optionChoiceId) {
        var om = self.getOptionChoice(optionId,optionChoiceId);
        if (om)
            return om.getUniqueId();
        else
            return '';
    };

    this.getOptionCode = function(optionId) {
        var mog = self.getMenuItemOption(optionId);
        if (mog)
            return mog.getOptionCode();
        else
            return '';
    };

    this.getOptionPriceDelta = function(optionId,optionChoiceId) {

        var om = self.getOptionChoice(optionId,optionChoiceId);
        if (om)
          return om.priceDelta;
        else
          return 0;
    };

    this.getDisplayOptionPriceDelta = function(optionId,optionChoiceId) {

        var priceDelta = self.getOptionPriceDelta(optionId,optionChoiceId);
        if (priceDelta)
            return '(+' + priceDelta + '$)';
        else
            return '';
    };


    this.getBasePrice = function() {
            return self.basePrice;
    };

    this.getPriceWithOption = function(optionId,optionChoiceId) {
        var mog = self.getMenuItemOption(optionId);
        if (mog)
            return mog.getPriceWithOption(optionChoiceId);
        else
            return 0;

    };

    this.hasAdditionalCost = function(optionId,optionChoiceId) {
        var om = self.getOptionChoice(optionId,optionChoiceId);
        return om && om.hasAdditionalCost();
    }

}
MenuItem.prototype = Object.create(BaseObject.prototype);

function Menu(data) {
    var self  = this;
    this.items = [];
    this.options = [];
    this.typeId = 0;
    this.typeCode = '';
    this.isAvailableOnline = 0;
    this.hourDescriptions = [];
    this.reserveOccasions = [];

    _copyObject(data,this);

    var opt;
    for (var index = 0; index < data.options.length; index++) {
        var opt = new MenuOption(this,data.options[index])
        this.options.push(this,opt);
    }

    this.spiceLevelOption =  self.options.find(function(option) {return  option.optionCode === "Spice Level"});

    this.getItem = function(itemId) {
        return self.items.find(function(item) {return itemId === item.itemId});
    };

    this.getOption = function(optionId) {
        return self.options.find(function(option) {return optionId === option.optionId });
    };

    for (var index = 0; index < data.items.length; index++) {
        var mi = new MenuItem(this,data.items[index]);
        this.items.push(mi);
    }

    this.menuCategories =   ko.utils.arrayMap(data.categories, function(cat) {
        return new menuCategory(cat.categoryId, cat.categoryCode,self.items)});


    for (var index = 0; index < data.hourDescriptions.length; index++) {
        this.hourDescriptions.push(data.hourDescriptions[index]);
    }

    for (var index = 0; index < data.reserveOccasions.length; index++) {
        var ro = new ReserveOccasion(this,data.reserveOccasions[index]);
        this.reserveOccasions.push(ro);
    }

    this.getReserveOccasions  = ko.computed(function(){
        return self.reserveOccasions;
    },this);


    this.isTypeAvailableOnline = function () {
      return  (self.isAvailableOnline === 1);
    };

    this.isFood = function () {
        return  (self.typeId < 100) ;
    };

}
Menu.prototype = Object.create(BaseObject.prototype);



function menuCategory(categoryId,categoryCode,items) {
    this.id = categoryId;
    this.code = categoryCode;

    this.items = null;
    this.items = ko.utils.arrayFilter(
        items, function(item)  {
            return (item.menuCategoryId == categoryId);
        }
    );

    this.gotoMenuCategory = function () {
        scrollToCenter(this.id);
        var $element = $('#'+this.id);

        $element.blast({ delimiter: "word" })
            .velocity({opacity: 0}, 2000, "easeInSine")
            .velocity({opacity: 1}, 2000, "easeInSine");
    };
}

function MenuViewModel(menuData) {
    var self = this;

    this.menu = new Menu(menuData);

    this.menufilterValue = ko.observable("");
    this.setOrderViewModel = function(ovm) {
        self.orderVM = ovm;
    };

    this.categories = ko.computed(function() {
        var filter = this.menufilterValue().toLowerCase();
        if (!filter) {
            return self.menu.menuCategories;
        } else {

            var filteredItemList = ko.utils.arrayFilter(self.menu.items, function(item) {
                return (item.itemCode.toLowerCase().indexOf(filter) >= 0);
            });

            var catList = ko.utils.arrayMap(self.menu.menuCategories, function(cat) {
                return new menuCategory(cat.id, cat.code,filteredItemList)});

            var catList = ko.utils.arrayFilter(catList, function(cat) {
                return (cat.items.length > 0);
            });

            return catList;

        }
    }, this);


    this.isFood = function () {
        return  (self.typeId < 100) ;
    };
}


var menu = null;
var mv = null;
var ovm = null;


$(function() {

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


