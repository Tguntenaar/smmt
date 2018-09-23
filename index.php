<!DOCTYPE html>
<html>
    <body>
        <h4>Test Request</h4> 

        <form action="" method="post">
            URL : <input type="text" name=url value="<?php echo isset($_POST['url']) ? $_POST['url'] : '' ?>" placeholder="Search..">
            <input type="submit" name="submit" value="Get Data!" />
        </form>    
        
        <?php
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include 'curlinfo.php';
                include 'mobilefriendly.php';
                include 'analyse.php';

                $url = test_input($_POST["url"]);

                $time_and_size = time_and_size_check($url);
                echo "<h4>Total Time Check: </h4>";
                echo "Result inhoud: ".$time_and_size[0]." seconds.";

                echo "<h4>Total Size Check: </h4>";
                echo "Result inhoud: ".$time_and_size[1]." bytes.";

                $mobile_friendly = mobile_ready_check($url, 'AIzaSyArsacdp79HPFfRZRvXaiLEjCD1LtDm3ww');
                echo "<h4>Mobile Friendly Check: </h4>";
                echo "Result inhoud: ".($mobile_friendly == 1 ? "True" : "False")."";

                // $value = crawl_page("$url", 3);
                echo "<h4>Google Analitics: </h4>";
                echo "Result inhoud: Krijg crawl_page niet werkend...";
            }
        ?>
    </body>
    
</html> 
