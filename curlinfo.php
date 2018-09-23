<?php
    function time_and_size_check($url) 
    {
        $curl = curl_init();    
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_FILETIME       => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        ));

        $header = curl_exec($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);
        return [$info['total_time'], $info['size_download']];
    }
?>
