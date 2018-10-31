<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Login JavaScript Example</title>
    <meta charset="UTF-8">
    
  </head>
  <body>
    <script>
      // This is called with the results from from FB.getLoginStatus().
      function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
          // Logged into your app and Facebook.
          testAPI();
          // getPageID();

          // fbAPI();
        } else {
          // The person is not logged into your app or we are unable to tell.
          document.getElementById('status').innerHTML = 'Please log ' +
            'into this app.';
        }
      }

      // This function is called when someone finishes with the Login
      // Button.  See the onlogin handler attached to it in the sample
      // code below.
      function checkLoginState() {
        FB.getLoginStatus(function(response) {
          statusChangeCallback(response);
        });
      }

      window.fbAsyncInit = function() {
        FB.init({
          appId      : '277584586167398',
          cookie     : true,  // enable cookies to allow the server to access
                              // the session
          xfbml      : true,  // parse social plugins on this page
          version    : 'v3.1' // use graph api version 2.8
        });

        // Now that we've initialized the JavaScript SDK, we call
        // FB.getLoginStatus().  This function gets the state of the
        // person visiting this page and can return one of three states to
        // the callback you provide.  They can be:
        //
        // 1. Logged into your app ('connected')
        // 2. Logged into Facebook, but not your app ('not_authorized')
        // 3. Not logged into Facebook and can't tell if they are logged into
        //    your app or not.
        //
        // These three cases are handled in the callback function.

        FB.getLoginStatus(function(response) {
          statusChangeCallback(response);
        });

      };

      // Load the SDK asynchronously
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));

      // Here we run a very simple test of the Graph API after login is
      // successful.  See statusChangeCallback() for when this call is made.
      function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
          console.log(JSON.stringify(response));
          console.log('Successful login for: ' + response.name);
          document.getElementById('status').innerHTML =
            'Thanks for logging in, ' + response.name + '!';
        });
      }

      function getQuerieDate(m) {
        var date = new Date();
        day = date.getDate();
        year = date.getFullYear();
        monthNumber = date.getMonth();
        month = getNameMonth(monthNumber + m);
        dateString = day + "-" + month + "-" + year;
        return dateString;
      }

      function getYesterDate() {
        var date = new Date();
        day = date.getDate();
        year = date.getFullYear();
        monthNumber = date.getMonth();
        month = getNameMonth(monthNumber);
        return day + "-" + month + "-" + year;
      }

      function getNameMonth(d) {
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";
        var n = month[d];
        return n;
      }

      function getPageID() {
        console.log('%c INSTAGRAM', 'color: blue; font-weight: bold;');
        console.log('Fetching your page id.... ');

        var id = 0;

        FB.api('/me/accounts', function(response) {
          if (!response || response.error) {
            alert('Error occured retrieving your page id.');
            console.log("%c " + reponse + " ", 'color: red');
          } else {
            // TODO data[0] misschien andere page?? zoek een instagram business
            // account tussen alle pages.
            var id = response.data[0].id;
            responseInJSON = getBusAccID(id);
            // console.log(JSON.stringify(response));
          }
        });
      }


      // This function gets the id of the instagram business account than uses
      // that data to find the instagram data.
      function getBusAccID(id) {
        console.log('%c page id :' + id, 'color: blue');
        console.log('Fetching your business account id.... ');

        var querie = '/' + id + '?fields=instagram_business_account';

        FB.api(querie, function(response) {
          if (!response || response.error) {
            alert('Error occured retrieving your instagram account id.');
            console.log("%c " + reponse + " ", 'color: red');
          } else {
            // console.log(JSON.stringify(response));
            instaAPI('tyronvan', response.instagram_business_account.id);
          }
        });
      }

      // Makes the instagram call.
      function instaAPI(businessName, iba_id) {

        console.log('%c insta business account id :' + iba_id, 'color: blue');
        console.log('Making insta request..');

        <?php //echo $businessName; ?>
        querie = iba_id + '?fields=business_discovery.username(' + businessName + '){username, media_count, followers_count, follows_count, media{timestamp, like_count, comments_count, caption}}';


        FB.api(querie, function(response) {
          if (!response || response.error) {
            alert('Error occured retrieving ' + businessName + '\'s info.');
            console.log("%c " + reponse + " ", 'color: red');
          } else {

            responseInJSON = JSON.stringify(response);
            // console.log(responseInJSON);
            var instaCookie = "instagram=" + responseInJSON;
            document.cookie = instaCookie;
          }
        });
      }

      <?php $pagina_naam = '1083104585201499' ?> // guntenaarmakelaars id?

      function fbAPI() {
        console.log('%c FACEBOOK', 'color: red; font-weight: bold;');
        console.log('Making fb requests..');

        var lastMonth = getQuerieDate(-1);
        var yesterday = getYesterDate();
        var now = getQuerieDate(0);

        var querie = '/<?php echo $pagina_naam; ?>/feed';
        var querie2 = '/<?php echo $pagina_naam; ?>/feed?since=' + yesterday;
        var querie3 = '/<?php echo $pagina_naam; ?>/feed?since=' + lastMonth + '&until=' + now;

        var querie4 =  '/<?php echo $pagina_naam; ?>/albums?fields=id,name,cover_photo.fields(images)'; // &limit=2 loesoe\\ for elke object in response.data en moet in dat object name === 'Cover Photos'

        var querie5 = '/<?php echo $pagina_naam; ?>?fields=picture{height,width}';
        var querie6 = '/<?php echo $pagina_naam; ?>?fields=country_page_likes';
        var querie7 = '/<?php echo $pagina_naam; ?>?fields=posts{message, type} &since=' + lastMonth + '&until=' + now;

        var querietotaal = '/<?php echo $pagina_naam; ?>?fields=country_page_likes,picture{height,width},posts{message, type}&since=' + lastMonth + '&until=' + now + '';


        FB.api(querie3, function(response) {
          var string = "fbFeed=" + JSON.stringify(response);
          document.cookie = string;
        });
        FB.api(querie4, function(response) {
          var string = "fbAlbumsFields=" + JSON.stringify(data);
          document.cookie = string;
        });
        FB.api(querietotaal, function(response) {
          var string = "fbPageFields=" + JSON.stringify(data);
          document.cookie = string;
        });

      }


    </script>


    <!--
      Below we include the Login Button social plugin. This button uses
      the JavaScript SDK to present a graphical Login button that triggers
      the FB.login() function when clicked.
    -->

    <!-- <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
    </fb:login-button> -->

    <div class="fb-login-button" data-width="900" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="false"></div>

    <div id="status"></div>

    <div class="">
      <button type="button" name="button" onclick="sendCookie()"></button>

    </div>

    <div class="">
      <p>
        <?php
          echo "instagram:";
          echo $_COOKIE['instagram'];
          echo 'facebook:'
          echo $_COOKIE['fbFeed'];
          echo $_COOKIE['fbAlbumsFields'];
          echo $_COOKIE['fbPageFields'];
         ?>
      </p>
    </div>
  </body>
</html>
