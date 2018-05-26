<?php

class Step7Controller {

    private $combinations;
    private $cardValues;

    public function __construct() {
        
        $combinationsPath = ROOT . '/config/combinations.php';
        $this->combinations = include($combinationsPath);

        $cartValuesPath = ROOT . '/config/cartValues.php';
        $this->cardValues = include($cartValuesPath);
    }

    public function actionIndex() {
        
        if (isset($_SESSION['river']) and count($_SESSION['river']) == 5) {
            $playersCards = $this->getCardsForPlayers();

            foreach ($playersCards as $key => $value) {
                $playersRang[$key] = $this->getOnePlayerResult($value);
            }

            $rangCart = [];
            foreach ($playersRang as $key => $value) {
                $rangCart[$key] = $value[6];
            }
            array_multisort($rangCart, SORT_DESC, $playersRang);

            require_once ROOT . '/views/viewStep7.php';
            
        }
        return TRUE;
        
    }

    private function getCardsForPlayers() {

        if (isset($_SESSION['playersCard']['mainPlayer'])) {
            $playersCards['mainPlayer'] = $_SESSION['playersCard']['mainPlayer'];
        }
        if (isset($_SESSION['playersCard']['player1'])) {
            $playersCards['player1'] = $_SESSION['playersCard']['player1'];
        }
        if (isset($_SESSION['playersCard']['player2'])) {
            $playersCards['player2'] = $_SESSION['playersCard']['player2'];
        }
        if (isset($_SESSION['playersCard']['player3'])) {
            $playersCards['player3'] = $_SESSION['playersCard']['player3'];
        }
        if (isset($_SESSION['playersCard']['player4'])) {
            $playersCards['player4'] = $_SESSION['playersCard']['player4'];
        }
        if (isset($_SESSION['playersCard']['player5'])) {
            $playersCards['player5'] = $_SESSION['playersCard']['player5'];
        }

        return $playersCards;
    }
    
    private function combineCardsForOnePlayer($playerCards){
        
        $turn = $_SESSION['river'];
        
        $playerCardComb = [
            [$playerCards[0], $playerCards[1], $turn[0], $turn[1], $turn[2]],
            [$playerCards[0], $playerCards[1], $turn[0], $turn[1], $turn[3]],
            [$playerCards[0], $playerCards[1], $turn[0], $turn[1], $turn[4]],
            [$playerCards[0], $playerCards[1], $turn[0], $turn[2], $turn[3]],
            [$playerCards[0], $playerCards[1], $turn[0], $turn[2], $turn[4]],
            [$playerCards[0], $playerCards[1], $turn[0], $turn[3], $turn[4]],
            [$playerCards[0], $playerCards[1], $turn[1], $turn[2], $turn[3]],
            [$playerCards[0], $playerCards[1], $turn[1], $turn[2], $turn[4]],
            [$playerCards[0], $playerCards[1], $turn[1], $turn[3], $turn[4]],
            [$playerCards[0], $playerCards[1], $turn[2], $turn[3], $turn[4]],
            
        ];
        
        $sort = function($cards) {
            $rangCart = [];
            foreach ($cards as $key => $value) {
                $rangCart[$key] = $value[2];
            }            
            array_multisort($rangCart, SORT_DESC, $cards);
            return $cards;
        };
        
        $playerCardComb = array_map($sort, $playerCardComb);

        return $playerCardComb;
    }
    
    private function getOnePlayerResult($playerCard){
        
        
        $playerCardComb = $this->combineCardsForOnePlayer($playerCard);
                     
        $playerCardCombAnalise = $this->analiseALL($playerCardComb); 
        
        $playerCardRang = $this->printCombinations($playerCardCombAnalise);
        
        
        $playerHighestResult = array_shift($playerCardRang);
        
        return $playerHighestResult;
    }   

    
    private function analiseALL($playersCards) {

        $playersRangArray = [];

        foreach ($playersCards as $key => $value) {
            $playersRangArray [$key] = $this->analise($value);
        }

        return $playersRangArray;
    }

    private function printCombinations($playersRangArray) {

        $replaiceComb = function ($comb) {
            foreach ($this->combinations as $rang => $value) {
                $comb[0] = preg_replace("~$rang~", $value, $comb[0]);
            }
            return $comb;
        };

        $playersCardsValue = array_map($replaiceComb, $playersRangArray);

        $replaiceCards = function ($cards) {
            foreach ($this->cardValues as $rang => $valueCard) {
                foreach ($cards as $key => $value) {
                    $cards[$key] = preg_replace("~$rang~", $valueCard, $cards[$key]);
                }
            }
            return $cards;
        };

        $playersCardsValue = array_map($replaiceCards, $playersCardsValue);

        foreach ($playersRangArray as $key => $value) {
            $playersRangArray [$key] = implode('', $value);
        }

        foreach ($playersCardsValue as $key => $value) {
            $playersCardsValue[$key][6] = $playersRangArray[$key];
        }

        $rangCart = [];
        foreach ($playersCardsValue as $key => $value) {
            $rangCart[$key] = $value[6];
        }
        array_multisort($rangCart, SORT_DESC, $playersCardsValue);

        return $playersCardsValue;
    }   
    
    private function analise($playerCards) {

        $analise[] = function($playerCards) { //проверка на рояль
            if (
                    ($playerCards[0][2] == 12)
                    and ( $playerCards[0][2] == ($playerCards[1][2]) + 1)
                    and ( $playerCards[1][2] == ($playerCards[2][2]) + 1)
                    and ( $playerCards[2][2] == ($playerCards[3][2]) + 1)
                    and ( $playerCards[3][2] == ($playerCards[4][2]) + 1)
                    and ( $playerCards[0][0] === $playerCards[1][0])
                    and ( $playerCards[1][0] === $playerCards[2][0])
                    and ( $playerCards[2][0] === $playerCards[3][0])
                    and ( $playerCards[3][0] === $playerCards[4][0])
            ) {
                $result = ['10', $playerCards[0][2], $playerCards[1][2], $playerCards[2][2], $playerCards[3][2], $playerCards[4][2]];
                return $result;
            }
            return FALSE;
        };



        $analise[] = function($playerCards) { //проверка на стрит-флеш
            if (
                    ($playerCards[0][2] == ($playerCards[1][2]) + 1)
                    and ( $playerCards[1][2] == ($playerCards[2][2]) + 1)
                    and ( $playerCards[2][2] == ($playerCards[3][2]) + 1)
                    and ( $playerCards[3][2] == ($playerCards[4][2]) + 1)
                    and ( $playerCards[0][0] === $playerCards[1][0])
                    and ( $playerCards[1][0] === $playerCards[2][0])
                    and ( $playerCards[2][0] === $playerCards[3][0])
                    and ( $playerCards[3][0] === $playerCards[4][0])
            ) {
                $result = ['9', $playerCards[0][2], $playerCards[1][2], $playerCards[2][2], $playerCards[3][2], $playerCards[4][2]];
                return $result;
            }
            return FALSE;
        };



        $analise[] = function($playerCards) {//проверка на 4-ки
            for ($i = 0; $i <= 1; $i++) {
                if ($playerCards[$i][2] == $playerCards[$i + 1][2] &&
                        $playerCards[$i + 1][2] == $playerCards[$i + 2][2] &&
                        $playerCards[$i + 2][2] == $playerCards[$i + 3][2]) {

                    $result = ['8', $playerCards[$i][2], $playerCards[$i + 1][2], $playerCards[$i + 2][2], $playerCards[$i + 3][2]];
                    array_splice($playerCards, $i, 4);
                    $result [5] = $playerCards[0][2];
                    return $result;
                }
            }
            return FALSE;
        };


        $analise[] = function($playerCards) {//проверка на фулхфуз            
            for ($i = 0; $i <= 2; $i++) {
                if (($playerCards[$i][2] == $playerCards[$i + 1][2])
                        and ( $playerCards[$i + 1][2] == $playerCards[$i + 2][2])) {

                    $result = ['7', $playerCards[$i][2], $playerCards[$i + 1][2], $playerCards[$i + 2][2]];
                    array_splice($playerCards, $i, 3);
                    break;
                }
            }

            if ($playerCards[0][2] == $playerCards[1][2]) {
                $result [4] = $playerCards[0][2];
                $result [5] = $playerCards[1][2];
            }

            if (isset($result)) {
                if (count($result) == 6) {
                    return $result;
                } else {
                    return FALSE;
                }
            }
            return FALSE;
        };



        $analise[] = function($playerCards) { //проверка на флеш
            if (
                    ($playerCards[0][0] === $playerCards[1][0])
                    and ( $playerCards[1][0] === $playerCards[2][0])
                    and ( $playerCards[2][0] === $playerCards[3][0])
                    and ( $playerCards[3][0] === $playerCards[4][0])
            ) {
                $result = ['6', $playerCards[0][2], $playerCards[1][2], $playerCards[2][2], $playerCards[3][2], $playerCards[4][2]];
                return $result;
            }

            return FALSE;
        };



        $analise[] = function($playerCards) { //проверка на стрит
            if (
                    ($playerCards[0][2] == ($playerCards[1][2]) + 1)
                    and ( $playerCards[1][2] == ($playerCards[2][2]) + 1)
                    and ( $playerCards[2][2] == ($playerCards[3][2]) + 1)
                    and ( $playerCards[3][2] == ($playerCards[4][2]) + 1)
            ) {
                $result = ['5', $playerCards[0][2], $playerCards[1][2], $playerCards[2][2], $playerCards[3][2], $playerCards[4][2]];
                return $result;
            }

            return FALSE;
        };


        $analise[] = function($playerCards) {//проверка на 3-ки
            for ($i = 0; $i <= 2; $i++) {
                if (($playerCards[$i][2] == $playerCards[$i + 1][2])
                        and ( $playerCards[$i + 1][2] == $playerCards[$i + 2][2])) {

                    $result = ['4', $playerCards[$i][2], $playerCards[$i + 1][2], $playerCards[$i + 2][2]];
                    array_splice($playerCards, $i, 3);
                    $result [4] = $playerCards[0][2];
                    $result [5] = $playerCards[1][2];
                    return $result;
                }
            }
            return FALSE;
        };

        $analise[] = function($playerCards) { //проверка на две пары 
            for ($i = 0; $i <= 3; $i++) {
                if ($playerCards[$i][2] == $playerCards[$i + 1][2]) {
                    $result = ['3', $playerCards[$i][2], $playerCards[$i + 1][2]];
                    array_splice($playerCards, $i, 2);
                    break;
                }
            }

            for ($i = 0; $i <= 1; $i++) {
                if ($playerCards[$i][2] == $playerCards[$i + 1][2]) {

                    $result [3] = $playerCards[$i][2];
                    $result [4] = $playerCards[$i + 1][2];
                    array_splice($playerCards, $i, 2);
                    $result[5] = $playerCards[0][2];
                    break;
                }
            }

            if (isset($result)) {
                if (count($result) == 6) {
                    return $result;
                } else {
                    return FALSE;
                }
            }
            return FALSE;
        };


        $analise[] = function($playerCards) { //проверка на пары
            for ($i = 0; $i <= 3; $i++) {
                if ($playerCards[$i][2] == $playerCards[$i + 1][2]) {
                    $result = ['2', $playerCards[$i][2], $playerCards[$i + 1][2]];
                    array_splice($playerCards, $i, 2);
                    $result [3] = $playerCards[0][2];
                    $result [4] = $playerCards[1][2];
                    $result [5] = $playerCards[2][2];
                    return $result;
                }
            }
            return FALSE;
        };

        $analise[] = function ($playerCards) {//если совпадений не найдено то рейтинг по старшей карте
            $result = ['1', $playerCards[0][2], $playerCards[1][2], $playerCards[2][2], $playerCards[3][2], $playerCards[4][2]];
            return $result;
        };



        $result = FALSE;
        foreach ($analise as $value) { //прогонка по всем функциям
            if ($result === FALSE) {
                $result = $value($playerCards);
            }
        }

        return $result;
    }

}

