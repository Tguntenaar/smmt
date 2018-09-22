<?php

function crawl_page($url, $depth = 5)
{
      static $seen = array();
      if (isset($seen[$url]) || $depth === 0) {
        return;
      }

      $seen[$url] = true;

      $dom = new DOMDocument('1.0');
      @$dom->loadHTMLFile($url);

      $anchors = $dom->getElementsByTagName('a');
      foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        if (0 !== strpos($href, 'http')) {
           /* this is where I changed hobodave's code */
            $host = "http://".parse_url($url,PHP_URL_HOST);
            $href = $host. '/' . ltrim($href, '/');
        }

        if (preg_match('/\bfacebook\b/', $href) ||
            preg_match('/\binstagram\b/', $href) ||
            preg_match('/\bpinterest\b/', $href) ||
            preg_match('/\bgoogle\b/', $href)){
            continue;
        }

        crawl_page($href, $depth - 1);
      }

      echo "New Page:<br /> ";
      echo "URL:",$url,PHP_EOL,"<br />";

      $source = $dom->saveHTML();


    if (preg_match('/\bgtm.js\b/',$source)){
        echo "Found Google Tag manager<br />";
    } else {
        echo "Didn´t found Google tag manager<br />";
    }
     if (preg_match('/\bconnect.facebook.net\b/',$source)){
         echo "Found Facebook Pixel<br />";
     } else {
         echo "Didn´t found Facebook Pixel<br />";
     }

      if(preg_match("/\bUA-\b/i", $source) ){
        echo "Google analytics found on page.<br /><br />";
      } else {
        echo "Google Analytics was not found on page.<br /><br />";
      }

    //   echo file_get_html('https://bobvanbuul.nl/')->plaintext;
    //   echo "CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL,"  <br /><br />";
}

crawl_page("http://mooibenjij.nl/", 4);
?>
