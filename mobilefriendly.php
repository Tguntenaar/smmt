<?php
    function mobile_ready_check($url, $apiKey)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.googleapis.com/pagespeedonline/v3beta1/mobileReady?key='.$apiKey.'&url='.$url.'&strategy=mobile',
            CURLOPT_RETURNTRANSFER => 1,
        ));
        
        $resp = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($resp, true);
        return $result['ruleGroups']['USABILITY']['pass'];
    }
?>
