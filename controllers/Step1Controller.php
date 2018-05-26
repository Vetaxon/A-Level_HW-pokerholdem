<?php

class Step1Controller {

    public function __construct() {

        $suitList = ["Spade", "Heart", "Club", "Diamond"];
        $cardList = ['00' => '2', '01' => '3', '02' => '4', '03' => '5', '04' => '6', '05' => '7', '06' => '8', '07' => '9', '08' => '10', '09' => 'Jack', '11' => 'Queen', '12' => 'King', '13' => 'Ace'];

        foreach ($suitList as $suit) {
            foreach ($cardList as $key => $rank) {
                $cardDeck[] = [$suit, $rank, $key];
            }
        }

        shuffle($cardDeck);
        $_SESSION['deck'] = $cardDeck;
        return TRUE;
    }
    
    public function actionIndex() {
        
        $playersCard = [];
        
        $playersCard['mainPlayer'] = $this->getCardsForPlayer();
        $playersCard['player1'] = $this->getCardsForPlayer();
        $playersCard['player2'] = $this->getCardsForPlayer();
        $playersCard['player3'] = $this->getCardsForPlayer();
        $playersCard['player4'] = $this->getCardsForPlayer();
        $playersCard['player5'] = $this->getCardsForPlayer();

        $_SESSION['playersCard'] = $playersCard;
        
        require_once ROOT. '/views/viewStep1.php';
        return TRUE;
    }   
    
    private function getOneCard() {
        return array_shift($_SESSION['deck']);
    }

    private function getCardsForPlayer() {
        $playerCard = [];
        $playerCard[0] = $this->getOneCard();
        $playerCard[1] = $this->getOneCard();
        return $playerCard;
    }
}
