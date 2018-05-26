<?php

class Step2Controller{
    
    public function actionIndex(){
        
        $flopCard = [];                
        $flopCard[0] = array_shift($_SESSION['deck']);
        $flopCard[1] = array_shift($_SESSION['deck']);
        $flopCard[2] = array_shift($_SESSION['deck']);        
        $_SESSION['flop'] = $flopCard;
        
        require_once ROOT. '/views/viewStep2.php';        
        return TRUE;        
    }    
}