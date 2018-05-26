<?php

class Step6Controller{
    
    public function actionIndex(){
        
        $riverCard = $_SESSION['turn'];
        $riverCard[4] = array_shift($_SESSION['deck']);        
        $_SESSION['river'] = $riverCard;
        unset($_SESSION['turn']);        
               
        require_once ROOT. '/views/viewStep6.php';        
        return TRUE;        
    }    
}
