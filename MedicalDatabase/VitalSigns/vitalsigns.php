<?php
require_once('..\database\database.php');

if(isset($_GET['patientID'])){
  $patientID = $_GET['patientID'];
  $patient = selectQuery("SELECT * FROM vitalsigns WHERE Patient_ID=".$patientID." ORDER BY VitalSigns_ID DESC");
} else {
  $patient = selectQuery("SELECT * FROM vitalsigns ORDER BY VitalSigns_ID DESC LIMIT 1");
  $patientID = $patient[0]['Patient_ID']; //patient that had the most recent log
}


$patientInfo = GetRow("SELECT * FROM patientinfo WHERE Patient_ID = ".$patientID);

if ($patientInfo['Occupation'] == 1){
  $patientOccupDeets = GetRow("SELECT occupations.Occupation, Grade, Section FROM patientinfo
  INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID
  INNER JOIN studentgrades ON patientinfo.StudentGrade = studentgrades.Grade_ID
  INNER JOIN studentsections ON patientinfo.StudentSection = studentsections.Section_ID
  WHERE patientinfo.Patient_ID = ".$patientID); 
}

$CheckIns = GetRows("SELECT * FROM checkins
INNER JOIN vitalsigns ON checkins.CheckIn_ID = vitalsigns.VitalSigns_ID
WHERE checkins.Patient_ID=".$patientID."
ORDER BY checkins.CheckIn_ID DESC");
$CheckInsNo = count($CheckIns);

if(isset($_GET['recentCheckIn'])){
  $CheckInID = $_GET['recentCheckIn'];
} elseif(isset($_GET['checkIn'])) {
  $CheckInID = $_GET['checkIn'];
} elseif($CheckInsNo!=0) {
  $CheckInID = $patient[0]['CheckIn_ID'];
} else {
  $CheckInID = null;
}


if ($CheckInID != null){
  $displayedCheckIn = GetRow("SELECT
  CheckInTime,
  CheckInDate,
  vitalsigns.*
  FROM vitalsigns INNER JOIN checkins ON
  vitalsigns.VitalSigns_ID = checkins.CheckIn_ID
  WHERE vitalsigns.Patient_ID=".$patientID." AND vitalsigns.CheckIn_ID=".$CheckInID."");

  $displayedVitals = GetRow("SELECT * FROM vitalsigns
  WHERE CheckIn_ID = ".$displayedCheckIn['CheckIn_ID']." AND 
  Patient_ID = ".$patientID."");
} else {
  $displayedCheckIn = [
    'CheckInTime' => 'This person has no check-ins yet.',
    'CheckInDate' => 'This person has no check-ins yet.'
  ];
  $displayedVitals = [
    'BloodPressure' => 'This person has no check-ins yet.',
    'Temperature' => 'This person has no check-ins yet.',
    'OxygenSaturation' => 'This person has no check-ins yet.',
    'PulseRate' => 'This person has no check-ins yet.',
    'DocsNote' => 'This person has no check-ins yet.'
  ];
}
?>

<?php 
  if(isset($_POST['DeleteVitalSignsY'])) {
    if($CheckInID!=null){
      $deletedVSID = $displayedVitals['VitalSigns_ID'];

      deletefromtable("checkins","CheckIn_ID=:id",["id"=>$displayedVitals['VitalSigns_ID']]);
      deletefromtable("vitalsigns","VitalSigns_ID=:id",["id"=>$displayedVitals['VitalSigns_ID']]);

      if($CheckInID<$patient[0]['CheckIn_ID']){

        for($VSCID = $patient[0]['CheckIn_ID'], $newVSCIDs = [], $i = 0, $VSID = $CheckIns[$i]['VitalSigns_ID']; $VSID >= $deletedVSID; $VSCID--, $i++, $VSID = $CheckIns[$i]['VitalSigns_ID']){
          array_push($newVSCIDs, ['VSCID'.$VSCID => $VSCID - 1]);
          update("vitalsigns", "CheckIn_ID = :VSCID".$VSCID, "VitalSigns_ID = ".$VSID, $newVSCIDs[$i]);
        }
        
      } 
    }
    header("Location:vitalsigns.php?patientID=".$patientID);
  } elseif (isset($_POST['DeleteVitalSignsN'])) {
    header("Refresh:0");
  }
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Patient List</title>
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../CSS_JS/CSSOverwrite.css">
    <script src="../CSS_JS/JS.js"></script>
  </head>
  <body>
    <div id="deleteConfirm" class="z-3 position-absolute top-50 start-50 translate-middle">
      <div id="deleteConfirmMessage">
        Delete recorded check-in and its respective vital signs of this person?
      </div>
      <form action="" method="POST">
        <input class="btn confirmBtns" type="submit" value="Yes" name="DeleteVitalSignsY">
        <input class="btn confirmBtns" type="submit" value="No" name="DeleteVitalSignsN">
      </form>
    </div>
    <div class="z-2 position-absolute overlay"></div>

    <nav class="h-auto p-3">
      <ul class="nav justify-content-end">
        <li class="nav-item">
          <a id="patientListBtn" class="nav-link" href="../index/index.php">Patient List</a>
        </li>
        <li class="nav-item">
          <a id="newPatientBtn" class="btn" role="button" href="../PatientInfo/AddPatientInfo.php">Add New Patient</a>
        </li>
      </ul>
    </nav>

    <div id="genContTitleBar" class="z-1 container w-90 h-75 position-absolute top-50 start-50 translate-middle my-4 p-0">
      <div class="container row">                                  
        <div id="titlebar" class="container col-sm-7 position-absolute start-0">
          Vital Signs
        </div>

        <div>
          <?php $patients = selectQuery("SELECT Name, Patient_ID, IDNo FROM patientinfo ORDER BY Name");?>
          <input id="searchbar" type="text" placeholder="Search for Patient" class="z-3 container p-2 col-sm-5 position-absolute end-0" onkeyup='search_patient(<?php echo json_encode($patients);?>)'>
        </div>

        <div id="SearchBarResults" class="z-2 container col-sm-5 position-absolute me-0 mt-1 pt-5 pb-3 px-0 top-0 end-0">
          <?php                                         
            foreach($patients as $patient){
            ?>
            <div name="patient" class="container row m-0 p-0">
              <a class="SearchBarPatients col-sm-11" id="person<?php echo $patient['Patient_ID'];?>" href="../PatientInfo/patientinfo.php?patientID=<?php echo $patient['Patient_ID'];?>">
                <?php echo "<div class='container row m-0 p-0'>
                  <div class='searchListIDNo col-sm-4'>ID: ".$patient['IDNo']."</div>
                  <div class='searchListName col-sm-7'>".$patient['Name']."</div>
                  <br></div>";
                ?>
              </a>
              <a class="SearchBarPatients searchListAdd col-sm-1" id="addPerson<?php echo $patient['Patient_ID'];?>" href="../VitalSigns/AddVitalSigns.php?patientID=<?php echo $patient['Patient_ID'];?>">+</a>
            </div>
            <?php
            }
          ?>
        </div>
      </div>                                                                    

      <div id="genContSubCont" class="z-1 container row h-75 pt-3 p-1">         

        <div class="container col-3 p-0 leftCont">                              
          <div id="patientDeetsVS" class="container px-3 py-2">
            <?php
              echo "Name: ".$patientInfo['Name'];
              echo "<br>";

              if ($patientInfo['Occupation'] == 1){
                echo "Grade: ".$patientOccupDeets['Grade'];
                echo "<br>";
                echo "Section: ".$patientOccupDeets['Section'];
              } else {
                echo "Unit: ";
              }
            ?>
          </div>                                               

          <div class="container p-3 h-60">                        
            <div class="d-grid">                               
              <div id="checkInDeets" class="container p-3">
                <div>CheckInTime: <?php echo $displayedCheckIn['CheckInTime']; ?></div>
                <div>CheckInDate: <?php echo $displayedCheckIn['CheckInDate']; ?></div>
              </div>

              <div id="checkInList" class="container p-3 list-group">
                <?php
                  foreach($CheckIns as $CheckIn) {
                ?>
                <a id="checkInListBtns" class="btn list-group-item" type="button"
                  href="vitalsigns.php?patientID=<?php echo $patientID;?>&checkIn=<?php echo $CheckIn['CheckIn_ID'];?>">
                  Check-in <?php echo $CheckIn['CheckIn_ID'];?>
                </a>
                <?php } ?>
                <a id="checkInListBtns" class="btn list-group-item" role="button"
                  href="AddVitalSigns.php?patientID=<?php echo $patientID;?>&checkInsNo=<?php echo $CheckInsNo;?>">
                  +
                </a>
              </div>
            </div>                                                
          </div>                                        
        </div>                                          

        <div class="container col-8 p-0 rightCont">  
          <div class="container row row-cols-2 ms-1 me-2 my-4 p-0">                         
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameTopLeft">
              Blood Pressure
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowDataTopRight">
              <?php if($displayedVitals['BloodPressure']!=NULL){echo $displayedVitals['BloodPressure'];}?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
              Temp
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowData">
              <?php if($displayedVitals['Temperature']!=NULL){echo $displayedVitals['Temperature']."°C";}?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
              Oxygen
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowData">
              <?php if($displayedVitals['OxygenSaturation']!=NULL){echo $displayedVitals['OxygenSaturation']."%";}?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameBottomLeft">
              Pulse Rate
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowDataBottomRight">
              <?php if($displayedVitals['PulseRate']){echo $displayedVitals['PulseRate']." BPM";}?>
            </div>
          </div>                                              

          <div id="docsNote" class="container h-25">
            <?php echo $displayedVitals['DocsNote'];?>
          </div>                                    


          <div class="container" id="buttonGroupVS">
            <a class="btn buttonGroup" id="pgButtons1"
              href="../PatientInfo/patientinfo.php?patientID=<?php echo $patientID;?>"
              role="button">Patient Info
            </a>

    
            <a class="btn buttonGroup" id="pgButtons2"
              <?php
                if ($CheckInID != null){
                  echo ' href="EditVitalSigns.php?patientID='.$patientID.'&checkIn='.$CheckInID.'" ';
                } else {
                  echo ' href="AddVitalSigns.php?patientID='.$patientID.'" ';
                }
                echo 'role="button">';

                if ($CheckInID != null){
                  echo 'Update';
                } else {
                  echo 'Add';
                }
              ?>
            </a>

            <?php
              if($CheckInID!=null){
            ?>
                <button class="btn buttonGroup" id="pgButtons3" onclick="DeleteConfirmation()">Delete</button>
            <?php
              }
            ?>

          </div>
         
        </div>                                                
      </div>                                                  
    </div>                                                    
  </body>
</html>

