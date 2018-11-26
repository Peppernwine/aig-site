<?php
    require_once "bootstrap.php";
?>
<?php
    $title = "Home";
    require_once "header-html.php";
?>
    <!-- Static navbar -->
    <div class="container-flex launch-page">

        <div class="empty-content"> </div>
        <?php

            require_once "multiparallax.php";
        ?>

        <div class="landing-page-overlay"></div>

            <div class="moto-overlay" >
                <div>
                    <!--
                    <p>Unique blend of <span>Authentic</span> Indian Flavors &amp; <span>Modern</span> Experience</p>
                    -->
                    <p>Fusion of <span>Authentic</span> Indian Flavors &amp; <span>Modern</span> Experience</p>

                    <!--     <p >Come celebrate your special evening with special Entrees from a private menu created just for you and/or have a fun evening enjoying Spicy Food, Drinks & Live Music </p> -->
                    <ul class="call-to-action-bar" >
                        <li class="call-to-action-link"><a class="call-to-action-btn" href="#">Chef's Table</a></li>
                        <li class="call-to-action-link"><a class="call-to-action-btn" href ="#">Events</a></li>
                        <li class="call-to-action-link" ><a class="call-to-action-btn" href="#">Live Music <i class="fas fa-music"></i></a> </li>
                    </ul>

                </div>
            </div>

            <!-- Social Icons -->
            <ul id="social_side_links">
                <li><a href="https://www.facebook.com/avonindiangrill" target="_blank"><img src="images/facebook-icon.png" alt="" /></a></li>
            </ul>


        <div class="container" >

            <article style="display:none" id="thumbnail-slider">
                <div class="inner">
                    <ul>
                        <li><a class="thumb" href="images/lobstermasala3-400.jpg"></a></li>
                        <li><a class="thumb" href="images/ctm-400.jpg"></a></li>
                        <li><a class="thumb" href="images/naan-400.jpg"></a></li>
                        <li><a class="thumb" href="images/quesadilla4-400.jpg"></a></li>
                        <li><a class="thumb" href="images/wings3-400.jpg"></a></li>
                        <li><a class="thumb" href="images/sausagemasala-400.jpg"></a></li>
                        <li><a class="thumb" href="images/tikkablanket-400.jpg"></a></li>
                        <li><a class="thumb" href="images/honeygobi4-400.jpg"></a></li>
                        <li><a class="thumb" href="images/samosa1-400.jpg"></a></li>
                        <li><a class="thumb" href="images/slider1-400.jpg"></a></li>
                        <li><a class="thumb" href="images/catering8-400.jpg"></a></li>
                        <li><a class="thumb" href="images/waffledosa1-400.jpg"></a></li>
                        <li><a class="thumb" href="images/momos1-400.jpg"></a></li>
                        <li><a class="thumb" href="images/chickenstirfry-400.jpg"></a></li>
                        <li><a class="thumb" href="images/Red Cocktail-400.jpg?v2"></a></li>
                        <li><a class="thumb" href="images/Blue Cocktail-400.jpg?v2"></a></li>
                    </ul>
                </div>
            </article>

            <article class="group landing-group"> <!-- display:block;margin:auto -->
                <h2 class="header-underline">Our Small Plates</h2>

                <div class="carousel" data-flickity='{ "imagesLoaded":true, "cellAlign": "center","contain": true,"wrapAround": false,"freeScroll": false }'>
                    <div class="carousel-cell card">
                          <img src="images/small-plates-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">FUSION</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                    <div class="carousel-cell card">
                          <img src="images/chaat-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">CHAAT</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>


                      <div class="carousel-cell card">
                          <img src="images/alldaybreakfast-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">ALL DAY BREAKFAST</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>
                  </div>

          </article>

            <article class="group landing-group"> <!-- display:block;margin:auto -->
                <h2 class="header-underline">Our Starters, Entrees &amp; Desserts</h2>

                <div class="carousel" data-flickity='{ "imagesLoaded":true, "cellAlign": "center","contain": true,"wrapAround": false,"freeScroll": false }'>
                      <div class="carousel-cell card">
                          <img src="images/honeygobi4-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">APPETIZER</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                      <div class="carousel-cell card">
                          <img src="images/soup-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">SOUP & SALAD</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                    <div class="carousel-cell card">
                          <img src="images/tandoorchicken-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">TAWA & GRILL</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>
                    <div class="carousel-cell card">
                          <img src="images/Biriyani3-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">RICE & NOODLES</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                     <div class="carousel-cell card">
                          <img src="images/daal-curry-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">VEGETARIAN ENTREE</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                      <div class="carousel-cell card">
                          <img src="images/muttoncurry-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">NON VEGETARIAN ENTREE</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                    <div class="carousel-cell card">
                          <img src="images/fish-curry-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">SEAFOOD</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                    <div class="carousel-cell card">
                          <img src="images/naan-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">BREAD</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>


                    <div class="carousel-cell card">
                          <img src="images/carrot-halwa-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">DESSERT</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>
                  </div>

          </article>

            <article class="group landing-group"> <!-- display:block;margin:auto -->
                <h2 class="header-underline">Our Beverages</h2>

                <div class="carousel" data-flickity='{ "imagesLoaded":true, "cellAlign": "center","contain": true,"wrapAround": false,"freeScroll": false }'>
                      <div class="carousel-cell card">
                          <img src="images/wine-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">WINE</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                      <div class="carousel-cell card">
                          <img src="images/beer-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">BEER</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                      <div class="carousel-cell card">
                          <img src="images/cocktail-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">COCKTAIL</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                     <div class="carousel-cell card">
                          <img src="images/mocktail-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">MOCKTAIL</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>
                     <div class="carousel-cell card">
                          <img src="images/lassi-400.jpg" alt="Avatar" style="width:100%">
                          <div class="cardcontainer">
                            <h4 class="card-header">LASSI &amp; ROOH AFZA</h4>
                            <p>50% off on Drinks &amp; Small plates</p>
                          </div>
                      </div>

                </div>

          </article>

            <article class="group landing-group"> <!-- display:bslock;margin:auto;width:90%; -->
                <h2 class="header-underline">Our Service & Events</h2>

                <div class="carousel" data-flickity='{ "imagesLoaded":true, "cellAlign": "center","contain": true,"wrapAround": false,"freeScroll": false }'>
                    <div class="carousel-cell card">
                      <img src="images/buffet2-400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">BUFFET</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>
                    <div class="carousel-cell card">
                      <img src="images/dinner-table-400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">DINING</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>

                    <div class="carousel-cell card">
                      <img src="images/chefs-table4-400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">CHEF&apos;S TABLE</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>


                    <div class="carousel-cell card">
                      <img src="images/twococktails-400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">HAPPY HOUR</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>


                    <div class="carousel-cell card">
                      <img src="images/homemeals-400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">HOME MEALS</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>

                    <div class="carousel-cell card">
                      <img src="images/catering8-400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">CATERING</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>

                    <div class="carousel-cell card">
                      <img src="images/special-event400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">SPECIAL EVENTS</h4>
                        <p>50% off on Drinks &amp; Small plates</p>
                      </div>
                    </div>
    <!--
                    <div class="carousel-cell card">
                      <img src="image/private-event400.jpg" alt="Avatar" style="width:100%">
                      <div class="cardcontainer">
                        <h4 class="card-header">PRIVATE FUNCTIONS</h4>
                        <p>50% off on Drinks & Small plates</p>
                      </div>
                    </div>
        -->
                </div>
          </article>

            <article class="group landing-group"> <!-- "margin:auto;width:90% -->
                    <h2 class="header-underline">Your Testimonials</h2>

                    <div class="star-rating">
                        <span>
                            <span style="color:#b70038;font-size:1.2rem">Social Media Rating : </span>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half"></i>
                        </span>
                    </div>


                    <div style="xposition:relative">
                        <div class="mb-wrap mb-style-6">
                            <blockquote cite="https://www.yelp.com/biz/avon-indian-grill-avon">
                                <p>
                                    The best Indian food I've had in a restaurant in the US! I went for the New Year's Eve special and was blown away by the unique tastes. I particularly enjoyed the 'tikki in a blanket' and the various sauces. We were a large group and ordered a variety of appetizers and entrées & every gravy is unique...
                            </p>
                            </blockquote>
                            <div class="mb-attribution">
                                <p class="mb-author">Vidhu N.
                                    <span style="color:darkorange">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </span>
                                </p>
                                <cite><a>Yelp Review</a></cite>
                            </div>

                        </div>
                        <div class="mb-wrap mb-style-6">
                            <blockquote cite="https://www.yelp.com/biz/avon-indian-grill-avon">
                                <p>
                                    Amazing.  Great food.  Great people.  My family and I are so addicted we'd eat here every day if we could.  In fact, several foods we usually don't like (okra, cauliflower, etc.) are delicious when they prepare them. Their weekend buffet is a bargain and is always changing.  Great combination of stereotypical Indian foods (Tandori Chicken, Tiki Masala, etc.) as well as more adventurous items. You will always leave satisfied.</p>
                            </blockquote>
                            <div class="mb-attribution">
                                <p class="mb-author">Fred L.
                                    <span style="color:darkorange">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </span>
                                </p>
                                <cite><a>Yelp Review</a></cite>
                            </div>
                         </div>
                        <div class="mb-wrap mb-style-6">
                            <blockquote cite="https://www.google.com/search?source=hp&ei=yfiwWriTB9HuzgLV2p6wCw&q=avon+indian+grill&oq=avon+indian+&gs_l=psy-ab.3.0.0l10.4636.8239.0.9320.13.12.0.0.0.0.149.1264.4j8.12.0....0...1.1.64.psy-ab..1.12.1261.0..46j0i131k1j0i46k1.0.AZ3jcOdMvns#lrd=0x89e7a8dbee9f0155:0xffe0e973a8411278,1,,,">
                                <p>We have been going here for years and each time is wonderful.  They started calling us by name after just a few visits and remember our favorite drinks and meals.  They even prepare a lamb dish that is not on the menu. The weekend buffet is exceptional value.  I can't recommend the restaurant highly enough
                            </p>
                            </blockquote>
                            <div class="mb-attribution">
                                <p class="mb-author">Susan R.

                                    <span style="color:darkorange">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </span>

                                </p>
                                <cite><a>Google Review</a></cite>
                            </div>
                        </div>
                        <div class="mb-wrap mb-style-6">
                            <blockquote cite="https://www.facebook.com/pg/avonindiangrill/reviews/?ref=page_internal">
                                <p>
                                    Avon Indian Grill served food for our recent Super Bowl party. Their party menu has a wide range of options like their dine-in menu. Chickens Wings (especially “Dynamite” flavor) and Chicken Biryani were big hits amongst kids and adults. Awesome food from Avon Indian Grill and an exciting Super Bowl game made our party a memorable one</p>
                            </blockquote>
                            <div class="mb-attribution">
                                <p class="mb-author">Mahesh V.
                                    <span style="color:darkorange">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </span>

                                </p>
                                <cite><a>Facebook Review</a></cite>
                            </div>
                         </div>

                    <div style="clear:both"></div>
                    </div>
                </article>

        </div>
       </div><!--/.container -->

<?php
    require_once "footer-html.php";


/*

TO DO
1) handle payment errors and retry just payment part
2) Cookies warning popup
3) Terms & conditions on signup form
4) fix footer navigations
5) Testing options for ordering - enable specific communications & include test in the email/fax/SMS

LOWER PRIORITY
1) prevent users from accessing orders of other customers (issue is when order does not have an ID)
2) prevent users from accessing reservations of other customers (issue is when order does not have an ID)
6) footer for order forms
7) good solution bootstrap.php includes for resource files

aigrestaurantsb.db.12728668.6b2.hostedresource.net
aigrestaurant
X@67JTNavc85DRaj4oRx#


aigrestaurantsb.db.12728668.6b2.hostedresource.net
aigrestaurantsb
X!x#lcBnTYDaj4ox#



*/
?>

