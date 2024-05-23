function DeleteConfirmation(){
    var confirmBoxOverlay = $(".overlay");
    var confirmBox = $("#deleteConfirm");

    confirmBoxOverlay.show();
    confirmBox.show();
}

function RerouteVS(){
    var confirmBoxOverlay = $(".overlay");
    var confirmBox = $("#addVSConfirm");

    confirmBoxOverlay.show();
    confirmBox.show();
}

function search_patient(patientlist) {
    var resultsContainer = $("#SearchBarResults");
    let input = document.getElementById('searchbar').value
    input = input.toLowerCase();
    const toDisplay = [];

    resultsContainer.css('display', 'none');
    for (toClear = 0; toClear<patientlist.length; toClear++){
      $("#person".concat(patientlist[toClear]['Patient_ID'])).css('display', 'none');
      $("#addPerson".concat(patientlist[toClear]['Patient_ID'])).css('display', 'none');
    }
  
    if (input == ""){
      return;
    }

    for (person = 0 ; person < patientlist.length ; person++) {          
      if (patientlist[person]['Name'].toLowerCase().includes(input) || patientlist[person]['IDNo'].includes(input)) {
        var personId = patientlist[person]['Patient_ID'];
        toDisplay.push("person".concat(personId));
        toDisplay.push("addPerson".concat(personId));
      }
    }

    for (counted = 0 ; counted < toDisplay.length ; counted++) {
      resultsContainer.css('display', 'block');
      $("#".concat(toDisplay[counted])).css('display', 'block');
    }
  }