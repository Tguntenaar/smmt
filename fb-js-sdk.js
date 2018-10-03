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
    businessDiscovery('tyronvan');
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
    console.log('Successful login for: ' + response.name);
    document.getElementById('status').innerHTML =
      'Thanks for logging in, ' + response.name + '!';
  });
}


/*This function computes average engagement.*/
function averageEngagement(likes, comments, total) {
  return (likes + comments) / total
}


/*
This function gets all of the hashtags.
*/
function getHashtags(string) {
  var re = /(#[a-zA-Z]+)/gm;
  var m;
  var counts = {};

  // Read all hashtags out of string.
  while ((m = re.exec(string)) != null) {
    if (m.index === re.lastIndex) {
      re.lastIndex++;
    }

    // count the frequency of every hashtag.
    for (var i = 0; i < m.length; i++) {
      var num = m[i];
      counts[num] = counts[num] ? counts[num] + 1 : 1;
    }
  }

  // dictioniary into 2D list.
  var sortable = [];
  for (var key in counts) {
    sortable.push([key, counts[key]]);
  }

  // sort list
  sortable.sort(function(a, b) {
      return b[1] - a[1];
  });

  // TODO fix als iemand onder de 5 hashtags gebruikt.
  try {
    console.log("Number of hashtags");
    console.log(sortable.length);
    console.log("Top 5 hashtags used last month.");
    for (var i = 0; i < smallest(sortable.length, 5); i++) {
      console.log(sortable[i][0] + " used: " + sortable[i][1] + " times");
    }
  } catch (e) {
      console.log("HASHTAG ERROR")
  }
}

// Return the smallest of two numbers.
function smallest(int1, int2) {
  if (int1 < int2) {return int1;}
  return int2;
}

/*
This function unpacks the media object of an instagram business profile.
*/
function unpackMedia(media) {
  var totalLikes = 0;
  var totalComments = 0;
  var likesLastMonth = 0;
  var commentsLastMonth = 0;
  var numberOfPostsLastMonth = 0;
  var allCaptions = "";

  for (key in media) {
    if (media.hasOwnProperty(key) && key === "data") {
      var currentObject = 0;

      // Data is an array. Media is a dictioniary.
      const data = media[key].values();

      for (const object of data) {
        // Data is an array of objects (dictioniaries).
        for (key in object) {
          if (key === 'like_count') {
            if (object === currentObject) {
              likesLastMonth += object[key];
            }
            totalLikes += object[key];
          } else if (key === 'comments_count') {
            if (object === currentObject) {
              commentsLastMonth += object[key];
            }
            totalComments += object[key];
          } else if (key === 'timestamp') {

            var now = new Date();
            var d = new Date(object[key]);

            // check if it is from last month.
            if (d.getFullYear() === now.getFullYear() &&
                d.getMonth() == now.getMonth() - 1) {
              numberOfPostsLastMonth += 1;
              currentObject = object;
            }
          } else if (key === 'caption') {
            allCaptions += object[key];
          } else { // key is id
            // console.log(key);
          }
        }
      }

    } // else als het paging is.
  }
  avg = averageEngagement(totalLikes, totalComments, 25);
  avgMonth = averageEngagement(likesLastMonth, commentsLastMonth, numberOfPostsLastMonth);
  console.log('averageEngagement');
  console.log(avg);
  console.log('totalLikes');
  console.log(totalLikes);
  console.log('totalComments');
  console.log(totalComments);
  console.log('totalPosts checked');
  console.log(25); // TODO omdat pagination gebeuren met instagram.
  console.log('averageEngagement last month');
  console.log(avgMonth);
  console.log('likesLastMonth');
  console.log(likesLastMonth);
  console.log('commentsLastMonth');
  console.log(commentsLastMonth);
  console.log('numberOfPostsLastMonth');
  console.log(numberOfPostsLastMonth);

  try {
    getHashtags(allCaptions);
  } catch (e) {
    console.log(e);
  }
}


/*
This function discovery info about a given instagram business profile. (username)
*/
function businessDiscovery(businessName) {
  string2 = ""
  string = "17841400714813297?fields=business_discovery.username(" + businessName + "){username, media_count, followers_count, follows_count, media{timestamp, like_count, comments_count, caption}}";
  FB.api( string, function (response) {
      if (response && !response.error) {

        try {
          for (key in response.business_discovery) {
            if (response.business_discovery.hasOwnProperty(key)) {
              if (key === 'media') {
                unpackMedia(response.business_discovery[key]);
              } else if (key === 'id') {
                // console.log(response.business_discovery[key])
              } else {
                console.log(key);
                console.log(response.business_discovery[key]);
                // string2 += key + ' => ' + response.business_discovery[key]; TODO
              }
            }
          }
        } catch (e) {
          console.log(e);
        }

        document.getElementById("info").innerHTML = string2; // TODO

      } else {
        console.log(response.error);
      }
    }
  );
}
