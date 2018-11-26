function CateringMenuItem(data) {
    var self = this;
    this.itemCode = ko.observable(data.itemCode)  ;
    this.chefsSpecial =  ko.observable(data.chefsSpecial)  ;
    this.isChefsSpecial = ko.computed(function() { return self.chefsSpecial === 1},this);
}

function CateringMenuCategory(data) {
    var self = this;
    this.categoryCode = ko.observable(data.categoryCode)  ;
    this.items = ko.observableArray([]);

    var mappedItems = $.map(data.items, function(item) {
        return new CateringMenuItem(item);
    });
    this.items(mappedItems);
}

function CateringPackageCategory(data) {
    var self = this;
    this.categoryCode = ko.observable(data.categoryCode)  ;
    this.itemCount =  ko.observable(data.itemCount)  ;
}

function CateringPackageOption(data) {
    var self = this;
    this.optionName = ko.observable(data.optionName);
    this.basePrice = ko.observable(data.basePrice);
    this.items = ko.observableArray([]);

    var mappedItems = $.map(data.items, function(item) {
        return new CateringPackageCategory(item);
    });
    this.items(mappedItems);
}

function CateringViewModel() {
    var self = this;
    this.typeDescription = ko.observable(null);
    this.packageOptions = ko.observableArray([]);
    this.menuCategories = ko.observableArray([]);

    this.getData= function () {

        var packageOptions = [
            {"optionName":"Option-1","basePrice":14.00,
                "items":[
                            {"categoryCode":"Veg Appetizers","itemCount":1},
                            {"categoryCode":"Vegetarian","itemCount":2},
                            {"categoryCode":"Rice","itemCount":1},
                            {"categoryCode":"Bread","itemCount":1},
                            {"categoryCode":"Condiments","itemCount":1},
                            {"categoryCode":"Dessert","itemCount":1}
                        ]
            },
            {"optionName":"Option-2","basePrice":14.00,
                "items":[
                    {"categoryCode":"Veg Appetizers","itemCount":2},
                    {"categoryCode":"Vegetarian","itemCount":3},
                    {"categoryCode":"Rice","itemCount":1},
                    {"categoryCode":"Bread","itemCount":1},
                    {"categoryCode":"Condiments","itemCount":1},
                    {"categoryCode":"Dessert","itemCount":1}
                ]
            },
            {"optionName":"Option-3","basePrice":14.00,
                "items":[
                    {"categoryCode":"Veg Appetizers","itemCount":1},
                    {"categoryCode":"Vegetarian","itemCount":2},
                    {"categoryCode":"Chicken","itemCount":1},
                    {"categoryCode":"Rice","itemCount":1},
                    {"categoryCode":"Bread","itemCount":1},
                    {"categoryCode":"Condiments","itemCount":1},
                    {"categoryCode":"Dessert","itemCount":1}
                ]
            },
            {"optionName":"Option-4","basePrice":14.00,
                "items":[
                    {"categoryCode":"Veg Appetizers","itemCount":1},
                    {"categoryCode":"Non Veg. Appetizers","itemCount":1},
                    {"categoryCode":"Vegetarian","itemCount":2},
                    {"categoryCode":"Chicken","itemCount":1},
                    {"categoryCode":"Rice","itemCount":1},
                    {"categoryCode":"Bread","itemCount":2},
                    {"categoryCode":"Condiments","itemCount":2},
                    {"categoryCode":"Dessert","itemCount":1}
                ]
            },
            {"optionName":"Option-5","basePrice":14.00,
                "items":[
                    {"categoryCode":"Veg. Appetizers","itemCount":1},
                    {"categoryCode":"Non Veg. Appetizers","itemCount":1},
                    {"categoryCode":"Vegetarian","itemCount":2},
                    {"categoryCode":"Chicken","itemCount":1},
                    {"categoryCode":"Lamb","itemCount":1},
                    {"categoryCode":"Rice","itemCount":1},
                    {"categoryCode":"Bread","itemCount":1},
                    {"categoryCode":"Condiments","itemCount":2},
                    {"categoryCode":"Dessert","itemCount":1}
                ]
            },
            {"optionName":"Option-6","basePrice":14.00,
                "items":[
                    {"categoryCode":"Veg. Appetizers","itemCount":2},
                    {"categoryCode":"Non Veg. Appetizers","itemCount":2},
                    {"categoryCode":"Vegetarian","itemCount":2},
                    {"categoryCode":"Chicken","itemCount":1},
                    {"categoryCode":"Lamb","itemCount":1},
                    {"categoryCode":"Rice","itemCount":1},
                    {"categoryCode":"Bread","itemCount":2},
                    {"categoryCode":"Condiments","itemCount":2},
                    {"categoryCode":"Dessert","itemCount":1}
                ]
            },
            {"optionName":"Option-7","basePrice":14.00,
                "items":[
                    {"categoryCode":"Veg. Appetizers","itemCount":2},
                    {"categoryCode":"Non Veg. Appetizers","itemCount":2},
                    {"categoryCode":"Vegetarian","itemCount":2},
                    {"categoryCode":"Chicken","itemCount":2},
                    {"categoryCode":"Lamb","itemCount":1},
                    {"categoryCode":"Shrimp","itemCount":1},
                    {"categoryCode":"Rice","itemCount":1},
                    {"categoryCode":"Bread","itemCount":2},
                    {"categoryCode":"Condiments","itemCount":2},
                    {"categoryCode":"Dessert","itemCount":2}
                ]
            }
        ];

        var veggieAptMenuItems = [
            {"categoryCode":"Veg. Appetizers",
              "items":[
                        {"itemCode":"Gobi Manchurian","chefsSpecial":0},
                        {"itemCode":"Tawa Baingain","chefsSpecial":0},
                        {"itemCode":"Cut Mirchi","chefsSpecial":0},
                        {"itemCode":"Veg. Cutlet","chefsSpecial":0},
                        {"itemCode":"Veg. Curry Momo","chefsSpecial":1},
                        {"itemCode":"Medu Vada","chefsSpecial":0},
                        {"itemCode":"Masala Vada","chefsSpecial":0},
                        {"itemCode":"Aloo Tikki","chefsSpecial":0},
                        {"itemCode":"Aloo Bonda","chefsSpecial":0},
                        {"itemCode":"Iddly","chefsSpecial":0},
                        {"itemCode":"Bisi Bele Bath","chefsSpecial":0}
                      ]}
                            ];

        var menuCategories = [];
        menuCategories = menuCategories.concat(veggieAptMenuItems);

        var meatAptMenuItems = [
            {"categoryCode":"Meat Appetizers",
                "items":[
                    {"itemCode":"Meat Samosa","chefsSpecial":0},
                    {"itemCode":"Chicken Cutlet","chefsSpecial":0},
                    {"itemCode":"Chicken Manchurian","chefsSpecial":0},
                    {"itemCode":"Kerala Chicken Fry","chefsSpecial":0},
                    {"itemCode":"Chicken Pepper Fry","chefsSpecial":0},
                    {"itemCode":"Chicken Curry Momo","chefsSpecial":1},
                    {"itemCode":"Chicken Pakora","chefsSpecial":0},
                    {"itemCode":"Meenu Pollichadu","chefsSpecial":1},
                    {"itemCode":"Fish Cutlet","chefsSpecial":0},
                    {"itemCode":"Calamari Balchao","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(meatAptMenuItems);

        var VegEntreeMenuItems = [
            {"categoryCode":"Vegetarian",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(VegEntreeMenuItems);

        var chickenEntreeMenuItems = [
            {"categoryCode":"Chicken",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(chickenEntreeMenuItems);


        var LambEntreeMenuItems = [
            {"categoryCode":"Lamb & Mutton",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(LambEntreeMenuItems);



        var SeaFoodEntreeMenuItems = [
            {"categoryCode":"Seafood",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(SeaFoodEntreeMenuItems);

        var RiceEntreeMenuItems = [
            {"categoryCode":"Rice & Noodles",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(RiceEntreeMenuItems);

        var BreadMenuItems = [
            {"categoryCode":"Bread",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(BreadMenuItems);


        var DessertMenuItems = [
            {"categoryCode":"Dessert",
                "items":[
                    {"itemCode":"Kodi Vepadu","chefsSpecial":0},
                    {"itemCode":"Amchuri Murg","chefsSpecial":0},
                    {"itemCode":"Kori Nilgiri","chefsSpecial":1},
                    {"itemCode":"Chicken Caffreal","chefsSpecial":0},
                    {"itemCode":"Chicken Mirch Ka Salan","chefsSpecial":0},
                    {"itemCode":"Chicken Rogan Josh","chefsSpecial":0},
                    {"itemCode":"Chicken Mughlai","chefsSpecial":0},
                    {"itemCode":"Adraki Murgh","chefsSpecial":0}
                ]}
        ];

        menuCategories = menuCategories.concat(DessertMenuItems);


        var data = {"typeDescription": "Traditional Indian Catering","packageOptions" : packageOptions,"menuCategories" : menuCategories};

        return data;
    }


    this.load = function() {
        var data = self.getData();

        self.typeDescription(data.typeDescription);

        var options = $.map(data.packageOptions, function(packageOption) {
            return new CateringPackageOption(packageOption);
        });

        options.forEach(function (option) {self.packageOptions.push(option)});

        var menuCategories = $.map(data.menuCategories, function(menuCategory) {
            return new CateringMenuCategory(menuCategory);
        });

        menuCategories.forEach(function (menuCategory) {self.menuCategories.push(menuCategory)});


        /*
        cpo = new CateringPackageOption({"optionName":"Option-1","basePrice":100.99,
                                        "items":[{"categoryCode":"Veg Appetizer","itemCount":2},
                                                 {"categoryCode":"Vegetables","itemCount":1}]});
        self.options.push(cpo);

        cpo = new CateringPackageOption({"optionName":"Option-2","basePrice":100.99,
            "items":[{"categoryCode":"Veg Appetizer","itemCount":2},
                {"categoryCode":"Vegetables","itemCount":1}]});

        self.options.push(cpo);

        cpo = new CateringPackageOption({"optionName":"Option-3","basePrice":100.99,
            "items":[{"categoryCode":"Veg Appetizer","itemCount":2},
                {"categoryCode":"Vegetables","itemCount":1}]});

        self.options.push(cpo);

        cpo = new CateringPackageOption({"optionName":"Option-4","basePrice":100.99,
            "items":[{"categoryCode":"Veg Appetizer","itemCount":2},
                {"categoryCode":"Vegetables","itemCount":1}]});

        self.options.push(cpo);
*/
    }
}


