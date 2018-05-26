<?php

class Step4Controller{
    
    public function actionIndex(){
        
        $turnCard = $_SESSION['flop'];
        $turnCard[3] = array_shift($_SESSION['deck']);        
        $_SESSION['turn'] = $turnCard;
        unset($_SESSION['flop']);        
               
        require_once ROOT. '/views/viewStep4.php';        
        return TRUE;        
    }    
}