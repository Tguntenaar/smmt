<?php
/**
 * @param $url
 * @param $apiKey
 * @return mixed
 */
function isMobileReady($url, $apiKey)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://www.googleapis.com/pagespeedonline/v3beta1/mobileReady?key='.$apiKey.'&url='.$url.'&strategy=mobile',
    ));
    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}

//result as an array
$result = json_decode(isMobileReady('http://mooibenjij.nl/', 'AIzaSyArsacdp79HPFfRZRvXaiLEjCD1LtDm3ww'), true);
$test = base64_decode($result['screenshot']['data']);

// echo '<img src="data:image/png;base64,'. $test .'" />';
echo '<img src="data:image/jpeg;base64,'. $result['screenshot']['data'].'" width="360px" height="202px" />';
// echo $result['screenshot']['data'];
// var_dump($result);
?>
<!-- <?php
$hex = substr($hex, 2);
$binary =  pack("H*", $hex);
?>
<img src="data:image/jpeg;base64,<?php echo base64_encode($result['screenshot']['data']);?>"> -->
