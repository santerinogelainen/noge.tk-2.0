<?php


class Finder {
    
     private $finder = array();
     
     
     function __construct($needle, $haystack) {
         if (!isset($needle)) {
             throw new Exception("Needle not set.");
         }
         if (!isset($haystack)) {
             throw new Exception("Haystack not set.");
         }
         $this->objectFinder($needle, $haystack);
     }
    
    
    
      //objectFinder recursivally searches for values in an object
      private function objectFinder($keys, $object, $title = "", $preserveresults = 0) {
        
        //init $this->curarrayfinder variable
        if ($preserveresults == 1) {
          $this->finder["value"] = array();
        } else if ($preserveresults == 0) {
          $this->finder["title"] = $title;
          $this->finder["value"] = $object;
        }
        
        //foreach key given
        //example: array("content", "this" => array("color")) (2 keys, 3 keys after recursion)
        //example: array("color", "this" => array()) (2 keys)
        //example: array("myelement", "colors", "background-color") (3 keys)
        foreach ($keys as $index => $key) {
          if (is_array($key)) {
            
            if ($index == "this") {
              
              $i = 1;
              foreach ($this->finder["value"] as $innerindex => $value) {
                if (!empty($key)) {
                    $this->objectFinder($key, $value, $innerindex, $i);
                } else if ($i == 1) {
                    $this->finder["value"] = array();
                    $this->finder["title"] .= "{KEY}->";
                    $this->finder["value"][$innerindex] = $value;
                } else {
                    $this->finder["value"][$innerindex] = $value;
                }
                $i++;
              }
              
            } else {
              if ($preserveresults) {
                $this->objectFinder($key, $this->finder["value"][$title], $title, 2);
              } else {
                $this->objectFinder($key, $this->finder["value"], $this->finder["title"]);
              }
            }
            
          } else {
            
            if ($preserveresults == 1 && $index == 0) {
              $this->finder["title"] .= "{KEY}->" . $key . "->";
            } else if ($preserveresults == 1 && $index > 0) {
              $this->finder["title"] .= $key . "->";
            }
            
            if ($preserveresults && $index == 0) {
              $this->finder["value"][$title] = $object->$key;

            } else if ($preserveresults && $index > 0) {
              $this->finder["value"][$title] = $this->curarrayfinder["value"][$title]->$key;
              
            } else if ($index == 0) {
              $this->finder["value"] = $object->$key;
              $this->finder["title"] .= $key . "->";
            
            } else {
              $this->finder["value"] = $this->curarrayfinder["value"]->$key;
              $this->finder["title"] .= $key . "->";
            }
            
          }
        }
      }
      
      
      public function getResults() {
          return $this->finder;
      }
    
}


?>