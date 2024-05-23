<?php
require_once('..\database\database.php');

$patientID = $_GET['patientID'];
$CheckInID = $_GET['checkIn'];
$priorInfo = GetRow("SELECT
CheckInTime,
CheckInDate,
vitalsigns.*
FROM vitalsigns INNER JOIN checkins ON
vitalsigns.VitalSigns_ID = checkins.CheckIn_ID
WHERE vitalsigns.Patient_ID=".$patientID." AND vitalsigns.CheckIn_ID=".$CheckInID."");
console_log($priorInfo);

$CheckIns = GetRows("SELECT * FROM checkins
INNER JOIN vitalsigns ON checkins.CheckIn_ID = vitalsigns.VitalSigns_ID
WHERE checkins.Patient_ID=".$patientID."
ORDER BY checkins.CheckIn_ID DESC");
$CheckInsNo = count($CheckIns);

$patientInfo = GetRow("SELECT * FROM patientinfo WHERE Patient_ID = ".$patientID);
if ($patientInfo['Occupation'] == 1){
  $patientOccupDeets = GetRow("SELECT occupations.Occupation, Grade, Section FROM patientinfo
  INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID
  INNER JOIN studentgrades ON patientinfo.StudentGrade = studentgrades.Grade_ID
  INNER JOIN studentsections ON patientinfo.StudentSection = studentsections.Section_ID
  WHERE patientinfo.Patient_ID = ".$patientID); 
}


if (!empty($_POST)) {
    if($_POST['Submit']) {
        $CheckInTime = $_POST['ChInTime'];
        $CheckInDate = $_POST['ChInDate'];
        $BloodPressure = $_POST['BloodPressure'];
        $Temperature = $_POST['Temp'];
        $OxygenSaturation = $_POST['OxygenSaturation'];
        $PulseRate = $_POST['PulseRate'];
        $DocsNote = $_POST['DocsNote'];

        $dataVS = [
            'BP' => $BloodPressure,
            'TP' => $Temperature,
            'OXYGN' => $OxygenSaturation,
            'PR' => $PulseRate,
            'DN' => $DocsNote
        ];

        $dataCH = [
          'CT' => $CheckInTime,
          'CD' => $CheckInDate
        ];

        update("vitalsigns",
        "BloodPressure = :BP,
        Temperature = :TP,
        OxygenSaturation = :OXYGN,
        PulseRate = :PR,
        DocsNote = :DN",
        "VitalSigns_ID = ".$priorInfo['VitalSigns_ID'],
        $dataVS);

        update("checkins",
        "CheckInTime = :CT, CheckInDate = :CD",
        "CheckIn_ID = ".$priorInfo['VitalSigns_ID'],
        $dataCH);

        header("Location:vitalsigns.php?patientID=".$patientID."&checkIn=".$CheckInID);
    }

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

    <div id="genContTitleBar" class="z-2 container w-90 h-75 position-absolute top-50 start-50 translate-middle my-4 p-0">           
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
        <form action="" method="post">
        <div class="container col-3 p-0 m-0 leftCont" style="float: left">                                                  
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
                <div>CheckInTime:
                  <input type="text" id="ChInTime" name="ChInTime" class="container px-3 py-2 col-sm-8" id="RowData"
                    <?php
                      if($priorInfo['CheckInTime']!=NULL){
                        echo ' value="'.$priorInfo['CheckInTime'].'" ';
                      } else {
                        echo ' placeholder="24-Hr-Format" ';
                      }
                    ?>
                  >
                </div>
                <div>CheckInDate:
                  <input type="text" id="ChInDate" name="ChInDate" class="container px-3 py-2 col-sm-8" id="RowData"
                    <?php
                      if($priorInfo['CheckInDate']!=NULL){
                        echo ' value="'.$priorInfo['CheckInDate'].'" ';
                      } else {
                        echo ' placeholder="MM/DD/YYYY" ';
                      }
                    ?>
                  >
                </div>
              </div>

              <div id="checkInList" class="container p-3 list-group">
                <?php
                  //console_log($CheckIns);
                  foreach($CheckIns as $CheckIn) {
                    //console_log($CheckIn);
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

            <div class="container col-8 ms-0 pb-4 pt-1 ps-3 pe-5 h-100 rightCont" style="float: right; margin-right: 4rem">  <!--ch.inData-->
              <div class="container row row-cols-2 ms-1 me-2 my-4 p-0">                         <!--ch.in_table-->
                <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameTopLeft">
                  Blood Pressure
                </div>
                <input type="text" id="BloodPressure" name="BloodPressure" class="container px-3 py-2 col-sm-8" id="RowData"
                  <?php
                    if($priorInfo['BloodPressure']!=NULL){
                      echo ' value="'.$priorInfo['BloodPressure'].'" ';
                    } else {
                      echo ' placeholder="Systolic/Diastolic" ';
                    }
                  ?>
                >
                
                <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
                  Temperature
                </div>
                <input type="text" id="Temp" name="Temp" class="container px-3 py-2 col-sm-8" id="RowData"
                  <?php
                    if($priorInfo['Temperature']!=NULL){
                      echo ' value="'.$priorInfo['Temperature'].'" ';
                    } else {
                      echo ' placeholder="Temperature °C" ';
                    }
                  ?>
                >
                
                <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
                  Oxygen Saturation
                </div>
                <input type="text" id="OxygenSaturation" name="OxygenSaturation" class="container px-3 py-2 col-sm-8" id="RowData"
                  <?php
                    if($priorInfo['OxygenSaturation']!=NULL){
                      echo ' value="'.$priorInfo['OxygenSaturation'].'" ';
                    } else {
                      echo ' placeholder="Oxygen Saturation %" ';
                    }
                  ?>
                >

                <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameBottomLeft">
                  Pulse Rate
                </div>
                <input type="text" id="PulseRate" name="PulseRate" class="container px-3 py-2 col-sm-8" id="RowData"
                  <?php
                    if($priorInfo['PulseRate']!=NULL){
                      echo ' value="'.$priorInfo['PulseRate'].'" ';
                    } else {
                      echo ' placeholder="BPM" ';
                    }
                  ?>
                >
            </div>                                           

            <input type="text" id="docsNote" name="DocsNote" class="container ms-1 h-25" style="width: 100%; background-color: white"
              <?php
                if($priorInfo['DocsNote']!=NULL){
                  echo ' value="'.$priorInfo['DocsNote'].'" ';
                } else {
                  echo ' placeholder="Chief Complaint and Intervention" ';
                }
              ?>
            >
            <input id="pgButtons3" class="btn mt-4 me-4 buttonGroup" type="submit" value="Update" name="Submit">
          </form>
        </div>                                                
      </div>                                                  
    </div>                                                    
  </body>

</html>

