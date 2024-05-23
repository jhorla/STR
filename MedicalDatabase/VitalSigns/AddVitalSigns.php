<?php
require_once('..\database\database.php');

$PatientID = $_GET['patientID'];

if(isset($_GET['checkInsNo'])){
  $CheckInsNo = $_GET['checkInsNo'];
} else {
  $CheckIns = GetRows("SELECT * FROM checkins
  WHERE Patient_ID=".$PatientID."
  ORDER BY CheckIn_ID DESC");
  $CheckInsNo = count($CheckIns);
}
console_log($CheckInsNo);

$patient = GetRows("SELECT * FROM vitalsigns WHERE Patient_ID =".$PatientID." ORDER BY CheckIn_ID DESC");
$patientInfo = GetRow("SELECT * FROM patientinfo WHERE Patient_ID = ".$PatientID);

if (!empty($_POST)) {
    if($_POST['Submit']) {
        // table stuff, checkins.CheckIn_ID = VitalSigns.VitalSigns_ID
        $CTableInc = (GetRow("SELECT CheckIn_ID FROM checkins ORDER BY CheckIn_ID DESC LIMIT 1")['CheckIn_ID']) + 1;
        $VTableInc = (GetRow("SELECT VitalSigns_ID FROM vitalsigns ORDER BY VitalSigns_ID DESC LIMIT 1")['VitalSigns_ID']) + 1;
        console_log($CTableInc);

        $CheckInID = $_GET['checkInsNo']+1;

        $CheckInDate = $_POST['ChInDate'];
        $CheckInTime = $_POST['ChInTime'];

        $dataCH = [
          'CID' => $CTableInc,
          'PID' => $PatientID,
          'CD' => $CheckInDate,
          'CT' => $CheckInTime
        ];

        $BloodPressure = $_POST['BloodPressure'];
        $Temperature = $_POST['Temp'];
        $OxygenSaturation = $_POST['OxygenSaturation'];
        $PulseRate = $_POST['PulseRate'];
        $DocsNote = $_POST['DocsNote'];

        $dataVS = [
            'VID' => $VTableInc,
            'PID' => $PatientID,
            'CID'=> $CheckInID,
            'BP' => $BloodPressure,
            'TP' => $Temperature,
            'OXYGN' => $OxygenSaturation,
            'PR' => $PulseRate,
            'DN' => $DocsNote
        ];

        //console_log($dataVS);

        add("vitalsigns",
        "VitalSigns_ID, Patient_ID, CheckIn_ID, BloodPressure, Temperature, OxygenSaturation, PulseRate, DocsNote",
        ":VID, :PID, :CID, :BP, :TP, :OXYGN, :PR, :DN",
        $dataVS);

        add("checkins", 
        "CheckIn_ID, Patient_ID, CheckInTime, CheckInDate",
        ":CID, :PID, :CT, :CD",
        $dataCH);

        header("Location:vitalsigns.php?patientID=".$PatientID);
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
          Patient List
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
        <form action="" method="POST">
        <div class="container col-3 p-0 m-0 leftCont" style="float: left">                                                
          <div id="patientDeetsVS" class="container px-3 py-2">
            <?php
              echo "Name: ".$patientInfo['Name']."\n";
              echo "<br>";
              echo "\nCheck-In #: ".($CheckInsNo+1);
            ?>
          </div>                                             
          <div id="checkInList" class="container pb-4 pt-3 ps-3">                      
            <div class="d-grid">                                  
              <div>CheckInTime</div>
              <input type="text" placeholder="24-Hr-Format" id="ChInTime" name="ChInTime" class="container px-3 py-2 col-sm-8">
              
              <div>CheckInDate</div>
              <input type="text" placeholder="MM/DD/YYYY" id="ChInDate" name="ChInDate" class="container px-3 py-2 col-sm-8">
              
            </div>                                               
          </div>                                      
        </div>                                                  

        <div class="container col-8 ms-0 pb-4 pt-1 ps-3 pe-5 h-100 rightCont" style="float: right; margin-right: 4rem"> 
            
            <div class="container row row-cols-2 ms-1 me-2 my-4 p-0">                       
                <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameTopLeft">
                  Blood Pressure
                </div>
                <input type="text" placeholder="Systolic/Diastolic" id="BloodPressure" name="BloodPressure" class="container px-3 py-2 col-sm-8">
                
                <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
                  Temperature
                </div>
                <input type="text" placeholder="Temperature °C" id="Temp" name="Temp" class="container px-3 py-2 col-sm-8">
                
                <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
                  Oxygen Saturation
                </div>
                <input type="text" placeholder="Oxygen Saturation %" id="OxygenSaturation" name="OxygenSaturation" class="container px-3 py-2 col-sm-8">

                <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameBottomLeft">
                  Pulse Rate
                </div>
                <input type="text" placeholder="BPM" id="PulseRate" name="PulseRate" class="container px-3 py-2 col-sm-8">
            </div>                                             

            <input type="text" placeholder="Chief Complaint and Intervention" id="docsNote" name="DocsNote" class="container ms-1 h-25" style="width: 100%; background-color: white"><!--Doc's Note-->
            
            <div class="container" id="buttonGroupVS">
              <input id="pgButtons2" class="btn buttonGroup me-4" type="submit" value="Add" name="Submit">
              <a id="pgButtons3" class="btn buttonGroup me-4" role="button"
                href="vitalsigns.php?patientID=<?php echo $PatientID;?>">Cancel</a>
            </div>
            </form>
        </div>                                                
      </div>                                                    
    </div>                                                      
  </body>
</html>

