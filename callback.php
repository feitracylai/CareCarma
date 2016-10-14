<?php
session_start();
?>


                            <?php
                            $accesstoken = '';
                            $client_id = '584594431619-c8gb5m52css0vs8biotp7jcie27h0iff.apps.googleusercontent.com';
                            $client_secret = 'gCqIX4YrqNH-8mYO91O_WBOJ';
                            $redirect_uri = 'http://127.0.0.1:8888/CareCarma/callback.php';
                            $simple_api_key = 'AIzaSyCdNyA6NGy8ie9ZcsSEh3adbdTXxn3LKUY';
                            $max_results = 500;
                            $auth_code = $_GET["code"];

                            function curl_file_get_contents($url) {
                                $curl = curl_init();
                                $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

                                curl_setopt($curl, CURLOPT_URL, $url);   //The URL to fetch. This can also be set when initializing a session with curl_init().
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);   //The number of seconds to wait while trying to connect.    

                                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);  //To follow any "Location: " header that the server sends as part of the HTTP header.
                                curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
                                curl_setopt($curl, CURLOPT_TIMEOUT, 10);   //The maximum number of seconds to allow cURL functions to execute.
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

                                $contents = curl_exec($curl);
                                curl_close($curl);
                                return $contents;
                            }

                            $fields = array(
                                'code' => urlencode($auth_code),
                                'client_id' => urlencode($client_id),
                                'client_secret' => urlencode($client_secret),
                                'redirect_uri' => urlencode($redirect_uri),
                                'grant_type' => urlencode('authorization_code')
                            );
                            $post = '';
                            foreach ($fields as $key => $value) {
                                $post .= $key . '=' . $value . '&';
                            }
                            $post = rtrim($post, '&');

                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
                            curl_setopt($curl, CURLOPT_POST, 5);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            $result = curl_exec($curl);

                            curl_close($curl);

                            $response = json_decode($result);
                            if (isset($response->access_token)) {
                                $accesstoken = $response->access_token;
                                $_SESSION['access_token'] = $response->access_token;
                            }


                            if (isset($_GET['code'])) {


                                $accesstoken = $_SESSION['access_token'];
                            }

                            if (isset($_REQUEST['logout'])) {
                                unset($_SESSION['access_token']);
                            }












                            $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . $max_results . '&oauth_token=' . $accesstoken;
                            $xmlresponse = curl_file_get_contents($url);

                            if ((strlen(stristr($xmlresponse, 'Authorization required')) > 0) && (strlen(stristr($xmlresponse, 'Error ')) > 0)) {
                                echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
                                exit();
                            }

                            //echo " <a href ='http://127.0.0.1/gmail_contact/callback.php?downloadcsv=1&code=4/eK2ugUwI_qiV1kE3fDa_92geg7s1DusDsN9BHzGrrTE# '><img src='images/excelimg.jpg' alt=''id ='downcsv'/></a>";
                            // echo "<h3>Email Addresses:</h3>";
                            $xml = new SimpleXMLElement($xmlresponse);
                            $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');

                            $result = $xml->xpath('//gd:email');

                            $output_array = array();
                            foreach ($xml->entry as $entry) {
                                foreach ($entry->xpath('gd:email') as $email) {
                                    $output_array[] = array(
                                        (string)$entry->title,
                                        (string)$entry->attributes()->href,
                                        (string)$email->attributes()->address);

                                }
                            }
                            print_r($output_array);




                            foreach ($result as $title) {
                                $arr[] = $title->attributes()->address;
//                                print_r($title->attributes()->title);
                                echo $title->attributes()->displayName;
                            }
//                            print_r($arr);
                            foreach ($arr as $key) {
//                                echo $key."<br>";
                            }

                            $response_array = json_decode(json_encode($arr), true);

                            // echo "<pre>";
                            // print_r($response_array);
                            //echo "</pre>";

                            $email_list = '';
                            foreach ($response_array as $value2) {

                                $email_list = ($value2[0] . ",") . $email_list;
                            }


                            //echo $abc;
                            // $final_array[] = $abc;
                            // $farr =$final_array;
                            //echo "<pre>";
                            //print_r($final_array);
                            // echo "</pre>";
                            //<input type="text" value="<?php echo ($abc);?" name="email">
                            ?>