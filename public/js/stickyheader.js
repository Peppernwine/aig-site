function closeNav() {
    node = document.getElementById("partners");
    document.body.removeChild(node);
}     

function addOrderOption(parent,href,text) {
    var listItem = document.createElement("li");
    parent.appendChild(listItem);

    var node = document.createElement("a");
    node.setAttribute('href',href);
    node.setAttribute('target',"_blank");
    node.innerHTML = text;        
    listItem.appendChild(node);
}


function showOrderOptions() {
    var overlayNode = document.createElement("article");

    overlayNode.classList.add("online-order-overlay");
    overlayNode.setAttribute("id","partners");

    var contentNode = document.createElement("div");
    contentNode.classList.add("online-partners");
    var navList = document.createElement("ul");
    navList.style.listStyle = "none";
    contentNode.appendChild(navList);

    var node = document.createElement("a");
    node.setAttribute('href','javascript:void(0)');
    node.classList.add('closebtn');
    node.setAttribute('onclick','closeNav()');
    node.innerHTML = "&times;";            

    overlayNode.appendChild(node);

    addOrderOption(navList,'tel:8602844466','Call Avon Indian Grill');
    addOrderOption(navList,'https://www.beyondmenu.com/47144/avon/avon-indian-grill-avon-06001.aspx','BeyonMenu');
    addOrderOption(navList,'https://www.dineinct.com/single.php/order/restaurant/avon-indian-grill/27?takeout=1','Dine-In Connecticut');
    addOrderOption(navList,'https://mymozo.com/myrestaurant/default?CompanyId=484','MyMozo');
    addOrderOption(navList,'https://avon.eat24hours.com/avon-indian-grill/103910','EAT24');
    addOrderOption(navList,'https://www.grubhub.com/restaurant/avon-indian-grill-320-w-main-st-avon/436949','GRUBHUB');

    overlayNode.appendChild(contentNode);
    document.body.appendChild(overlayNode);
    window.setTimeout(function(){overlayNode.style.width = "100%";},350);
}


// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function windowScroll() {
    if (!header) return;
    if (window.pageYOffset >= sticky) {
        topbar.style.display = "none";
        navbarheader.style.display = "none";
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
        navbarheader.style.display = "block";
        topbar.style.display = "block";
    }

};


window.onscroll = function() {windowScroll()};
// Get the header
var header,navbarheader,topbar,sticky;

$(document).ready(function() {
    header = document.getElementsByTagName("header")[0];
    navbarheader = document.getElementsByClassName("navbar-header")[0];
    topbar = document.getElementsByClassName("top-bar")[0];

    // Get the offset position of the navbar
    sticky = header.offsetTop + 85;

    $(window).scroll(function(){windowScroll()});

});

