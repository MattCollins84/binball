<?

  /*
   *  View class
   */
 
  require_once("controllers/Controller.php");
  
  Class View {
    
    //Render a specific view
    static public function renderView($view, $data=array(), $noHeaderOrFooter=false, $adminHeaders=false) {
      
			global $config;

      //check that this view exists
      if (!file_exists("views/".$view.".php")) {
        
        //doesn't exist, 404
        return View::render404($view, $data);
        die();
        
      }
      
      //render the view
      ob_start();
      if ($noHeaderOrFooter === false) {
        if ($adminHeaders === true) {
          include("views/header_admin.php");
        } else {
          include("views/header.php");
        }
        include("views/".$view.".php");
        if ($adminHeaders === true) {
          include("views/footer_admin.php");
        } else {
          include("views/footer.php");
        }
      } else {
        include("views/".$view.".php");
      }
      $view = ob_get_clean();
      //return
      return $view;
        
    }
    
    //Render 404 - not found
    static public function render404($view="", $data=array()) {
      
      if ($view) {
        $data['_view'] = $view;
      }
      
      echo View::renderView("404", $data);
      
    }
    
    //Render 503 - service unavailable
    static public function render503($view="", $data=array()) {
      
      if ($view) {
        $data['_view'] = $view;
      }
      
      echo View::renderView("503", $data);
      
    }
    
    //Render 403 - Forbidden
    static public function render403($view="", $data=array()) {
      
      if ($view) {
        $data['_view'] = $view;
      }
      
      echo View::renderView("403", $data);
      
    }
    
  }

?>