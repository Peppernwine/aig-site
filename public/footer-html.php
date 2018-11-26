        <footer>
            <section>
                <nav>
                    <ul>
                        <li>
                            <ul>
                                <li><a href="#">Menu</a></li>
                                <li><a href="#">Lunch &amp; Dinner</a></li>
                                <li><a href="#">Chef&apos;s Table</a></li>
                                <li><a href="#">Catering</a></li>
                                <li><a href="#">Beverages</a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul>
                        <li>
                            <ul>
                                <li><a href="#">About</a></li>
                                <li><a href="#">Cuisine</a></li>
                                <li><a href="#">Chef&apos;s Table</a></li>
                                <li><a href="#">Catering</a></li>
                                <li><a href="#">Special Events</a></li>
                                <li><a href="#">Bar</a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul>
                        <li>
                            <ul>
                                <li><a href="#">Events</a></li>
                                <li><a href="#">Private Functions</a></li>
                                <li><a href="#">Special Events</a></li>
                                <li><a target="_blank" href="#">Reservations</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </section>    

            <section>
                <p>320 West Main Street<br>Avon, CT 06001<br> 
                    <a href="#">See map</a>
                </p>
                <p>Tel: (860) 284-4466<br>Fax:(860) 404-5319</p>
            </section>	
            
            <section>
                <p><em>Lunch Hours:</em><br>Tue - Fri 11:30am - 2:30pm, Sat &amp; Sun 11:30am - 3pm</p><p><em>Dinner Hours:</em><br> Tue - Thu &amp; Sun 4:30pm - 9:30pm, Fri &amp; Sat 4:30pm - 10pm </p>
                <p><em>Happy Hour:</em><br>Tue - Fri 4:30pm - 6:30pm</p>
            </section>	

            <div style="clear:both"></div>

            
            <section id="footer-logo-section">
                <img src="images/PWLogo-400.png"/>
                <p>© 2018 PEPPER 'N WINE LLC, ALL RIGHTS RESERVED</p>
                <ul id="social-media-links">
                    <li>
                        <a target ="_blank" href="https://www.facebook.com/avonindiangrill/"><i style="padding:5px;color: white" class="fab fa-facebook-f"></i>like us on facebook</a>
                    </li>
                </ul>
            </section>
            <div style="clear:both"></div>
        </footer>

        <i id="scroll-to-top-btn"  class="fas fa-arrow-circle-up"></i>

        <!--
    <button id="scroll-to-top-btn" title="Go to top">
        <i class="fas fa-arrow-circle-up"></i>
       TOP
    </button>
-->
<?php
    if (!empty($scripts))
        array_unshift($scripts, "scripts.php");
    else
        $scripts = ["scripts.php"];
    foreach ($scripts as $script) {
        require_once $script;
    }
?>

</body>
</html>

<!--
<script>
  // importDoc references this import's document
  var footerDoc = document.currentScript.ownerDocument;

  // mainDoc references the main document (the page that's importing us)
  var mainDoc = document;

  // Grab the first stylesheet from this import, clone it,
  // and append it to the importing document.
  var styles = footerDoc.querySelector('link[rel="stylesheet"]');
  mainDoc.head.appendChild(styles.cloneNode(true));
  

function loadFooter() {
  var footer = footerDoc.querySelector('footer');  
  document.body.appendChild(footer.cloneNode(true));
}
  
    
</script>


-->