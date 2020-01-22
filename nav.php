<div class="uk-offcanvas-content">
  <!-- menu position. delete .uk-light to change black navbar to white (also you should change logo to dark one)-->
  <nav class="uk-navbar-container uk-light" uk-navbar="mode: click" uk-sticky="animation: uk-animation-slide-top; show-on-up: true">
    <!-- menu-->
    <div class="uk-navbar-left nav-overlay">
      <div class="uk-navbar-flip">
        <ul class="uk-navbar-nav uk-visible@s">
          <li class="uk-active"><a href="index.php">HOME</a></li>
          <li><a href="post.php">POST &nbsp;<i class="far fa-plus-square"></i> </a></li>
          <li><a href="#">Item 3</a></li>
          <li><a href="#">Item 4</a></li>
          <li><a href="#">Item 5</a></li>
          <li><a href="#">Item 6</a></li>

        </ul>
        <ul class="uk-navbar-nav uk-hidden@s">

          <li><a class="uk-navbar-toggle" uk-navbar-toggle-icon uk-toggle="target: #mobile-navbar"></a></li>
        </ul>
      </div>
    </div>
    <!-- endmenu-->
    <!-- logo or title-->
    <div class="uk-navbar-right nav-overlay"><a class="uk-navbar-item uk-logo" href="#">
        <!--h3 Site Name--><img src="img/profile/feuille.png" alt="logo" width="64"></a></div>
    <!-- end logo or title-->

  </nav>
  <!-- end menu position-->
  <!-- off-canvas menu-->
  <div id="mobile-navbar" uk-offcanvas="mode: slide; flip: false">
    <div class="uk-offcanvas-bar">
      <!-- off-canvas close button-->

      <!-- off-canvas close button-->
      <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
        <!-- logo or title-->
        <li class="uk-text-center" style="padding: 0 0 25px 0;"><a href="#">
            <!--h3 Site Name--><img src="http://blog.codepen.io/wp-content/uploads/2012/06/Button-Fill-White-Large.png" alt="logo" width="64"></a></li>
        <!-- end logo or title-->
        <!-- menu-->
        <li>
          <hr>
        </li>
        <li class="uk-text-center">
          <h3>Menu</h3>
        </li>
        <li class="uk-active"><a href="index.php">HOME
            <!--span.uk-light(uk-icon="icon: pencil")-->
            <!--| #{" "}Item 1--></a></li>
        <li><a href="post.php">POST
            <!--span.uk-light(uk-icon="icon: code")-->
            <!--| #{" "}Item #{i}#{j}--></a></li>
        <li><a href="#">Item 3
            <!--span.uk-light(uk-icon="icon: code")-->
            <!--| #{" "}Item #{i}#{j}--></a></li>
        <li><a href="#">Item 4
            <!--span.uk-light(uk-icon="icon: code")-->
            <!--| #{" "}Item #{i}#{j}--></a></li>
        <li><a href="#">Item 5
            <!--span.uk-light(uk-icon="icon: code")-->
            <!--| #{" "}Item #{i}#{j}--></a></li>
        <li><a href="#">Item 6
            <!--span.uk-light(uk-icon="icon: code")-->
            <!--| #{" "}Item #{i}#{j}--></a></li>
      </ul>
    </div>
  </div>