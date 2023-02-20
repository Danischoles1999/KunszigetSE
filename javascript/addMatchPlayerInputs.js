$(document).ready(function () {
    $("#inputSeason").change(function () {
        var input = $('#inputSeason').val();
        if(input == 1){
            minYear = "2022";
            maxYear = "2023";
        }
        else if(input == 2){
            minYear = "2023";
            maxYear = "2024";
        }
        else if(input == 3){
            minYear = "2024";
            maxYear = "2025";
        }
        else if(input == 4){
            minYear = "2025";
            maxYear = "2026";
        }
        else if(input == 5){
            minYear = "2026";
            maxYear = "2027";
        }
        else if(input == 6){
            minYear = "2027";
            maxYear = "2028";
        }
        
        minDate = minYear+"-01-01";
        maxDate = maxYear+"-12-31";
        $("#inputDateOfGame").attr("min",minDate);
        $("#inputDateOfGame").attr("max",maxDate);
    })
})

index = 1;
function AddMatchPlayerInput(){
    const playerInputs = document.getElementById("playersInputs");
    const row = document.createElement('div');
    row.className="row";
    const home = document.createElement("div");
    home.className="col-6 d-flex justify-content-evenly";
    const inputHomeName = document.createElement("input");
    inputHomeName.type="text";
    inputHomeName.className="col-6 mx-auto";
    inputHomeName.placeholder = "Név";
    inputHomeName.name = "homePlayerName[]";
    const inputHomeStart = document.createElement("select");
    inputHomeStart.name = "homePlayerStart[]";
    const opt1 = document.createElement("option");
    const opt2 = document.createElement("option");
    opt1.value = "1";
    opt1.text = "igen";
    opt2.value = "2";
    opt2.text = "nem";
    if(index<=11){
        opt1.selected;
    }
    else{
        opt2.selected;
    }
    inputHomeStart.add(opt1, null);
    inputHomeStart.add(opt2, null);
    const inputHomeGoal = document.createElement("input");
    inputHomeGoal.type="number";
    inputHomeGoal.className="col-1 mx-auto";
    inputHomeGoal.name = "homePlayerGoal[]";
    inputHomeGoal.defaultValue = "0";
    const inputHomeYellow = document.createElement("input");
    inputHomeYellow.type="number";
    inputHomeYellow.className="col-1 mx-auto";
    inputHomeYellow.name = "homePlayerYellow[]";
    inputHomeYellow.defaultValue = "0";
    const inputHomeRed = document.createElement("input");
    inputHomeRed.type="number";
    inputHomeRed.className="col-1 mx-auto";
    inputHomeRed.name = "homePlayerRed[]";
    inputHomeRed.defaultValue = "0";
    const away = document.createElement("div");
    away.className="col-6 d-flex justify-content-evenly";
    const inputAwayName = document.createElement("input");
    inputAwayName.type="text";
    inputAwayName.className="col-6 mx-auto";
    inputAwayName.placeholder = "Név";
    inputAwayName.name = "awayPlayerName[]";
    const inputAwayStart = document.createElement("select");
    inputAwayStart.name = "awayPlayerStart[]";
    const opt3 = document.createElement("option");
    const opt4 = document.createElement("option");
    opt3.value = "1";
    opt3.text = "igen";
    opt4.value = "2";
    opt4.text = "nem";
    inputAwayStart.add(opt3, null);
    inputAwayStart.add(opt4, null);
    const inputAwayGoal = document.createElement("input");
    inputAwayGoal.type="number";
    inputAwayGoal.className="col-1 mx-auto";
    inputAwayGoal.name = "awayPlayerGoal[]";
    inputAwayGoal.defaultValue = "0";
    const inputAwayYellow = document.createElement("input");
    inputAwayYellow.type="number";
    inputAwayYellow.className="col-1 mx-auto";
    inputAwayYellow.name = "awayPlayerYellow[]";
    inputAwayYellow.defaultValue = "0";
    const inputAwayRed = document.createElement("input");
    inputAwayRed.type="number";
    inputAwayRed.className="col-1 mx-auto";
    inputAwayRed.name = "awayPlayerRed[]";
    inputAwayRed.defaultValue = "0";
    home.appendChild(inputHomeName);
    home.appendChild(inputHomeStart);
    home.appendChild(inputHomeGoal);
    home.appendChild(inputHomeYellow);
    home.appendChild(inputHomeRed);
    away.appendChild(inputAwayName);
    away.appendChild(inputAwayStart);
    away.appendChild(inputAwayGoal);
    away.appendChild(inputAwayYellow);
    away.appendChild(inputAwayRed);
    row.appendChild(home);
    row.appendChild(away);
    playerInputs.appendChild(row);
    index++;
}

function playerIDOnlyNumbers(str) {
    correct = /^[0-9]+$/.test(str);
    if(!correct){
        swal("Az azonosító csak számokat tartalmazhat!","", "error").then(function(){window.location.href="../pages/games.php"});
    }
}
function playerIDLength(str) {
    if(str.length()){
        swal("Az azonosító 6 karakterből kell, hogy álljon!","", "error").then(function(){window.location.href="../pages/games.php"});
    }
}