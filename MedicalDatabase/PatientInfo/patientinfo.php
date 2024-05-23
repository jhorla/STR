<?php
require_once('..\database\database.php');
if (isset($_GET['patientID'])){
  $patientID = $_GET['patientID'];
  $displayedData = GetRow("SELECT * FROM patientinfo WHERE Patient_ID=".$patientID);
} else {
  $displayedData = GetRow("SELECT * FROM patientinfo ORDER BY Patient_ID DESC LIMIT 1");
}

$displayedOccup = selectQuery("SELECT * FROM patientinfo INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID WHERE patientinfo.Patient_ID='".$displayedData['Patient_ID']."'");
console_log($displayedOccup);

if ($displayedOccup[0]['Occupation'] == 'Student') {
  $displaySection = selectQuery("SELECT * FROM studentsections WHERE Section_ID=' ".$displayedOccup[0]['StudentSection']." '");
  $displayGrade = selectQuery("SELECT * FROM studentgrades WHERE Grade_ID =".$displayedOccup[0]['StudentGrade']);
} elseif ($displayedOccup[0]['Occupation'] == 'Faculty') {
  $displayUnit = selectQuery("SELECT * FROM employeesunits WHERE Unit_ID=' ".$displayedOccup[0]['FacultyUnit']." '");
  console_log($displayUnit);
}

$CheckInRecent = GetRow("SELECT * FROM vitalsigns
WHERE Patient_ID=".$displayedData['Patient_ID']."
ORDER BY CheckIn_ID DESC LIMIT 1");
?>

<?php
  if(isset($_POST['DeletePatientInfoY'])) {
    deletefromtable("patientinfo","Patient_ID=:id",["id"=>$patientID]);
    deletefromtable("checkins","Patient_ID=:id",["id"=>$patientID]);
    deletefromtable("vitalsigns","Patient_ID=:id",["id"=>$patientID]);
    header("Location:../index/index.php");
  } elseif (isset($_POST['DeletePatientInfoN'])) {
    header("Refresh:0");
  }

?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Patient Info</title>
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
          <a id="patientListBtn" class="btn nav-link" href="../index/index.php">Patient List</a>
        </li>
        <li class="nav-item">
          <a id="newPatientBtn" class="btn" role="button" href="../PatientInfo/AddPatientInfo.php">Add New Patient</a>
        </li>
      </ul>
    </nav>

    <div id="deleteConfirm" class="z-3 position-absolute top-50 start-50 translate-middle">
      <div id="deleteConfirmMessage">
        Delete all data (basic information, recorded check-ins, and recorded vital signs) of this person?
      </div>
      <form method="POST">
        <input class="btn confirmBtns" type="submit" value="Yes" name="DeletePatientInfoY">
        <input class="btn confirmBtns" type="submit" value="No" name="DeletePatientInfoN">
      </form>
    </div> 

    <div id="addVSConfirm" class="z-3 position-absolute top-50 start-50 translate-middle">
      <div id="AddVSConfirmMessage">
        Add a new check-in, or view check-in history?
      </div>
      <a class="btn confirmBtns"
        href="../VitalSigns/AddVitalSigns.php?patientID=<?php echo $patientID;?>"
        >Add
      </a>
      <a class="btn confirmBtns"
        href="../VitalSigns/vitalsigns.php?patientID=<?php echo $displayedData['Patient_ID'];?>&recentCheckIn=<?php
          if ($CheckInRecent==null){
            echo null;
          } else {
            echo $CheckInRecent['CheckIn_ID'];
          }?>">
        View
      </a>
      <a class="btn confirmBtns" id="cancelConfirmBtn"
        href=""
        >Cancel
      </a>
    </div>    

    <div class="z-2 position-absolute overlay"></div>

    <div id="genContTitleBar" class="z-1 container w-90 h-75 position-absolute top-50 start-50 translate-middle my-4 p-0">      
      <div class="container row">                              
        <div id="titlebar" class="container col-sm-7 position-absolute start-0">
          Patient Info
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

      <div id="genContSubCont" class="z-n1 container row h-75 pt-3 p-1">                
      
      
        <div class="container col-3 p-0 leftCont">    
          <div id="patientOccupDeetsHeading" class="container p-3 h-60">                      
            Occupation Details
          </div>                                      
          <div id="patientOccupDeets">
            <?php
              if ($displayedOccup[0]['Occupation'] == 'Student'){
                echo '
                <div class="container row p-0 m-0 mb-3">
                  <div id="OccupIDNoLabel" class="container py-2 col-sm-4">
                    ID No: 
                  </div>
                  <div id="PatientIDNo" class="container px-3 py-2 col-sm-8">'.$displayedData['IDNo'].'</div>
                </div>
                ';
              }
            ?>
            <div id="patientOccupCont"> <?php
              echo "Occupation: ".$displayedOccup[0]['Occupation'];
              echo "<br>";
              if ($displayedOccup[0]['Occupation'] == 'Student'){

                echo "Grade: ".$displayGrade[0]['Grade'];
                echo "<br>";
                echo "Section: ".$displaySection[0]['Section'];
              } else {
                echo "Faculty Unit: ".$displayUnit[0]['Unit'];
              }
            ?>
            </div>
          </div>
          <br>

        </div>                                                

        <div class="container col-8 p-0 rightCont"> 
          <div id="patientInfoTableHeading" class="container p-3 h-60">
            Contact Details
          </div>
          <div id="patientInfoTable" class="container row row-cols-2 p-0">                       
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameTopLeft">
              Name
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowDataTopRight">
              <?php echo $displayedData['Name'];?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
              Contact No.
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowData">
            <?php echo $displayedData['ContactNo'];?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
              Emergency Contact Name
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowData">
            <?php echo $displayedData['EmergencyContactName'];?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowName">
              Emergency Contact No. 1
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowData">
            <?php echo $displayedData['EmergencyContactNo1'];?>
            </div>
            <div class="container px-3 py-2 col-sm-4 me-0" id="RowNameBottomLeft">
              Emergency Contact No. 2
            </div>
            <div class="container px-3 py-2 col-sm-7 ms-0" id="RowDataBottomRight">
            <?php echo $displayedData['EmergencyContactNo2'];?>
            </div>
          </div>                                           
          
          <div class="container" id="buttonGroup">
            <button class="btn buttonGroup" id="pgButtons1" onclick="RerouteVS()">
              Vital Signs
            </button>

            <a class="btn buttonGroup" id="pgButtons2"
              href="EditPatientInfo.php?patientID=<?php echo $displayedData['Patient_ID'];?>"
              role="button">Update
            </a>
            
            <button id="pgButtons3" class="btn buttonGroup" onclick="DeleteConfirmation()">Delete</button>
          </div>
        </div>                                                 
      </div>                                                     
    </div>                                                    
  </body>
</html>

