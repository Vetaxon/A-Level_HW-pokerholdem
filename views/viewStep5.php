<?php 

?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

        <title>Poker holdem</title>
    </head>
    <body>
        <br>
        <h1 class="container bgcont center-block">Poker</h1><hr>
        
        <div class="container bgcont center-block">
            <h3 class="container">Turn:</h3>
            <div class="container">
                <?php foreach ($_SESSION['turn'] as $key => $value): ?>
                    <?php echo "$value[1] $value[0], "; ?>                        
                <?php endforeach; ?>
            </div><hr>

        </div>

    </div>
    <div class="container bgcont center-block">
        <h4 class="container">Cards of players:</h4><br>
        <div class="container">
            <?php foreach ($_SESSION['playersCard'] as $key => $value): ?>
                <div class="container">
                    <?php echo "For $key: "; ?>
                    <?php foreach ($value as $key1 => $value1) {
                        echo "$value1[1] $value1[0], ";
                    } ?>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>        
        
    </div>
    <div class="container bgcont center-block">
        <h4 class="container">Rang of players on Turn:</h4>
        <div class="container">
            <?php $i = 1;?>
            <?php foreach ($playersRang as $key => $value): ?>
                <div class="container">
                    <?php echo $i++ . ") $key - $value[0]: $value[1], $value[2], $value[3], $value[4], $value[5], rang - $value[6];"; ?>
                    
                    <br>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
        <div>
            <p><a href="step6">River</a></p>
            <p><a href="start">To start</a></p>
            <hr>
        </div>

    </div>        
    <br><hr>


</body>
</html>
