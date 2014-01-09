<?
  
  Class Insto {

    // do CURL request
    static public function doCurl($mode, $call, $arrParams) {

      $url = "https://api.insto.co.uk:3000/1595dd1cb1e713d09b6b20ab83c210b2/".$call;
      if($mode == "GET") {
        $url.="?".http_build_query($arrParams);
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      if($mode == "GET") {        
        curl_setopt($ch, CURLOPT_POST, false);
      } else {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arrParams));     
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);

      $arr = json_decode($output,1);

      return $arr;

    }

    // send to homescreen
    static public function sendHomescreen($data) {

      return Insto::doCurl("GET", "message/to/homescreen/yes", $data);

    }

    // query
    static public function query($data) {

      return Insto::doCurl("GET", "query", $data);

    }

  }

?>