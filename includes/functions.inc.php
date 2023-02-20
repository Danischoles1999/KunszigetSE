<?php

function uidExists($conn, $username, $email)
{
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;"; //email vagy felhasználónév is jó bejelentkezéshez
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/login.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}
function playerIDExists($conn, $playerID)
{
    $sql = "SELECT * FROM players WHERE player_ID = ? ;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/games.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if (mysqli_fetch_assoc($resultData)) {
        return true;
    } else {
        return false;
    }
}
function playerIDValid($conn, $playerID)
{
    if (strlen($playerID) != 6) {
        header("location: ../pages/players.php?playerID=wrongLength");
        exit();
    }
    if (!is_numeric($playerID)) {
        header("location: ../pages/players.php?playerID=isNotNumeric");
        exit();
    }
}

function setSessions($conn, $uidExists){
    session_start();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION["userrole"] = $uidExists["usersRole"];
    $_SESSION["userid"] = $uidExists["usersID"];
    $_SESSION["useruid"] = $uidExists["usersUid"];
    $_SESSION["userpwd"] = $uidExists["usersPwd"];
    $_SESSION["username"] = $uidExists["usersName"];
    $_SESSION["playerid"] = null;
    $_SESSION["coachid"] = null;
    $_SESSION["teamid"] = null;

    if($_SESSION["userrole"] == 3){
        $playerID = getPlayerID($conn, $_SESSION["username"]);
        $_SESSION["playerid"]=$playerID;
        $teamID = getTeamID($conn, $playerID);
        $_SESSION["teamid"] = $teamID;
    }
    if($_SESSION["userrole"] == 2){
        $coachID = getCoachID($conn, $_SESSION["username"]);
        $_SESSION["coachid"]=$coachID;
        $teamID = getTeamIDFromCoachID($conn, $coachID);
        $_SESSION["teamid"] = $teamID;
    }
}

function unsetSessions(){
    session_destroy();
    unset($_SESSION['loggedin']);
    unset($_SESSION['userrole']);
    unset($_SESSION['userid']);
    unset($_SESSION['useruid']);
    unset($_SESSION['userpwd']);
    unset($_SESSION['username']);
    unset($_SESSION['playerid']);
    unset($_SESSION['coachid']);
    unset($_SESSION['teamid']);
}

function loginUser($conn, $username, $pwd)
{
    $uidExists = uidExists($conn, $username, $username);
    $password = $uidExists["usersPwd"];
    $checkPassword = password_verify($pwd, $password);
    if ($password === $pwd || $checkPassword === true) {
        setSessions($conn, $uidExists);
        header("location: ../pages/home.php");
        exit();
    } elseif ($uidExists === false) {
        header("location: ../pages/login.php?error=wronguser");
        exit();
    } elseif ($password != $pwd && !(password_verify($pwd, $password))) {
        header("location: ../pages/login.php?error=wrongpassword");
        exit();
    }
}

function getPlayerID($conn, $playerName){
    $sql = "SELECT player_ID FROM players WHERE player_fullName = '$playerName'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row["player_ID"];
}

function getCoachID($conn, $coachName){
    $sql = "SELECT coach_ID FROM coaches WHERE coach_name = '$coachName'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row["coach_ID"];
}

function getTeamID($conn, $playerID){
    $sql = "SELECT * FROM players WHERE player_ID = '$playerID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row["team_ID"];
}

function firstLogin($conn, $userId)
{
    $sql = "SELECT * FROM users WHERE usersUid = ? ;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/home.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);

    if ($row['FirstLogin'] === 1) {
        return true;
    } elseif ($row['FirstLogin'] === 0) {
        return false;
    }
    mysqli_stmt_close($stmt);
}
function logout()
{
    if (isset($_GET['logout'])) {
        unsetSessions();
        header("location: ../pages/guest.php");
        exit();
    }
}
function getUserData($conn,$id){
    $sql= "SELECT * FROM users WHERE (usersID='" . $id . "');";
    $result = mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    return $row;
}
function changePassword($conn, $hashedPassword, $i, $user){
    $sql = "UPDATE users SET usersPwd=?, FirstLogin=? WHERE usersID=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../pages/home.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sii", $hashedPassword, $i, $user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo("<script>location.href = '../pages/home.php?password=changed';</script>");
    //header("Location: ../pages/home.php?password=changed");
    exit();
}
function addPlayer($conn, $id, $fullName, $birthdate, $team, $position, $height, $weight, $strongFoot, $mobile, $email, $contract_date, $contract_expires, $last_medical, $image)
{
    if (playerIDExists($conn, $id)) {
        header("Location: ../pages/players.php?playerID=alreadyExists");
        exit();
    } else {
        playerIDValid($conn, $id);
        $sql = "INSERT INTO players (player_ID, player_fullName,player_birthdate,team_ID, player_position, player_height, player_weight, player_strongFoot, player_mobile, player_email, player_image) VALUES ('" . $id . "','" . $fullName . "','" . $birthdate . "','" . $team . "','" . $position . "','" . $height . "','" . $weight . "','" . $strongFoot . "','" . $mobile . "','" . $email . "','" . $image . "');";
        mysqli_query($conn, $sql);
        $sql2 = "INSERT INTO contracts (player_ID, contract_date, contract_expires, last_medical) VALUES ('" . $id . "','" . $contract_date . "','" . $contract_expires . "','" . $last_medical . "');";

        mysqli_query($conn, $sql2);
        header("Location: ../pages/players.php?addPlayer=success");
        exit();
    }
}
function addUser($conn, $userFullName, $userEmail, $userName, $userPassword, $userPasswordRe, $userRole)
{
    $uidExists = uidExists($conn, $userName, $userEmail);
    if ($uidExists === true) {
        header("Location: ../pages/users.php?emailorusername=taken");
        exit();
    }
    if ($userPassword != $userPasswordRe) {
        header("Location: ../pages/users.php?password=dontmatch");
        exit();
    }
    if($userRole == 3){
        $sql = "SELECT * FROM players WHERE player_fullName='$userFullName';";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
        $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd, FirstLogin, usersRole) VALUES ('" . $userFullName . "','" . $userEmail . "','" . $userName . "','" . $userPassword . "','" . 1 . "','" . $userRole . "');";
        mysqli_query($conn, $sql);
            $sql = "SELECT MAX(usersID) as id FROM users";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $id = $row["id"];
            $sql = "UPDATE players SET usersID='$id' WHERE player_fullName='$userFullName';";
            mysqli_query($conn, $sql);
        }
        else{
            header("Location: ../pages/users.php?addUser=CantFindPlayer");
            exit();
        }

    }
    if($userRole == 2){
        $sql = "SELECT * FROM coaches WHERE coach_Name='$userFullName';";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
        $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd, FirstLogin, usersRole) VALUES ('" . $userFullName . "','" . $userEmail . "','" . $userName . "','" . $userPassword . "','" . 1 . "','" . $userRole . "');";
        mysqli_query($conn, $sql);
            $sql = "SELECT MAX(usersID) as id FROM users";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $id = $row["id"];
            $sql = "UPDATE coaches SET usersID='$id' WHERE coach_Name='$userFullName';";
            mysqli_query($conn, $sql);
            header("Location: ../pages/users.php?addUser=seccess");
            exit();
        }
        else{
            header("Location: ../pages/users.php?addUser=CantFindCoach");
            exit();
        }
    }
    else{
        $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd, FirstLogin, usersRole) VALUES ('" . $userFullName . "','" . $userEmail . "','" . $userName . "','" . $userPassword . "','" . 1 . "','" . $userRole . "');";
        mysqli_query($conn, $sql);
        header("Location: ../pages/users.php?addUser=success");
        exit();
    }

}
function addGame($conn, $season, $date, $place, $team, $homeTeam, $homeTeamGoals, $awayTeam, $awayTeamGoals, $homePlayers, $awayPlayers)
{
    $sql = "INSERT INTO matches (match_season, match_date,match_place,match_teamID,match_homeTeam,match_homeTeamGoals,match_awayTeam,match_awayTeamGoals) VALUES ('" . $season . "','" . $date . "','" . $place . "','" . $team . "','" . $homeTeam . "','" . $homeTeamGoals . "','" . $awayTeam . "','" . $awayTeamGoals . "');";
    mysqli_query($conn, $sql);
    $sql1 = "SELECT match_ID FROM matches WHERE match_ID=(SELECT MAX(match_ID) FROM matches);";
    $result = mysqli_query($conn, $sql1);
    $row = mysqli_fetch_assoc($result);
    $match_ID = $row["match_ID"];
    $teamID = $_SESSION["teamid"];
    foreach ($homePlayers as $key => $value) {
        if($homeTeam == "Kunsziget SE"){
            $playerID = getPlayerIDFromName($conn, $homePlayers[$key]->playerName);
            $sql = "INSERT INTO match_player (match_ID, player_ID, match_playerName, match_playerTeam, match_PlayerStart, match_playerGoal, match_playerYellow, match_playerRed) VALUES ('" . $match_ID . "','" . $playerID . "','" . $homePlayers[$key]->playerName . "','" . $homeTeam . "','" . $homePlayers[$key]->start . "','" . $homePlayers[$key]->goal . "','" . $homePlayers[$key]->yellow . "','" . $homePlayers[$key]->red . "');";
            if($homePlayers[$key]->yellow == '1'){
                $numOfYellows = getNumberOfYellowsThisSeason($conn, $match_ID, $season, getAllMatchWithYellow($conn, $playerID));
                if($numOfYellows == 5){
                    addAbsence($conn, $playerID, $teamID, $date, strtotime("$date +1 week"), "eltiltás", "sárgalapok");
                }
            }
            if($homePlayers[$key]->red == '1'){
                addAbsence($conn, $playerID, $teamID, $date, strtotime("$date +1 week"), "eltiltás", "piroslap"); 
            }
        }
        else{
            $sql = "INSERT INTO match_player (match_ID, match_playerName, match_playerTeam, match_PlayerStart, match_playerGoal, match_playerYellow, match_playerRed) VALUES ('" . $match_ID . "','" . $homePlayers[$key]->playerName . "','" . $homeTeam . "','" . $homePlayers[$key]->start . "','" . $homePlayers[$key]->goal . "','" . $homePlayers[$key]->yellow . "','" . $homePlayers[$key]->red . "');";
        }
        mysqli_query($conn, $sql);
    }
    foreach ($awayPlayers as $key => $value) {
        if($awayTeam == "Kunsziget SE"){
            $playerID = getPlayerIDFromName($conn, $homePlayers[$key]->playerName);
            $sql = "INSERT INTO match_player (match_ID, player_ID, match_playerName, match_playerTeam, match_PlayerStart, match_playerGoal, match_playerYellow, match_playerRed) VALUES ('" . $match_ID . "','" . $playerID . "','" . $awayPlayers[$key]->playerName . "','" . $awayTeam . "','" . $awayPlayers[$key]->start . "','" . $awayPlayers[$key]->goal . "','" . $awayPlayers[$key]->yellow . "','" . $awayPlayers[$key]->red . "');";
            if($awayPlayers[$key]->yellow == '1'){
                $numOfYellows = getNumberOfYellowsThisSeason($conn, $match_ID, $season, getAllMatchWithYellow($conn, $playerID));
                if($numOfYellows == 5){
                    addAbsence($conn, $playerID, $teamID, $date, strtotime("$date +1 week"), "eltiltás", "sárgalapok");
                }
            }
            if($awayPlayers[$key]->red == '1'){
                addAbsence($conn, $playerID, $teamID, $date, strtotime("$date +1 week"), "eltiltás", "piroslap"); 
            }
        }
        else{
            $sql = "INSERT INTO match_player (match_ID, match_playerName, match_playerTeam, match_PlayerStart, match_playerGoal, match_playerYellow, match_playerRed) VALUES ('" . $match_ID . "','" . $awayPlayers[$key]->playerName . "','" . $awayTeam . "','" . $awayPlayers[$key]->start . "','" . $awayPlayers[$key]->goal . "','" . $awayPlayers[$key]->yellow . "','" . $awayPlayers[$key]->red . "');";
        }
        mysqli_query($conn, $sql);
    }
    header("Location: ../components/addGameForm.php?addGame=success");
    exit;
}

function getAllMatchWithYellow($conn, $playerID){
    $sql = "SELECT * FROM match_player WHERE player_ID = '$playerID' AND match_playerYellow = '1'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function getNumberOfYellowsThisSeason($conn, $match_ID, $season, $matchesWithYellow){
    $yellows = 0;
    while($matches = mysqli_fetch_assoc($matchesWithYellow)){
        $thisSeason = getMatchSeason($conn, $matches["match_ID"]);
        if($thisSeason == $season){
            $yellows++;
        } 
    }
    return $yellows;
}

function getMatchSeason($conn, $match_ID){
    $sql = "SELECT * FROM matches WHERE match_ID = '$match_ID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row["match_season"];
}

function getPlayerIDFromName($conn, $playerName){
    $sql = "SELECT * FROM players WHERE player_fullName = '$playerName'";
    $result = mysqli_query($conn, $sql); 
    $row = mysqli_fetch_assoc($result);
    return $row["player_ID"];
}

function showPlayersMainData($conn, $role)
{
    $sql = "SELECT player_ID,player_fullName,player_birthdate,team_name,player_position,player_height,player_weight,player_strongFoot,player_mobile,player_email,player_image,coach_Name FROM players, teams, coaches WHERE (players.team_ID = teams.team_ID AND teams.coach_ID = coaches.coach_ID);";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        showPlayersTable($conn, $result, $resultCheck, $role);
    }
}
function showPlayersTable($conn, $result, $resultCheck, $role)
{
    ?>
        <div class="table-responsive border border-light rounded p-2">
            <table id="table" class="table text-light table-hover">
                <thead>
                    <tr>
                        <th>Játékos azonosító száma</th>
                        <th>Teljes név</th>
                        <th>Születési idő</th>
                        <th>Csapat/Korosztály</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultCheck > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $playerID = $row["player_ID"];
                    ?>
                            <tr role="button" data-bs-toggle="modal" data-bs-target="#show<?php echo $playerID; ?>">
                                <td><?php echo $row["player_ID"]; ?></td>
                                <td><?php echo $row["player_fullName"]; ?></td>
                                <td><?php echo $row["player_birthdate"]; ?></td>
                                <td><?php echo $row["team_name"]; ?></td>
                                <td><a href="../pages/modifyPlayer.php?id=<?php echo $playerID; ?>" class="btn btn-secondary mx-auto bi bi-pencil <?php if ($role == 3) {
                                                                                                                                                                echo "disabled";
                                                                                                                                                            } ?>">
                                    </a>
                                </td>
                                <td><a data-bs-toggle="modal" data-bs-target="#delete<?php echo $playerID; ?>" class="btn btn-danger mx-auto bi bi-trash <?php if ($role == 3) {
                                                                                                                                                                        echo "disabled";
                                                                                                                                                                    } ?>">
                                    </a>
                                </td>
                                <div class="modal fade" id="delete<?php echo $playerID; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Megerősítés</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Biztosan törli az adatbázisból a kijelölt játékost?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                                <a href="../includes/deletePlayer.inc.php?id=<?php echo $playerID; ?>" type="button" class="btn btn-danger">Törlés</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="show<?php echo $playerID; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <img class="player-image img-fluid float-start img-thumbnail rounded" src="<?php echo $row["player_image"]; ?>" alt="">
                                                <h5 class="modal-title">
                                                    <?php
                                                    echo $row["player_fullName"];
                                                    ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <span>Játékos azonosító száma: <?php echo $row["player_ID"]; ?></span><br>
                                                <span>Születési dátum: <?php echo $row["player_birthdate"]; ?></span><br>
                                                <span>Csapat/Korosztály: <?php echo $row["team_name"]; ?></span><br>
                                                <span>Poszt: <?php echo $row["player_position"]; ?></span><br>
                                                <span>Magasság: <?php echo $row["player_height"]; ?></span><br>
                                                <span>Súly: <?php echo $row["player_weight"]; ?></span><br>
                                                <span>Ügyesebb láb: <?php 
                                                if($row["player_strongFoot"] == 1)
                                                echo "jobb ";
                                                else{
                                                    echo "bal";
                                                }
                                                ?></span><br>
                                                <span>Telefon: <?php echo $row["player_mobile"]; ?></span><br>
                                                <span>Email: <?php echo $row["player_email"]; ?></span><br>
                                                <span>Súly: <?php echo $row["player_weight"]; ?></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vissza</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
        }
}
function showGamesMainData($conn, $role)
{
    $sql = "SELECT * FROM matches;";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        showGamesTable($conn, $result, $resultCheck, $role);
    }
}
function showGamesTable($conn, $result, $resultCheck, $role)
{
    ?>
    <div class="table-responsive border border-light rounded p-2">
        <table id="table" class="table text-white table-hover">
            <thead>
                <tr>
                    <th>Szezon</th>
                    <th>Dátum</th>
                    <th>Helyszín</th>
                    <th>Korosztály</th>
                    <th>Hazai Csapat</th>
                    <th>Vendég Csapat</th>
                    <th>Eredmény</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($resultCheck > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr data-bs-toggle="modal" data-bs-target="#show<?php echo $row["match_ID"]; ?>">
                            <td><?php
                                if ($row["match_season"] == '1') {
                                    echo "2022/2023";
                                } elseif ($row["match_season"] == '2') {
                                    echo "2023/2024";
                                } elseif ($row["match_season"] == '3') {
                                    echo "2024/2025";
                                } elseif ($row["match_season"] == '4') {
                                    echo "2025/2026";
                                } elseif ($row["match_season"] == '5') {
                                    echo "2026/2027";
                                } elseif ($row["match_season"] == '6') {
                                    echo "2027/2028";
                                }

                                ?></td>
                            <td><?php echo $row["match_date"]; ?></td>
                            <td><?php echo $row["match_place"]; ?></td>
                            <td><?php
                                echo turnTeamIDIntoString($row["match_teamID"]);
                            ?></td>
                            <td><?php echo $row["match_homeTeam"]; ?></td>
                            <td><?php echo $row["match_awayTeam"]; ?></td>
                            <td><?php echo ($row["match_homeTeamGoals"] . "-" . $row["match_awayTeamGoals"]); ?></td>


                            <td><a href="../pages/modifyGame.php?id=<?php echo $row["match_ID"]; ?>" class="btn btn-secondary mx-auto bi bi-pencil <?php if ($role == 3) {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>">
                                </a>
                            </td>
                            <td><a data-bs-toggle="modal" data-bs-target="#delete<?php echo $row["match_ID"]; ?>" class="btn btn-danger mx-auto bi bi-trash <?php if ($role == 3) {
                                                                                                                                                                echo "disabled";
                                                                                                                                                            } ?>">
                                </a>
                            </td>
                            <div class="modal fade" id="delete<?php echo $row["match_ID"]; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Megerősítés</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Biztosan törli az adatbázisból a kijelölt mérkőzést?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                            <a href="../includes/deleteGame.inc.php?id=<?php echo $row["match_ID"]; ?>" type="button" class="btn btn-danger">Törlés</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="show<?php echo $row["match_ID"]; ?>" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <img class="team-image w-75 img-fluid mx-auto img-thumbnail rounded" src="../images/adult.jpg" alt="">
                                                <h5 class="modal-title">
                                                </h5>
                                        </div>
                                        <div class="modal-body">
                                            <h5 class="text-center">
                                        <?php
                                            echo $row["match_homeTeam"] . " : " . $row["match_homeTeamGoals"] . " - " . $row["match_awayTeam"] . " : " . $row["match_awayTeamGoals"];
                                        ?>      
                                            </h5>
                                            <?php showStartingTeams($conn, $row["match_ID"], $row["match_homeTeam"], $row["match_awayTeam"]); ?>                   
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vissza</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
        }
}

function showStartingTeams($conn, $matchID, $homeTeam, $awayTeam){
    $sql = "SELECT * FROM match_player WHERE match_ID = '$matchID' AND match_playerTeam = '$homeTeam'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    $sql2 = "SELECT * FROM match_player WHERE match_ID = '$matchID' AND match_playerTeam = '$awayTeam'";
    $result2 = mysqli_query($conn, $sql2);
    $resultCheck2 = mysqli_num_rows($result2);
    if($resultCheck > 0 && $resultCheck2 > 0){
        ?>
    <div class="row">
        <?php
        $homePlayers = array();
        $awayPlayers = array();
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $homePlayers[$i] = $row["match_playerName"];
            $i++;
        }
        $i=0;
        while($row2 = mysqli_fetch_assoc($result2)){
            $awayPlayers[$i] = $row2["match_playerName"];
            $i++;
        }
        if(count($homePlayers) > count($awayPlayers)){
            for ($i=0; $i < count($homePlayers); $i++) { 
                ?>
                    <div class="col-6">
                    <?php
                        if(isset($homePlayers[$i])){
                            echo $homePlayers[$i];
                        }
                        else{
                            echo "";
                        }
                    ?>
                    </div>
                    <?php
                    ?>
                    <div class="col-6">
                    <?php
                        if(isset($awayPlayers[$i])){
                            echo $awayPlayers[$i];
                        }
                        else{
                            echo "";
                        }
                    ?>
                    </div>
                    <?php
            }
        }
        else{
            for ($i=0; $i < count($awayPlayers); $i++) { 
                ?>
                    <div class="col-6">
                    <?php
                        if(isset($homePlayers[$i])){
                            echo $homePlayers[$i];
                        }
                        else{
                            echo "";
                        }
                    ?>
                    </div>
                    <?php
                    ?>
                    <div class="col-6">
                    <?php
                        if(isset($awayPlayers[$i])){
                            echo $awayPlayers[$i];
                        }
                        else{
                            echo "";
                        }
                    ?>
                    </div>
                    <?php
            }
        }
        ?>
    </div>
        <?php
    }
}

function showAllUsers($conn,$id)
{
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        showAllUsersTable($result, $resultCheck,$id);
    }
}
function showAllUsersTable($result, $resultCheck,$id)
{
    ?>
    <div class="table-responsive border border-light rounded p-2">
        <table id="table" class="table text-white table-hover">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Email</th>
                    <th>Felhasználónév</th>
                    <th>Jogosultság</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($resultCheck > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if($id != $row['usersID']){
                ?>
                    
                        <tr>
                            <td><?php echo $row["usersName"]; ?></td>
                            <td><?php echo $row["usersEmail"]; ?></td>
                            <td><?php echo $row["usersUid"]; ?></td>
                            <td><?php
                                if ($row["usersRole"] == '1') {
                                    echo "admin";
                                } elseif ($row["usersRole"] == '2') {
                                    echo "edző";
                                } else {
                                    echo "játékos";
                                }
                                ?></td>
                            <td><a href="../pages/modifyUser.php?id=<?php echo $row["usersID"]; ?>" class="btn btn-secondary mx-auto bi bi-pencil"></a>
                            </td>
                            <td><a data-bs-toggle="modal" data-bs-target="#delete<?php echo $row["usersID"]; ?>" class="btn btn-danger mx-auto bi bi-trash"></a>
                            </td>
                            <div class="modal fade" id="delete<?php echo $row["usersID"]; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Megerősítés</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Biztosan törli az adatbázisból a kijelölt felhasználót?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                            <a href="../includes/deleteUser.inc.php?id=<?php echo $row["usersID"]; ?>" type="button" class="btn btn-danger">Törlés</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>
    <?php
        }
}
function deletePlayer($conn, $id)
{
    $sql = "DELETE FROM players WHERE player_ID = '" . $id . "';";
    $sql2 = "DELETE FROM contracts WHERE player_ID = '" . $id . "';";
    mysqli_query($conn, $sql);
    mysqli_query($conn, $sql2);
    header("Location: ../pages/players.php?deletePlayer=success");
    exit();
}
function deleteGame($conn, $id)
{
    $sql = "DELETE FROM matches WHERE match_ID = '" . $id . "';";
    $sql2 = "DELETE FROM match_player WHERE match_ID = '" . $id . "';";
    mysqli_query($conn, $sql);
    mysqli_query($conn, $sql2);
    header("Location: ../pages/games.php?deleteGame=success");
    exit();
}
function deleteUser($conn, $id)
{
    $sql = "DELETE FROM users WHERE usersID = '" . $id . "';";
    mysqli_query($conn, $sql);
    header("Location: ../pages/users.php?deleteUser=success");
    exit();
}
function modifyPlayer($conn, $id, $fullName, $birthdate, $team, $position, $height, $weight, $strongFoot, $mobile, $email, $image)
{
    $sql = "UPDATE players SET player_fullName='$fullName',player_birthdate='$birthdate',team_ID='$team' ,player_position='$position', player_height='$height', player_weight='$weight', player_strongFoot='$strongFoot', player_mobile='$mobile', player_email='$email', player_image='$image' WHERE player_ID='$id';";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("Location: ../pages/players.php?modifyPlayer=success");
        exit();
    }
    else{
        header("Location: ../pages/players.php?modifyPlayer=error");
        exit();
    }
}

function showModifyPlayerForm($conn, $id)
{
    $sql = "SELECT player_ID,player_fullName,player_birthdate,team_name,player_position,player_height,player_weight,player_strongFoot,player_mobile,player_email,player_image FROM players, teams WHERE ((players.team_ID = teams.team_ID) AND players.player_ID = ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/players.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);
    $id = $row["player_ID"];
    $fullName = $row["player_fullName"];
    $birthdate = $row["player_birthdate"];
    $team = $row["team_name"];
    $position = $row["player_position"];
    $height = $row["player_height"];
    $weight = $row["player_weight"];
    $strongFoot = $row["player_strongFoot"];
    $mobile = $row["player_mobile"];
    $email = $row["player_email"];
    $image = $row["player_image"];
    $today = date("Y-m-d");
    $yesterday = strtotime("$today -1 day");
    ?>
    <div class="container">
        <form action="../includes/modifyPlayer.inc.php?id=<?php echo $id; ?>" method="post">
            <div class="row">
                <div class="col-md-4">
                    <label for="playerID">Azonosító szám:</label>
                    <input class="form-control" type="text" name="PlayerID" value="<?php echo $id; ?>" disabled></input>
                </div>
                <div class="col-md-4">
                    <label for="fullName">Teljes név:</label>
                    <input class="form-control" type="text" name="fullName" value="<?php echo $fullName; ?>"></input>
                </div>
                <div class="col-md-4">
                    <label for="birthdate" value="<?php echo $birthdate; ?>">Születési dátum:</label>
                    <input class="form-control" type="date" name="birthdate" min="1900-01-01" max="<?php echo $yesterday; ?>"></input>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label for="team">Csapat:</label>
                    <select class="form-select" name="team" id="team">
                        <option value="1" <?php if ($team == "U9") {
                                                echo 'selected';
                                            } ?>>U9</option>
                        <option value="2" <?php if ($team == "U11") {
                                                echo "selected";
                                            } ?>>U11</option>
                        <option value="3" <?php if ($team == "U14") {
                                                echo "selected";
                                            } ?>>U14</option>
                        <option value="4" <?php if ($team == "U16") {
                                                echo "selected";
                                            } ?>>U16</option>
                        <option value="5" <?php if ($team == "U19") {
                                                echo "selected";
                                            } ?>>U19</option>
                        <option value="6" <?php if ($team == "Felnőtt") {
                                                echo "selected";
                                            } ?>>Felnőtt</option>
                    </select>
                </div>
                <div class="col-2">
                    <label for="position">Poszt:</label>
                    <input class="form-control" type="text" name="position" value="<?php echo $position; ?>"></input>
                </div>
                <div class="col-2">
                    <label for="height">Magasság:</label>
                    <input class="form-control" type="text" name="height" value="<?php echo $height; ?>"></input>
                </div>
                <div class="col-2">
                    <label for="weight">Súly:</label>
                    <input class="form-control" type="text" name="weight" value="<?php echo $weight; ?>"></input>
                </div>
                <div class="col-3">
                    <label for="strongFoot">Ügyesebb láb:</label>
                    <input class="form-control" type="text" name="strongFoot" value="<?php
                    if($strongFoot == 1){
                        echo "jobb";
                    }
                    else{
                        echo $strongFoot;
                    }
                    ?>"></input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="mobile">Telefon:</label>
                    <input class="form-control" type="text" name="mobile" value="<?php echo $mobile; ?>"></input>
                </div>
                <div class="col-md-3">
                    <label for="email">Email:</label>
                    <input class="form-control" type="text" name="email" value="<?php echo $email; ?>"></input>
                </div>
                <div class="col-md-6">
                    <label for="image">Kép URL-je:</label>
                    <input class="form-control" type="text" name="image" value="<?php echo $image; ?>"></input>
                </div>
            </div>
            <button type="submit" name="modifyPlayer" class="modifyPlayerButton btn btn-secondary my-3">Szerkesztés mentése</button>
        </form>
    </div>
    <?php
}
function modifyUser($conn,$id,$userName,$userEmail,$userUid,$userRole){
    $sql = "UPDATE users SET usersName = '$userName' , usersEmail='$userEmail',usersUid='$userUid',usersRole='$userRole' WHERE usersID='$id';";
    mysqli_query($conn, $sql);
    header("Location: ../pages/users.php?modifyUser=success");
    exit();
}
function showModifyUserForm($conn, $id)
{
    $sql = "SELECT * FROM users WHERE usersID=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/users.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);
    $id = $row["usersID"];
    $userName = $row["usersName"];
    $userEmail = $row["usersEmail"];
    $userUid = $row["usersUid"];
    $userRole = $row["usersRole"];
    ?>
    <div class="container">
    <form action="../includes/modifyUser.inc.php?id=<?php echo $id; ?>" method="post">
    <div class="row">
        <div class="col-md-3">
            <label for="userName">Név</label>
            <input class="form-control" type="text" name="userName" value="<?php echo $userName; ?>"></input>
        </div>
        <div class="col-md-3">
            <label for="userEmail">Email</label>
            <input class="form-control" type="email" name="userEmail" value="<?php echo $userEmail; ?>"></input>
        </div>
        <div class="col-md-3">
            <label for="userUid">Felhasználónév</label>
            <input class="form-control" type="text" name="userUid" value="<?php echo $userUid; ?>"></input>
        </div>
        <div class="col-md-3">
            <label for="userRole">Jogosultság</label>
            <select class="form-select" name="userRole" id="userRole">
                <option value="1" <?php if ($userRole == '1') {
                                        echo 'selected';
                                    } ?>>Admin</option>
                <option value="2" <?php if ($userRole == '2') {
                                        echo "selected";
                                    } ?>>Edző</option>
                <option value="3" <?php if ($userRole == '3') {
                                        echo "selected";
                                    } ?>>Játékos</option>
            </select>
        </div>
    </div>
    <button type="submit" name="modifyUser" class="modifyUserButton btn btn-secondary my-3">Szerkesztés mentése</button>
    </form>
    </div>
    <?php
}
function showExpireContactDate($conn, $playerID, $type)
{
    $sql = "SELECT * FROM contracts WHERE contracts.player_ID='$playerID';";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $row = mysqli_fetch_assoc($result);
        $date = "";
        if($type == "contracts"){
            $date = $row["contract_expires"];
        }
        elseif($type == "medicals"){
            $date = $row["last_medical"];
        }
        return $date;
    }
}
function showExpireDateDaysLeft($conn, $playerID, $type){
    $sql = "SELECT * FROM contracts WHERE contracts.player_ID='$playerID';";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $row = mysqli_fetch_assoc($result);
        $date = "";
        if($type == "contracts"){
            $date = $row["contract_expires"];
        }
        elseif($type == "medicals"){
            $date = $row["last_medical"];
        }
        $diff = (date_diff(date_create(date("Y-m-d")), date_create($date)));
        return $diff->format("%R%a nap");

    }
}

function showExpireDatesOfAllPlayers($conn, $coachid, $type){
    $sql = "SELECT * FROM teams WHERE coach_ID = '$coachid'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    $teamID = "";
    if($resultCheck > 0){
        $row = mysqli_fetch_assoc($result);
        $teamID = $row["team_ID"];
    }
    else{
        echo "Hiba";
        exit();
    }
    $sql = "SELECT * FROM players WHERE team_ID = '$teamID'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck>0){
        ?>
        <div class="row justify-content-evenly">
        <?php
    while($row = mysqli_fetch_assoc($result)){
            showExpireContactCard($conn, $row, $type);
    }
    ?>
        </div>
        <?php
    }
    else{
        echo "Hiba";
        exit();
    }
}

function showExpireContactCard($conn, $row, $type){
    $daysLeft = showExpireDateDaysLeft($conn, $row["player_ID"], $type);
    $days = "";
    $today = date("Y-m-d");
    $tomorrow = strtotime("$today +1 day");
    for ($i=0; $i < strlen($daysLeft); $i++) { 
        if(is_numeric($daysLeft[$i]) || $daysLeft[$i] == "-"){
            $days=$days . $daysLeft[$i];
        }
    }
    $date = "";
    if($type == "contracts"){
        $date = showExpireContactDate($conn, $row["player_ID"], $type);
    }
    elseif($type == "medicals"){
        $date = showExpireContactDate($conn, $row["player_ID"], $type);
    }
    ?>
    <div class="card col-8 col-sm-6 col-md-4 col-lg-3 col-xl-2 ms-2 mt-2 p-0">
        <img src="<?php echo $row["player_image"] ?>" class="card-img-top mt-2" alt="<?php echo $row["player_fullName"] ?>">
        <div class="card-body">
            <h5 class="card-title h-25 text-center"><?php echo $row["player_fullName"] ?></h5>
            <p class="card-text"><?php echo "<h6 class='p-0 m-0'>Igazolás lejárata:</h6>" . $date ?></p>
            <div class="row mx-auto">
                <div class="col-6 p-0 m-0 pt-2 badge <?php if((int)$days <= 15){echo "bg-danger";} elseif((int)$days <= 30){echo "bg-warning";} else{echo "bg-info";} ?>">
                    <h6 class=""><?php echo $daysLeft ?></h6>
                </div>
            <div class="col-6 text-center">
                <a data-bs-toggle="modal" data-bs-target="#updateContract<?php echo $row["player_ID"]; echo $type;?>"  class="btn btn-primary">Frissít</a>
            </div>
            <div class="modal fade" id="updateContract<?php echo $row["player_ID"]; echo $type;?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title">Lejárati dátum frissítése</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../includes/updateContract.inc.php?playerID=<?php echo $row["player_ID"] ?>&type=<?php echo $type ?>" method="post">
                                <label for="newDateOfExpire">Új dátum</label>
                                <input type="date" name="newDateOfExpire" class="form-control" min="<?php echo $tomorrow ?>" max="2100-01-01" required>
                                <button type="submit" name="submit" class="btn btn-success my-3">Rögzít</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php
}

function updateContract($conn, $playerID, $type, $newDateOfExpire){
    if($type == "contracts"){
        $sql = "UPDATE contracts SET contract_expires = '$newDateOfExpire' WHERE player_ID='$playerID'";
    }
    elseif($type == "medicals"){
        $sql = "UPDATE contracts SET last_medical = '$newDateOfExpire' WHERE player_ID='$playerID'";       
    }
    mysqli_query($conn, $sql);
    header("location: ../pages/contracts.php?update=success");
    exit();
}

function getCoachData($conn,$id){
    $coach = new stdClass();
    $sql ="SELECT usersName FROM users WHERE usersID=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/addGameSquad.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    $coach->coachName=($row["usersName"]);
    $sql ="SELECT coach_ID FROM coaches WHERE coach_Name=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/addGameSquad.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $row["usersName"]);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    $coach->coachID=$row["coach_ID"];
    $sql ="SELECT team_ID FROM teams WHERE coach_ID=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/gameSquads.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $row["coach_ID"]);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    $coach->teamID=$row["team_ID"];
    return $coach;
}

function getPlayers($conn, $teamID){
    $players = array();
    $sql ="SELECT * FROM players WHERE team_ID=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/gameSquads.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $teamID);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $i = 0;
    while($row = mysqli_fetch_assoc($resultData)){
        $player = new stdClass();
        $player->name=($row["player_fullName"]);
        $player->id = ($row["player_ID"]);
        $players[$i]=($player);
        $i++;
    }
    return $players; 
}

function addGameSquad($conn,$teamID,$date,$place,$playerids){
    $sql = "INSERT INTO squads (team_ID, squad_matchDate, squad_matchPlace) VALUES ('" . $teamID . "','" . $date . "','" . $place . "');";
    mysqli_query($conn, $sql);
    $sql = "SELECT MAX(squad_ID) as maximum FROM squads";
    $result  = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $squadID = $row["maximum"];
    foreach ($playerids as $key) {
        $sql = "INSERT INTO squad_player (player_ID, squad_ID) VALUES ('" . $key . "','" . $squadID . "');";
        mysqli_query($conn, $sql);
        
    }
    header("location: ../pages/gameSquads.php?success=squadAdded");
    exit();
}

function getLatestGame($conn, $team){
    $sql ="SELECT * FROM teams WHERE team_name='$team';";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $row = mysqli_fetch_assoc($result);
        $teamID = $row["team_ID"];
        $sqlLatestMatch = "SELECT * FROM matches WHERE match_teamID = '$teamID' AND match_date = (SELECT MAX(match_date) FROM matches WHERE match_teamID = '$teamID');";
        $result = mysqli_query($conn, $sqlLatestMatch);
        $match = mysqli_fetch_assoc($result);
        return $match;
    }
    else{
        echo "nincs ilyen mérkőzés";
    }
}

function getGoalScorers($conn, $matchID){
    $sql = "SELECT * FROM match_player WHERE match_ID = '$matchID' AND match_playerGoal > '0';";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        return $result;
    }
    else{
        echo "hiba";
    }
}

function getLatestResults($conn, $number, $team){
    $sql ="SELECT * FROM teams WHERE team_name='$team';";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $row = mysqli_fetch_assoc($result);
        $teamID = $row["team_ID"];
        $sqlGames = "SELECT * FROM matches WHERE match_teamID = '$teamID' ORDER BY match_date DESC LIMIT $number";
        $result = mysqli_query($conn, $sqlGames);
        $resultCheck = mysqli_num_rows($result);
        $gameResults = array();
        $index = 0;
        if ($resultCheck > 0) {
            while($match = mysqli_fetch_assoc($result)){
                $winner = getMatchWinner($match);
                if($winner == "Kunsziget SE"){
                    $gameResults[$index] = "won";
                }
                elseif($winner == "X"){
                    $gameResults[$index] = "X";
                }
                else{
                    $gameResults[$index] = "lost";
                }
                $index++;
            }
            return $gameResults;
        }
        else{
            echo "hiba";
        }
    }
}

function getMatchWinner($match){
        if($match["match_homeTeamGoals"] > $match["match_awayTeamGoals"]){
            return $match["match_homeTeam"];
        }
        elseif($match["match_homeTeamGoals"] < $match["match_awayTeamGoals"]){
            return $match["match_awayTeam"];
        }
        else{
            return "X";
        }
}

function IsThereSquadInvolved($conn, $playerID){
    $sql ="SELECT * FROM squad_player WHERE player_ID = '$playerID' AND squad_player_availability = 'Nem jelzett vissza'";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $i = 0;
        while($row = mysqli_fetch_assoc($result)){
            $squadID = $row["squad_ID"];
            $sql = "SELECT * FROM squads WHERE squad_ID = '$squadID'";
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            if ($resultCheck > 0) {
                $i++;
            }
        }
        if($i > 0){
            return $i;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function showNextGameSquadsInvolved($conn, $playerID){
    $sql ="SELECT * FROM squad_player WHERE player_ID = '$playerID'";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $neededSquadIDs = array();
        while($row = mysqli_fetch_assoc($result)){
            $squadID = $row["squad_ID"];
            $sql2 = "SELECT * FROM squads WHERE squad_ID ='$squadID' AND squad_matchDate > CURDATE()";
            $result2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($result2);
            array_push($neededSquadIDs, $row2["squad_ID"]);
        }
        
        showGameSquadsInvolvedTable($conn, $neededSquadIDs);
    }
    else{
        echo "Nincs keret melyben szerepelsz!";
    }
}

function showAllGameSquads($conn){
    $sql ="SELECT * FROM squads WHERE squad_matchDate > CURDATE();";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $neededSquadIDs = array();
        while($row = mysqli_fetch_assoc($result)){
            $squadID = $row["squad_ID"];
            array_push($neededSquadIDs, $squadID);
        }
        showGameSquadsInvolvedTable($conn, $neededSquadIDs);
    }
    else{
        echo "Nincs elérhető keret...";
    }
}

function showGameSquadsInvolvedTable($conn, $neededSquadIDs){
    ?>
        <div class="table-responsive border border-light rounded p-2">
        <table id="table" class="table text-white table-hover">
            <thead>
                <tr>
                    <th>Csapat</th>
                    <th>Dátum</th>
                    <th>Helyszín</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    for ($i=0; $i < count($neededSquadIDs); $i++) { 
                        $sql = "SELECT * FROM squads WHERE squad_ID = '$neededSquadIDs[$i]'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        ?>
                        <tr>
                            <td><?php echo turnTeamIDIntoString($row["team_ID"]); ?></td>
                            <td><?php echo $row["squad_matchDate"]; ?></td>
                            <td><?php echo $row["squad_matchPlace"]; ?></td>
                            <td>
                            <?php if($_SESSION["userrole"] == 3) {?>    
                            <!-- <a href="../pages/changeAvailability.php?date=" 
                            class="btn btn-secondary mx-auto bi bi-three-dots">-->
                            <a data-bs-toggle="modal" data-bs-target="#change<?php echo $row["squad_ID"] . $_SESSION["playerid"];?>" 
                            class="btn btn-secondary mx-auto bi bi-three-dots">
                            <?php
                            showChangeAvailabilityModal($conn, $row["squad_ID"], $_SESSION["playerid"]);
                            }

                            elseif($_SESSION["userrole"] == 2){ ?>
                                <a data-bs-toggle="modal" data-bs-target="#show<?php echo $row["squad_ID"];?>" 
                                class="btn btn-secondary mx-auto bi bi-three-dots">
                                <?php
                                availabilityModal($conn, $row["squad_ID"], $row["team_ID"]);
                            }
                            ?></td>
                        </tr>
                         <?php
                    }
                        while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <?php
                } ?>
            </tbody>
        </table>
    </div>
    <?php
}

function availabilityModal($conn, $squadID, $teamID){
    $sql = "SELECT * FROM squad_player WHERE squad_ID = '$squadID'";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        showAvailabilityModal($conn, $result, $squadID);
    }
    else{
        echo "hiba";
    }
}
function showAvailabilityModal($conn, $result, $squadID){
    ?>
        <div class="modal fade text-dark" id="show<?php echo $squadID?>" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Visszajelzés
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Játékos neve</th>
                                    <th>Elérhető-e</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <td>
                                        <?php
                                        $name = getPlayerNameFromID($conn,$row["player_ID"]);
                                        echo $name;
                                        ?>
                                    </td>
                                    <td>
                                            <?php
                                            echo $row["squad_player_availability"]; 
                                            ?>
                                    </td>
                                </tr>
                                    <?php    
                                }
                                ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vissza</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

function showChangeAvailabilityModal($conn, $squadID, $playerID){?>
    <div class="modal fade text-dark" id="change<?php echo $squadID.$playerID?>" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Visszajelzés
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="container">
                <button onclick="location.href='../pages/gameSquads.php?SquadID=<?php echo $squadID?>&PlayerID=<?php echo $playerID ?>&Available=1'" class="btn btn-success">Ott leszek!</button>
                <button onclick="location.href='../pages/gameSquads.php?SquadID=<?php echo $squadID?>&PlayerID=<?php echo $playerID ?>&Available=0'" class="btn btn-danger">Nem tudok részt venni!</button>
            </div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
            </div>
        </div>
    </div>
    </div>
    <?php
}

function setSquadAvailability($conn,$squadID,$playerID,$available){
    $respond = "";
    if($available == "1"){
        $respond = "Ott leszek";
    }
    elseif($available == "0"){
        $respond = "Nem tudok részt venni";
    }
    $sql = "UPDATE squad_player SET squad_player_availability = '$respond' WHERE squad_ID='$squadID' AND player_ID = '$playerID'";
    mysqli_query($conn, $sql);
    echo("<script>location.href = '../pages/gameSquads.php?availability=changed';</script>");
}

function getPlayerNameFromID($conn,$playerID){
    $sql = "SELECT player_fullName FROM players WHERE player_ID = '$playerID'";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['player_fullName'];
    }
    else{
        echo "hiba";
    }
}

function turnTeamIDIntoString($teamID){
    if($teamID == 1){
        return "U9";
    }
    if($teamID == 2){
        return "U11";
    }
    if($teamID == 3){
        return "U14";
    }
    if($teamID == 4){
        return "U16";
    }
    if($teamID == 5){
        return "U19";
    }
    else{
        return "Felnőtt";
    }
}

function getAbsences($conn, $playerID){
    $sql = "SELECT * FROM absences WHERE player_ID = '$playerID'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck == 0){
        $result = "nincs rögzített hiányzás";
    }
    return $result;
}

function showAbsences($conn, $playerID){
   $result = getAbsences($conn, $playerID);
   if($result == "nincs rögzített hiányzás"){
    ?>
    <h3>Ön még nem rögzített hiányzást a rendszerben...</h3>
    <?php
   }
   else{
    ?>
    <div class="table-responsive border border-light rounded p-2">
        <table id="table" class="table table-hover text-light">
            <thead>
                <tr>
                    <th>Kezdő dátum</th>
                    <th>Befejező dátum</th>
                    <th>Hiányzás oka</th>
                    <th>Bövebben</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <td>
                        <?php
                        echo $row["absences_start_date"];
                        ?>
                    </td>
                    <td>
                            <?php
                            echo $row["absences_end_date"]; 
                            ?>
                    </td>
                    <td>
                            <?php
                            echo $row["absences_reason"];
                            ?>
                    </td>
                    <?php 
                        if($row["absences_explanation"] != ""){
                            ?>
                            <td>
                                <a class="btn btn-secondary mx-auto bi bi-three-dots"></a>
                            </td>
                            <?php 
                        }
                        else{
                            ?>
                            <td>
                                nincs indoklás
                            </td>
                            <?php
                        }
                    ?>
                    <td>
                        <a href="../pages/modifyAbsence.php?id=<?php echo $row["absence_ID"]; ?>" class="btn btn-secondary mx-auto bi bi-pencil"></a>
                    </td>
                    <td>
                        <a data-bs-toggle="modal" data-bs-target="#deleteAbsence<?php echo $row["absence_ID"]; ?>" class="btn btn-danger mx-auto bi bi-trash"></a>
                    </td>
                    <div class="modal fade" id="deleteAbsence<?php echo $row["absence_ID"]; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content text-dark">
                                <div class="modal-header">
                                    <h5 class="modal-title">Megerősítés</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Biztosan törli a hiányzását az adatbázisból?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                    <a href="../includes/deleteAbsence.inc.php?id=<?php echo $row["absence_ID"]; ?>" type="button" class="btn btn-danger">Törlés</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </tr>
                    <?php    
                }
                ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php   }
}

function showAllAbsences($conn, $teamid){
    $sql = "SELECT * FROM absences WHERE team_ID = '$teamid' AND absences_start_date > CURDATE() OR (absences_start_date < CURDATE() AND absences_end_date > CURDATE())";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        ?>
            <div class="table-responsive border border-light rounded p-2">
                <table id="table" class="table table-hover text-light">
                    <thead>
                        <tr>
                            <th>Játékos neve</th>
                            <th>Kezdő dátum</th>
                            <th>Befejező dátum</th>
                            <th>Hiányzás oka</th>
                            <th>Bövebben</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <td>
                                <?php
                                echo getPlayerNameFromID($conn, $row["player_ID"]);
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $row["absences_start_date"];
                                ?>
                            </td>
                            <td>
                                    <?php
                                    echo $row["absences_end_date"]; 
                                    ?>
                            </td>
                            <td>
                                    <?php
                                    echo $row["absences_reason"];
                                    ?>
                            </td>
                            <?php 
                                if($row["absences_explanation"] != ""){
                                    ?>
                                    <td>
                                        <a class="btn btn-secondary mx-auto bi bi-three-dots"></a>
                                    </td>
                                    <?php 
                                }
                                else{
                                    ?>
                                    <td>
                                        nincs indoklás
                                    </td>
                                    <?php
                                }
                            ?>
                        </tr>
                            <?php    
                        }
                        ?>
                        </tr>
                    </tbody>
                </table>
            </div>
    <?php
    }
    else{
        echo "nincs hiányzó játékosa a következő időszakra!";
    }
}

function addAbsence($conn, $playerID, $teamID, $start_date, $end_date, $reason, $explanation){
    $sql = "INSERT INTO absences (player_ID, team_ID, absences_start_date,absences_end_date,absences_reason,absences_explanation) VALUES ('" . $playerID . "','" . $teamID . "','" . $start_date . "','" . $end_date . "','" . $reason . "','" . $explanation . "');";
    mysqli_query($conn, $sql);
    header("Location: ../pages/squadUpdate.php?addAbsence=success");
    exit();
}

function deleteAbsence($conn, $id){
    $sql = "DELETE FROM absences WHERE absence_ID = '" . $id . "';";
    mysqli_query($conn, $sql);
    header("Location: ../pages/squadUpdate.php?deleteAbsence=success");
    exit();
}

function showNextTrainings($conn, $role){
    $sql ="SELECT * FROM trainings WHERE training_startDate > CURDATE() ORDER BY training_startDate ;";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        showNextTrainingsTable($conn, $result, $role);
    }
    else{
        echo "Nincs rögzítve edzés a hétre...";
        exit();
    }
}

function showNextTrainingsTable($conn, $result, $role){?>
    <div class="table-responsive border border-light rounded p-2">
        <table id="table" class="table text-white table-hover text-center">
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Korosztály</th>
                    <th>Edző</th>
                    <th>Helyszín</th>
                    <th>Várható létszám</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)){
            ?>    
                <tr>
                    <td><?php echo $row["training_startDate"]."-".$row["training_endDate"]; ?></td>
                    <td><?php echo turnTeamIDIntoString($row["team_ID"]); ?></td>
                    <td><?php echo turnCoachIDIntoName($conn, $row["coach_ID"]); ?></td>
                    <td><?php echo $row["training_place"]; ?></td>
                    <td>
                        <?php echo getNumberOfPlayersThatWillBeThereOnTraining($conn, $row["training_ID"]); ?>
                    </td>
                    <td>
                    <?php
                        if($role == 3){?>
                            <a data-bs-toggle="modal" data-bs-target="#replyTraining<?php echo $row["training_ID"]; ?>" class="btn btn-secondary bi bi-arrow-right-square"></a>
                            <div class="modal fade" id="replyTraining<?php echo $row["training_ID"]; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Visszajelzés</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        <form action="../includes/replyTraining.inc.php?trainingID=<?php echo $row["training_ID"]; ?>&playerID=<?php echo $_SESSION["playerid"]; ?>" method="post" id="trainingForm">
                                            <label for="selectReplyTraining"></label>
                                            <select name="selectReplyTraining" class="form-select">
                                                <option value="beThere">Ott leszek</option>
                                                <option value="cantBeThere">Nem tudok menni</option>
                                            </select>
                                            <button type="submit" name="submit" class="btn btn-success my-3">Rögzít</button>
                                        </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        else{ ?>
                            <a data-bs-toggle="modal" data-bs-target="#deleteTraining<?php echo $row["training_ID"]; ?>" class="btn btn-danger bi bi-trash <?php if($row["coach_ID"] != $_SESSION["coachid"]){echo "disabled";}?>"></a>
                            <div class="modal fade" id="deleteTraining<?php echo $row["training_ID"]; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Megerősítés</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Biztosan törli az edzést az adatbázisból?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                            <a href="../includes/deleteTraining.inc.php?id=<?php echo $row["training_ID"]; ?>" type="button" class="btn btn-danger">Törlés</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    ?>
                    </td>
                </tr>
            <?php
            }?>
            </tbody>
        </table>
    </div>
    <?php
}

function deleteTraining($conn, $id){
    $sql = "DELETE FROM trainings WHERE training_ID = '" . $id . "';";
    mysqli_query($conn, $sql);
    $sql = "DELETE FROM training_players WHERE training_ID = '" . $id . "';";
    mysqli_query($conn, $sql);
    header("Location: ../pages/trainings.php?deleteTraining=success");
    exit();
}

function addReplyTraining($conn, $reply, $trainingID, $playerID){
    $sql = "SELECT * FROM training_players WHERE training_ID = '$trainingID' AND player_ID = '$playerID'";
    $result =mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($reply == "beThere"){
        $reply = "Ott leszek";
    }
    else{
        $reply = "Nem tudok menni";
    }
    if($resultCheck > 0){
        $sql = "UPDATE training_players SET player_reply='$reply' WHERE training_ID = '$trainingID' AND player_ID='$playerID';";
        mysqli_query($conn, $sql);
        header("Location: ../pages/trainings.php?updateReply=success");
        exit();
    }
    else{
        $sql = "INSERT INTO training_players (training_ID, player_ID, player_reply) VALUES ('" . $trainingID . "','" . $playerID . "','" . $reply . "');";
        mysqli_query($conn, $sql);
        header("Location: ../pages/trainings.php?addReply=success");
        exit();
    }

}

function turnCoachIDIntoName($conn, $coachID){
    $sql = "SELECT * FROM coaches WHERE coach_ID = '$coachID'";
    $result = mysqli_query($conn,$sql);
    $coach = mysqli_fetch_assoc($result);
    return $coach["coach_Name"];
}

function showMyTrainings($conn, $teamID, $role){
    $sql = "SELECT * FROM trainings WHERE team_ID = '$teamID' AND training_startDate > CURDATE()";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        showNextTrainingsTable($conn, $result, $role);
    }
    else{
        echo "Nincs rögzítve edzés a hétre...";
        exit();
    }
}

function getNumberOfPlayersThatWillBeThereOnTraining($conn, $trainingID){
    $sql = "SELECT COUNT(training_ID) as letszam FROM training_players WHERE training_ID = '$trainingID' AND player_reply = 'Ott leszek'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['letszam'];
}

function addTraining($conn,$startDate,$endDate,$place,$coachID){
    $teamID = getTeamIDFromCoachID($conn, $coachID);
    if(!isTheGivenDateAndPlaceFree($conn, $startDate, $endDate, $place)){
        header("Location: ../pages/trainings.php?Date=isNotFree");
        exit();        
    }
    else{
        $sql = "INSERT INTO trainings (coach_ID, team_ID, training_startDate, training_endDate, training_place) VALUES ('" . $coachID . "','" . $teamID . "','" . $startDate . "','" . $endDate . "','" . $place . "');";
        mysqli_query($conn, $sql);
        header("Location: ../pages/trainings.php?addTraining=success");
        exit();
    }
}

function getTeamIDFromCoachID($conn, $coachID){
    $sql = "SELECT * FROM teams WHERE coach_ID = '$coachID'";
    $result = mysqli_query($conn,$sql);
    $team = mysqli_fetch_assoc($result);
    return $team["team_ID"];  
}

function isTheGivenDateAndPlaceFree($conn, $startDate, $endDate, $place){
    $sql = "SELECT * FROM trainings WHERE ((training_startDate < '$startDate' && training_endDate > '$startDate') OR (training_startDate < '$endDate' && training_endDate > '$endDate')) AND training_place ='$place'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        return false;
    }
    else{
        return true;
    }
}

function getNumberOfPlayedMatches($conn, $playerid){
    $sql = "SELECT COUNT(player_ID) as lejatszottmeccs FROM match_player WHERE player_ID = '$playerid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['lejatszottmeccs'];
}

function getNumberOfMatches($conn, $teamid){
    $sql = "SELECT COUNT(match_ID) as osszesmeccs FROM matches WHERE match_teamID = '$teamid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['osszesmeccs'];   
}

function getNumberOfTrainingParticipated($conn, $playerid){
    $sql = "SELECT COUNT(player_ID) as edzesek FROM training_players WHERE player_ID = '$playerid' AND player_reply = 'Ott leszek'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['edzesek'];
}

function getNumberOfTrainings($conn, $teamid){
    $sql = "SELECT COUNT(training_ID) as osszesedzes FROM trainings WHERE team_ID = '$teamid' AND training_StartDate < CURDATE()";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['osszesedzes'];   
}

function getNumberOfStartedMatches($conn, $playerid){
    $sql = "SELECT COUNT(player_ID) as kezdo FROM match_player WHERE player_ID = '$playerid' AND match_playerStart = '1'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['kezdo'];
}

function getNumberOfGoals($conn, $playerid){
    $sql = "SELECT SUM(match_playerGoal) as osszesgol FROM match_player WHERE player_ID = '$playerid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['osszesgol']; 
}

function getNextGame($conn, $teamid, $playerid){
    $sql = "SELECT * FROM squad_player WHERE player_ID = '$playerid'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $i = 0;
        $next = mysqli_fetch_assoc($result);
        while($row = mysqli_fetch_assoc($result)){
            $squadID = $row["squad_ID"];
            $sql2 = "SELECT * FROM squads WHERE squad_ID = '$squadID'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            if($i == 0){
                $next = $row2;
            }
            else{
                if($row2["squad_matchDate"] < $next["squad_matchDate"]){
                    $next = $row2;
                }
            }
            $i++;
        }
        return $next;
    }
    
    else{
        return false;
    }
}

function getNextTraining($conn, $teamid){
    $sql = "SELECT * FROM trainings WHERE team_ID = '$teamid' AND training_startDate > CURDATE() ORDER BY training_startDate LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        return mysqli_fetch_assoc($result);
    }
    else{
        return false;
    }
}

function getNumberOfPlayerOnEachTraining($conn, $coachid, $count){
    $numOfPlayersEachTraining = array();
    $sql = "SELECT * FROM trainings WHERE coach_ID = '$coachid' AND training_startDate < CURDATE() ORDER BY training_startDate LIMIT $count";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        while($row = mysqli_fetch_assoc($result)){
            $trainingID = $row["training_ID"];
            $date = $row["training_startDate"];
            $sql2 = "SELECT COUNT(training_ID) as letszam FROM training_players WHERE training_ID = '$trainingID' AND player_reply = 'Ott leszek'";
            $result2 = mysqli_query($conn, $sql2);
            $resultCheck2 = mysqli_num_rows($result2);
            if($resultCheck2 > 0){
                $row2 = mysqli_fetch_assoc($result2);
                $numOfPlayersEachTraining["$date"]=$row2["letszam"];
            }
        }
    }
    return $numOfPlayersEachTraining;
}

function getNumberOfPlayersOnEachMatch($conn, $coachid, $count){
    $numOfPlayersEachMatch = array();
    $teamid = getTeamIDFromCoachID($conn, $coachid);
    $sql = "SELECT * FROM matches WHERE match_teamID = '$teamid' AND match_date < CURDATE() ORDER BY match_date LIMIT $count";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        while($row = mysqli_fetch_assoc($result)){
            $matchID = $row["match_ID"];
            $date = $row["match_date"];
            $sql2 = "SELECT COUNT(match_ID) as letszam FROM match_player WHERE match_ID = '$matchID' AND match_playerTeam = 'Kunsziget SE'";
            $result2 = mysqli_query($conn, $sql2);
            $resultCheck2 = mysqli_num_rows($result2);
            if($resultCheck2 > 0){
                $row2 = mysqli_fetch_assoc($result2);
                $numOfPlayersEachMatch["$date"]=$row2["letszam"];
            }
        }
    }
    return $numOfPlayersEachMatch;
}

function getNumberOfAllPlayers($conn, $coachid){
    $teamid = getTeamIDFromCoachID($conn, $coachid);
    $sql = "SELECT COUNT(player_ID) as letszam FROM players WHERE team_ID = '$teamid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row["letszam"];
}

function addTravelOption($conn,$date,$place,$coachID){
    $teamID = getTeamIDFromCoachID($conn, $coachID);
    $sql = "SELECT * FROM travel_options WHERE travel_date = '$date' AND team_ID = '$teamID'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        header("Location: ../pages/travels.php?addTravelOption=ThereIsAnOptionThatDay");
        exit();
    }
    else{
        $shortDate = substr($date, 0, 10);
        $sql = "SELECT * FROM squads WHERE squad_matchDate = '$shortDate' AND team_ID = '$teamID'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            $sql = "INSERT INTO travel_options (travel_date, travel_startPlace, team_ID) VALUES ('" . $date . "','" . $place . "','" . $teamID . "');";            
            mysqli_query($conn, $sql);
            header("Location: ../pages/travels.php?addTravelOption=success");
            exit();
        }
        else{
            header("Location: ../pages/travels.php?addTravelOption=ThereIsNoMatch");
            exit();
        }
    }
}

function showTravelOptions($conn, $coachid){
    $sql = "SELECT * FROM travel_options WHERE travel_date > CURDATE()";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        ?>
        <div class="table-responsive border border-light rounded p-2">
        <table id="table" class="table text-white table-hover text-center">
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Korosztály</th>
                    <th>Indulás helye</th>
                    <th>Várható létszám</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)){
            ?>    
                <tr>
                    <td><?php echo $row["travel_date"] ?></td>
                    <td><?php echo turnTeamIDIntoString($row["team_ID"]) ?></td>
                    <td><?php echo $row["travel_startPlace"]; ?></td>
                    <td>
                        <?php echo getNumberOfPlayersThatWillUseTravelOption($conn, $row["travel_ID"]); ?>
                    </td>
                    <td>
                        <a data-bs-toggle="modal" data-bs-target="#deleteTravelOption<?php echo $row["travel_ID"]; ?>" class="btn btn-danger bi bi-trash <?php if($coachid != $_SESSION["coachid"]){echo "disabled";}?>"></a>
                        <div class="modal fade" id="deleteTravelOption<?php echo $row["travel_ID"]; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Megerősítés</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Biztosan törli az utazási opciót?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                        <a href="../includes/deleteTravelOption.inc.php?travelID=<?php echo $row["travel_ID"]; ?>" type="button" class="btn btn-danger">Törlés</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                    </td>
                </tr>
            <?php
            }?>
            </tbody>
        </table>
    </div>
    <?php
    
}

function deleteTravel($conn,$travelID){
    $sql = "DELETE FROM travel_options WHERE travel_ID = '$travelID'";
    mysqli_query($conn, $sql);
    header("Location: ../pages/travels.php?deleteTravel=success");
    exit();
}

function getNumberOfPlayersThatWillUseTravelOption($conn, $travelID){
    $sql = "SELECT COUNT(travel_ID) as letszam FROM traveling_players WHERE travel_ID = '$travelID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row["letszam"];
}

function showTravelOptionsIAmInvolved($conn, $teamid, $playerID){
    $sql = "SELECT * FROM travel_options WHERE team_ID = '$teamid' AND travel_date > CURDATE()";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        $row = mysqli_fetch_assoc($result);
        $shortDate = substr($row["travel_date"], 0, 10);
        $sql2 = "SELECT * FROM squads WHERE squad_matchDate = '$shortDate' AND squad_matchPlace <> 'Kunsziget'";
        $result2 = mysqli_query($conn, $sql2);
        $resultCheck2 = mysqli_num_rows($result2);
        if($resultCheck2 > 0){
            while($row2 = mysqli_fetch_assoc($result2)){
                $squadID = $row2["squad_ID"];
                $sql3 = "SELECT * FROM squad_player WHERE squad_ID = '$squadID' AND player_ID = '$playerID'";
                $result3 = mysqli_query($conn, $sql3);
                $resultCheck3 = mysqli_num_rows($result3);
                if($resultCheck3 > 0){?>
                    <div class="table-responsive border border-light rounded p-2">
            <table id="table" class="table text-white table-hover text-center">
                <thead>
                    <tr>
                        <th>Dátum</th>
                        <th>Korosztály</th>
                        <th>Indulás helye</th>
                        <th>Várható létszám</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)){
                ?>    
                    <tr>
                        <td><?php echo $row["travel_date"] ?></td>
                        <td><?php echo turnTeamIDIntoString($row["team_ID"]) ?></td>
                        <td><?php echo $row["travel_startPlace"]; ?></td>
                        <td>
                            <?php echo getNumberOfPlayersThatWillUseTravelOption($conn, $row["travel_ID"]); ?>
                        </td>
                        <td>
                        <?php
                        $reply = checkIfTravelReplyAlreadyGiven($conn, $playerID, $row["travel_ID"]);
                        if($reply == true){
                        ?>
                            <span>Már igényelte!</span>
                        <?php
                        }
                        else{
                        ?>
                            <a data-bs-toggle="modal" data-bs-target="#addTravelReply<?php echo $row["travel_ID"]; ?>" class="btn btn-info">Igénybe veszem</a>
                            <div class="modal fade" id="addTravelReply<?php echo $row["travel_ID"]; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Megerősítés</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Szeretné igénybe venni az utazási lehetőséget?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                            <a href="../includes/addTravelReply.inc.php?travelID=<?php echo $row["travel_ID"]; ?>&playerID=<?php echo $playerID; ?>" type="button" class="btn btn-success">Igen</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        }
                        ?>
                        </td>
                    </tr>
                <?php
                }
            else{
            ?>
                <h3 class="mt-5 text-light text-center">Nincs idegenbeli mérkőzés, melyen számítanak Önre a jövőben</h3>
            <?php
            }
                ?>
                </tbody>
            </table>
        </div><?php
                }
            }
            ?>
        
    <?php
    }
}

function addTravelReply($conn,$travelID,$playerID){
    $sql = "INSERT INTO traveling_players (travel_ID, player_ID) VALUES ('" . $travelID . "','" . $playerID . "');";   
    mysqli_query($conn, $sql);
    header("Location: ../pages/travels.php?addTravelReply=success");
    exit();
}

function checkIfTravelReplyAlreadyGiven($conn, $playerID, $travelID){
    $sql = "SELECT * FROM traveling_players WHERE travel_ID = '$travelID' AND player_ID = '$playerID'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        return true;
    }
    else{
        return false;
    }
}

function showEachTeam($conn){
    $sql = "SELECT * FROM teams";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        while($teamData = mysqli_fetch_assoc($result)){
            $teamid = $teamData["team_ID"];
            $coachid = $teamData["coach_ID"];
            $sql2 = "SELECT * FROM coaches WHERE coach_ID = '$coachid'";
            $result2 = mysqli_query($conn, $sql2);
            $coachData = mysqli_fetch_assoc($result2);
            $sql3 = "SELECT * FROM players WHERE team_ID = '$teamid' ORDER BY player_fullName";
            $result3 = mysqli_query($conn, $sql3);
            $resultCheck3 = mysqli_num_rows($result3);
            if($resultCheck3 > 0){
                ?>
                <div class="mb-3">
                <div class="text-white text-center p-5">
                <h1 class="bg-light text-primary rounded p-2"><?php echo turnTeamIDIntoString($teamid) ?> csapat:</h1>
                </div>
                <div class="table-responsive col-10 mx-auto">
                    <table class="table text-white table-hover text-center">
                        <thead>
                            <tr>
                                <th>Edző neve</th>
                                <th>Mobil</th>
                                <th>E-mail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $coachData["coach_Name"] ?></td>
                                <td><?php echo $coachData["coach_mobile"] ?></td>
                                <td><?php echo $coachData["coach_email"] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive col-8 mx-auto border border-light rounded p-2 text-light">
                    <table id="table" class="table text-light table-hover text-center">
                        <thead>
                            <tr>
                                <th>Kép</th>
                                <th>Játékos neve</th>
                                <th>Születési dátum</th>
                                <th>Poszt</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($playerData = mysqli_fetch_assoc($result3)){
                        ?>    
                            <tr>
                                <td><img class="rounded-circle lilImage" src="<?php echo $playerData["player_image"] ?>" alt=""></td>
                                <td><?php echo $playerData["player_fullName"] ?></td>
                                <td><?php echo $playerData["player_birthdate"] ?></td>
                                <td><?php echo $playerData["player_position"] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                </div>
            <?php
            }
        }
    }
}