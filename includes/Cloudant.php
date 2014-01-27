<?
  require_once("includes/config.php");
  
  Class Cloudant {

    static public function doCurl($method, $collection, $data, $params = array()) {

      global $config;

      $CLOUDANT_URL = $config['cloudant'];

      $ch = curl_init();

      if (is_array($data) && count($data)) {
        $payload = json_encode($data);
      }

      $url = $CLOUDANT_URL.$collection;

      if($method == "GET" && is_array($params) && count($params) > 0) {
        $url.="?".http_build_query($params);
      }

      //echo $url."<br />";
      
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); /* or PUT */
      if ($payload) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-type: application/json',
          'Accept: */*'
      ));
      curl_setopt($ch, CURLOPT_USERPWD, 'binball:binballwizard');
      $response = curl_exec($ch);
      curl_close($ch);

      return json_decode($response, true);

    }

  }

?>