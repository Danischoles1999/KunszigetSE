//Részvétel a meccseken diagram

document.addEventListener("DOMContentLoaded", function () {

  numberOfPlayedMatches = document.getElementById("numberOfPlayedMatches").value;
  numberOfMatches = document.getElementById("numberOfMatches").value;
  numberOfNotPlayedMatches = numberOfMatches - numberOfPlayedMatches;

  const data = {
    labels: [
      'Részt vett',
      'Nem vett részt'
    ],
    datasets: [{
      label: 'Részvétel a mérkőzéseken',
      data: [numberOfPlayedMatches, numberOfNotPlayedMatches],
      backgroundColor: [
        'rgb(2, 0, 193)',
        'rgb(160, 0, 0)'
      ],
      hoverOffset: 4
    }]
  };

  const config = {
    type: 'doughnut',
    data,
    options:{
      
     },
      plugins: {}
    
  };

  new Chart(
    document.getElementById('playedMatchesOfAllChart'),
    config
  );
})

//Kezdőcsapat tagja diagram

document.addEventListener("DOMContentLoaded", function () {

  numberOfMatchesStarted = document.getElementById("numberOfMatchesStarted").value;
  numberOfMatchesPlayed = document.getElementById("numberOfPlayedMatches").value;
  numberOfMatchesNotStarted = numberOfMatchesPlayed - numberOfMatchesStarted;

  const data = {
    labels: [
      'Kezdő',
      'Csere'
    ],
    datasets: [{
      label: 'Kezdőcsapat tagja',
      data: [numberOfMatchesStarted, numberOfMatchesNotStarted],
      backgroundColor: [
        'rgb(2, 0, 193)',
        'rgb(160, 0, 0)'
      ],
      hoverOffset: 4
    }]
  };

  const config = {
    type: 'doughnut',
    data,
    options:{
      
     },
      plugins: {}
    
  };

  new Chart(
    document.getElementById('stratedMatchesChart'),
    config
  );
})

//Részvétel az edzéseken diagram

document.addEventListener("DOMContentLoaded", function () {

  numberOfTrainingsParticipated = document.getElementById("numberOfTrainingsParticipated").value;
  numberOfTrainings = document.getElementById("numberOfTrainings").value;
  numberOfTrainingsNotParticipated = numberOfTrainings - numberOfTrainingsParticipated;

  const data = {
    labels: [
      'Részt vett',
      'Nem vett részt'
    ],
    datasets: [{
      label: 'Részvétel az edzéseken',
      data: [numberOfTrainingsParticipated, numberOfTrainingsNotParticipated],
      backgroundColor: [
        'rgb(2, 0, 193)',
        'rgb(160, 0, 0)'
      ],
      hoverOffset: 4
    }]
  };

  const config = {
    type: 'doughnut',
    data,
    options:{
      
     },
      plugins: {}
    
  };

  new Chart(
    document.getElementById('trainingsParticipatedOfAllChart'),
    config
  );
})