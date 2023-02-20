<?php
require_once '../components/guestHeader.php';
?>
<div class="container-fluid p-0">
    <div class="welcome-div h1 border-bottom border-white text-light d-flex align-items-center justify-content-center animate__animated animate__zoomIn"> Üdvözöljük a Kunsziget SE honlapján!</div>
</div>
<div class="container mainContainer p-3 my-3 rounded text-dark">
    <div class="container">

        <div class="row bg-light p-3 my-5 rounded">
            <h1 class="display-3">Felnőtt csapat</h1>
            <hr>
            <div class="row">
                <div class="col-10">
                    <div class="row">
                        <h3 class="display-5">Utolsó mérkőzés:</h3>
                    </div>
                    <?php
                    $match = getLatestGame($conn, "Felnőtt");
                    if ($match) {
                        $result = getGoalScorers($conn, $match["match_ID"]);
                    

                    ?>
                    <div class="row">
                        <h3 class="display-5 mt-0"> <?php echo $match["match_place"] . ", " . $match["match_date"]; ?></h3>
                    </div>
                    <div class="row display-6">
                        <div class="col-5 text-center">
                            <?php echo $match["match_homeTeam"]; ?>
                        </div>
                        <div class="col-2 text-center">
                            <?php echo $match["match_homeTeamGoals"] . "-" . $match["match_awayTeamGoals"]; ?>
                        </div>
                        <div class="col-5 text-center">
                            <?php echo $match["match_awayTeam"]; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mt-2 text-center">
                            <?php
                            while ($goalScorer = mysqli_fetch_assoc($result)) {
                                if ($goalScorer["match_playerTeam"] == $match["match_homeTeam"]) {
                                    echo $goalScorer["match_playerName"] . "<img id='addGameIconBall' src='../images/ball.png' title='Gól' alt='Gól'>";
                            ?>
                                    <br>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                        if ($match) {
                            $result = getGoalScorers($conn, $match["match_ID"]);
                        }
                        ?>
                        <div class="col-6 mt-2 text-center">
                            <?php
                            while ($goalScorer = mysqli_fetch_assoc($result)) {
                                if ($goalScorer["match_playerTeam"] == $match["match_awayTeam"]) {
                                    echo $goalScorer["match_playerName"] . "<img id='addGameIconBall' src='../images/ball.png' title='Gól' alt='Gól'>";
                            ?>
                                    <br>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="row">
                        <div class="row">Forma:</div>
                        <?php
                        $gameResults = getLatestResults($conn, "5", "Felnőtt");
                        for ($i = 0; $i < count($gameResults); $i++) {
                            if ($gameResults[$i] == "won") {
                        ?>
                                <div class="col-1 winnerDiv">
                                    GY
                                </div>
                            <?php
                            } elseif ($gameResults[$i] == "X") {
                            ?>
                                <div class="col-1 XDiv">
                                    D
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="col-1 loserDiv">
                                    V
                                </div>
                        <?php
                            }
                        }
                    }
                    else{
                        ?>
                    <div>Nincs mérkőzés az adatbázisban</div>
                        <?php
                    }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="container">
            <?php
            $match = getLatestGame($conn, "U19");
            if ($match) {
                $result = getGoalScorers($conn, $match["match_ID"]);
            }
            ?>
            <div class="row bg-light p-3 mt-5 rounded">
                <h1 class="display-3">U19-es csapat</h1>
                <hr>
                <h3 class="display-5">Utolsó mérkőzés: <?php echo $match["match_place"] . ", " . $match["match_date"]; ?></h3>
                <div class="row display-6">
                    <div class="col-5 text-center">
                        <?php echo $match["match_homeTeam"]; ?>
                    </div>
                    <div class="col-2 text-center">
                        <?php echo $match["match_homeTeamGoals"] . "-" . $match["match_awayTeamGoals"]; ?>
                    </div>
                    <div class="col-5 text-center">
                        <?php echo $match["match_awayTeam"]; ?>
                    </div>
                </div>
                <div class="row">
                    <span>Gólszerzők:</span>
                    <div class="col-6 text-center">
                        <?php
                        while ($goalScorer = mysqli_fetch_assoc($result)) {
                            if ($goalScorer["match_playerTeam"] == $match["match_homeTeam"]) {
                                echo $goalScorer["match_playerName"];
                        ?>
                                <br>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php
                        if ($match) {
                            $result = getGoalScorers($conn, $match["match_ID"]);
                        }
                        while ($goalScorer = mysqli_fetch_assoc($result)) {
                            if ($goalScorer["match_playerTeam"] == $match["match_awayTeam"]) {
                                echo $goalScorer["match_playerName"];
                        ?>
                                <br>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div> -->
    <?php
    require_once '../components/guestFooter.php';
    ?>