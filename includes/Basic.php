<?
  
  require_once("includes/DB.php");
  
  /*
    Yearbook.php
    Yearbook base class
  */
  if (!function_exists('json_encode'))
  {
    function json_encode($a=false)
    {
      if (is_null($a)) return 'null';
      if ($a === false) return 'false';
      if ($a === true) return 'true';
      if (is_scalar($a))
      {
        if (is_float($a))
        {
          // Always use "." for floats.
          return floatval(str_replace(",", ".", strval($a)));
        }

        if (is_string($a))
        {
          static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
          return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
        }
        else
          return $a;
      }
      $isList = true;
      for ($i = 0, reset($a); $i < count($a); $i++, next($a))
      {
        if (key($a) !== $i)
        {
          $isList = false;
          break;
        }
      }
      $result = array();
      if ($isList)
      {
        foreach ($a as $v) $result[] = json_encode($v);
        return '[' . join(',', $result) . ']';
      }
      else
      {
        foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
        return '{' . join(',', $result) . '}';
      }
    }
  }
  
  if ( !function_exists('json_decode') ){
    function json_decode($json)
    {
        $comment = false;
        $out = '$x=';
      
        for ($i=0; $i<strlen($json); $i++)
        {
            if (!$comment)
            {
                if (($json[$i] == '{') || ($json[$i] == '['))       $out .= ' array(';
                else if (($json[$i] == '}') || ($json[$i] == ']'))   $out .= ')';
                else if ($json[$i] == ':')    $out .= '=>';
                else                         $out .= $json[$i];          
            }
            else $out .= $json[$i];
            if ($json[$i] == '"' && $json[($i-1)]!="\\")    $comment = !$comment;
        }
        eval($out . ';');
        return $x;
    }
  }
  /*
   * JSON function?!
   */
  
  
  
  abstract Class Basic {
    
    //constants
    const STATUS_UNCHANGED = 'unchanged';
    const STATUS_CHANGED = 'changed';
    const STATUS_NEW = 'new';
    const STATUS_DELETED = 'deleted';
    
    //protected
    protected $db;
    protected $saveStatus;
    
    function __construct() {
      
      //DB connection
      //$this->db = new DB();
      $this->saveStatus = Basic::STATUS_UNCHANGED;
    }
    
    //mark unchanged
    public function markUnchanged() {
      $this->saveStatus = Basic::STATUS_UNCHANGED;
    }
    
    //mark unchanged
    public function markDirty() {
      $this->saveStatus = Basic::STATUS_CHANGED;
    }
    
    //mark unchanged
    public function markNew() {
      $this->saveStatus = Basic::STATUS_NEW;
    }
    
    //mark unchanged
    public function markDeleted() {
      $this->saveStatus = Basic::STATUS_DELETED;
    }
    
    //save object
    public function save() {
      
      //create
      if ($this->saveStatus == Basic::STATUS_NEW) {
        return $this->saveDB();
      }
      
      //update
      else if ($this->saveStatus == Basic::STATUS_CHANGED) {
        return $this->updateDB();
      }
      
      //delete
      else if ($this->saveStatus == Basic::STATUS_DELETED) {
        return $this->deleteDB();
      }
      
      //no change
      else {
        return false;
      }
    }
    
    //abstract save function
    abstract protected function saveDB();
    abstract protected function updateDB();
    abstract protected function deleteDB();
    
  }
  
?>