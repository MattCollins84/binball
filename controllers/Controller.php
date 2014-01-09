<?

  /*
   *  Abstract class on which all of our controllers are based
   */
  
  require_once("includes/View.php");
  
  Abstract Class Controller {
  
    static public function redirect302($url) {
      header("Location: ".$url);
    }
    
    static public function redirect301($url) {
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: ".$url);
    }
  
    static public function show503() {
      $data = array();
      echo View::renderView("503", $data);
    }
    
    static public function show403() {
      $data = array();
      echo View::renderView("403", $data);
    } 

		 //Work out styling for a brand
    static public function calculateStyling() {

      $data = array();

			// CI - styling set as default
			$data['display_name'] = 'CentralIndex';
			$data['css_name'] = 'centralindex';
			$data['link_contact'] = 'http://admin.centralindex.com/contact';
			$data['link_register'] = 'http://business.localmole.co.uk/';

      // if we have masheryid in session, then use this to feed the styling
/*      if($_SESSION['masheryid']) {
        switch($_SESSION['masheryid']) {
          case "jpress": 
            $data['display_name'] = 'Johnston Press';
      			$data['css_name'] = 'jpress';
          break;
          
          case "enablemedia": 
            $data['display_name'] = 'Scoot';
      			$data['css_name'] = 'enablemedia';
          break;
          
          case "yourlocalie": 
            $data['display_name'] = 'Johnston Press';
      			$data['css_name'] = 'yourlocalie';
          break;
          
          default:
          break;
        }
      }*/
 			return $data;
    
    }      
  
  }

?>