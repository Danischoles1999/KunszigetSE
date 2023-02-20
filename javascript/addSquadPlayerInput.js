$(document).ready(function () {
    $('#addPlayerLink').addClass("disabled");
    $("#inputDate").change(function () {
        var input = $('#inputDate').val();
        if (input != "") {
            $.ajax({
                url: "../includes/absentPlayers.inc.php",
                method: "POST",
                data: { input: input },
                success: function (data) {
                    $("#unavailablePlayers").html(data);
                    $('#addPlayerLink').removeClass("disabled");
                }
            })
        } else {
            $('#addPlayerLink').addClass("disabled");
        }
    })
})

function getUnavailablePlayersArray() {
    i = 1;
    numOfPlayers = document.getElementById("i").value;
    playerIDs = Array();
    while (i <= numOfPlayers) {
        playerID = document.getElementById("unavailable" + i).innerHTML;
        playerIDs.push(playerID);
        i++;
    }
    return playerIDs;
}

num = 0;
formerplayers = [];

function AddSquadPlayerInput(players) {
    if(document.getElementById("inputDate").value){
    unavailablePlayers = getUnavailablePlayersArray();
    if (unavailablePlayers != false) {
        for (let index = 0; index < unavailablePlayers.length; index++) {
            if(!formerplayers.includes(unavailablePlayers[index])){
                formerplayers.push(unavailablePlayers[index]);
            }
        }
    }
    }
    num++;
    const squadPlayerInputs = document.getElementById("squadPlayerInputs");
    formerSelect = null;
    if (num > 1) {
        formerSelect = document.getElementById(num - 1);
    }
    selectitems = 0;
    const row = document.createElement('div');
    const select = document.createElement('select');
    if(formerSelect != null){
        formerplayers.push(formerSelect.value);
    }
    row.className = "col-md-3 mt-3";
    select.className = "form-select mx-auto";
    select.name = "inputSquadPlayer[]";
    select.id = num;
    selectitems = 0;
    for (let index = 0; index < players.length; index++) {
        if (!formerplayers.includes(players[index].id)) {
            const opt = document.createElement("option");
            opt.value = players[index].id;
            opt.text = players[index].name;
            select.add(opt);
            selectitems++
        }
    }
    if (selectitems != 0) {
        row.appendChild(select);
        squadPlayerInputs.appendChild(row);
    }
    else {
        swal("Nem tud több játékost hozzáadni", "", "error");

    }

}
