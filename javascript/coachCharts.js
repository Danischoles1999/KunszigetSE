//Edzéslátogatottság diagram

document.addEventListener("DOMContentLoaded", function () {
    i = 0;
    xValues = Array();
    yValues = Array();
    for (var key in numOfPlayersEachTraining) {
        xValues[i] = key.substring(0,10);
        yValues[i] = numOfPlayersEachTraining[key];
        i++;
    }


    new Chart("numberOfPlayersOnTrainings", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                label: "Edzés látogatottság",
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(4, 17, 201)",
                borderColor: "rgb(0, 255, 255)",
                data: yValues
            }]
        },
        options: {

        }
    });
})

//Mérkőzéseken résztvevők száma diagram

document.addEventListener("DOMContentLoaded", function () {
    i = 0;
    xValues = Array();
    yValues = Array();
    for (var key in numOfPlayersEachMatch) {
        xValues[i] = key.substring(0,10);
        yValues[i] = numOfPlayersEachMatch[key];
        i++;
    }


    new Chart("numberOfPlayersOnMatches", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                label: "Mérkőzés látogatottság",
                fill: false,
                lineTension: 0,
                backgroundColor: "rgb(4, 17, 201)",
                borderColor: "rgb(0, 255, 255)",
                data: yValues
            }]
        },
        options: {

        }
    });
})