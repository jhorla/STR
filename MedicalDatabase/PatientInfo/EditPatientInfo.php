<?php
require_once('..\database\database.php');
//echo "<alert>".$_POST['Submit']."</alert>";
$Occupations = GetRows("SELECT DISTINCT Occupation_ID, Occupation FROM occupations");
$Grades = GetRows("SELECT DISTINCT Grade_ID, Grade FROM studentgrades WHERE Grade_ID != 0");
$Units = GetRows("SELECT DISTINCT Unit_ID, Unit FROM employeesunits WHERE Unit_ID != 0");

$Sections = GetRows("SELECT Section_ID, Section FROM studentsections WHERE Section_ID != 0");
console_log($Sections);

$patientID = $_GET['patientID'];
$priorInfo = GetRow("SELECT * FROM patientinfo WHERE Patient_ID = ".$patientID);
//console_log($priorInfo);

if ($priorInfo['Occupation'] == 1){
  //fuck efficiency rn the future batches can improve on that I AM A ONE MAN ARMY OK
  $patientOccupDeets = GetRow("SELECT occupations.Occupation, occupations.Occupation_ID,
  Grade, studentgrades.Grade_ID,
  Section, studentsections.Section_ID
  FROM patientinfo
  INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID
  INNER JOIN studentgrades ON patientinfo.StudentGrade = studentgrades.Grade_ID
  INNER JOIN studentsections ON patientinfo.StudentSection = studentsections.Section_ID
  WHERE patientinfo.Patient_ID = ".$patientID); 
} elseif ($priorInfo['Occupation'] == 2){
  $patientOccupDeets = GetRow("SELECT occupations.Occupation, occupations.Occupation_ID,
  Unit_ID, Unit
  FROM patientinfo
  INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID
  INNER JOIN employeesunits ON patientinfo.FacultyUnit = employeesunits.Unit_ID
  WHERE patientinfo.Patient_ID = ".$patientID); 
}

//console_log($patientOccupDeets);
// check 1: same with V1.55
?>

<?php

if (!empty($_POST)) {
    if(isset($_POST['Submit'])) {
        $PatientName = $_POST['PatientName'];
        $PatientContNo = $_POST['PatientContact'];
        $EmContName = $_POST['PatientEmergencyName'];
        $EmContNo1 = $_POST['PatientEmergencyContact1'];
        $EmContNo2 = $_POST['PatientEmergencyContact2'];

        $PatientIDNo = $_POST['PatientIDNo'];
        $PatientOccup = $_POST['Occupation'];
        if($PatientOccup==1) {
          $PatientGrd = $_POST['GradeUnit'];
          $PatientSec = $_POST['Section'];
          $PatientUnit = NULL;
        } else {
          $PatientGrd = NULL;
          $PatientSec = NULL;
          $PatientUnit = $_POST['GradeUnit'];
        }

        $data = [
            'PN' => $PatientName,
            'PCN' => $PatientContNo,
            'ECN' => $EmContName,
            'EC1' => $EmContNo1,
            'EC2' => $EmContNo2,
            'PIDNo' => $PatientIDNo,
            'POCC' => $PatientOccup,
            'PGRD' => $PatientGrd,
            'PSEC' => $PatientSec,
            'PFAC' => $PatientUnit
        ];
        
        update("patientinfo",
        "Name = :PN,
        ContactNo = :PCN,
        EmergencyContactName = :ECN,
        EmergencyContactNo1 = :EC1,
        EmergencyContactNo2 = :EC2,
        IDNo = :PIDNo,
        Occupation = :POCC,
        StudentGrade = :PGRD,
        StudentSection = :PSEC,
        FacultyUnit = :PFAC",
        "Patient_ID = ".$patientID,
        $data);     
        }
      
        header("Location:patientinfo.php?patientID=".$patientID);
    }
?>

<html lang="en">
  <head>
    <meta charset="utf-8" >
    <title>Patient List</title>
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="../CSS_JS/CSSOverwrite.css">
    <script src="../CSS_JS/JS.js"></script>
    <script>
      function FetchGrades(id){
        // console.log("FetchGrades id: "+ id)
        $("#Grades").html('');
        $("#Sections").html('<option>Select Section</option>');
        $.ajax({
          type: 'post',
          url: '../getdata.php?patientID=<?php echo $patientID;?>',
          data: {Occupations:id},
          success:function(data){
            $("#Grades").html(data);
          }
        })
        
      }

      function FetchSections(id, occup){
        $("#Sections").html('');
        $("#Sections").html('<option>Select Section</option>');
        $.ajax({
          type: 'post',
          url: '../getdata.php?patientID=<?php echo $patientID;?>',
          data: {Grades:id, Occupation:occup},
          success:function(data){
            $("#Sections").html(data);
          }
        })
      }

     </script>
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

      <div id="genContSubCont" class="z-1 container row h-75 pt-3 p-1">                 
      
        
        <div class="container col-3 m-0 p-0 leftCont">                                                  
          <div id="patientOccupDeetsHeading" class="container p-3 h-60">                        
            Occupation Details
          </div>                                   

          <form id="patientOccupDeets" action="" method="post">
          <div class="container row p-0 m-0 mb-3 mt-2">
            <div id="OccupIDNoLabel" class="container py-2 col-sm-4">
              ID No: 
            </div>
            <input name="PatientIDNo" class="container px-3 py-2 col-sm-8"
              <?php
                if($priorInfo['IDNo']!=NULL){
                  echo ' value="'.$priorInfo['IDNo'].'" ';
                } else {
                  echo ' placeholder="xx-xxxx-xxx"';
                }
              ?>
            >
          </div>

          <div class="form-group">
            <label for="Occupation">Occupation</label>
            <select name="Occupation" id="Occupations" class="form-control" onchange="FetchGrades(this.value)">
              <option selected value="<?php echo $patientOccupDeets['Occupation_ID'];?>">
                <?php echo $patientOccupDeets['Occupation'];?>
              </option>

              <?php
                foreach($Occupations as $Occupation) {
              ?>
              <option value="<?php echo $Occupation['Occupation_ID'];?>"> <?php echo $Occupation['Occupation'];?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="Grade">Grade/Unit</label>
            <?php echo console_log($patientOccupDeets); ?>
            <select name="GradeUnit" id="Grades" class="form-control" onchange="FetchSections(this.value, document.getElementById('Occupations').value)">
              <option selected value="<?php
                if($patientOccupDeets['Occupation_ID']==1) {
                  echo $patientOccupDeets['Grade_ID'];
                } elseif($patientOccupDeets['Occupation_ID']==2){
                  echo $patientOccupDeets['Unit_ID'];
                }?>
              ">
                <?php
                  if($patientOccupDeets['Occupation_ID']==1) {
                    echo $patientOccupDeets['Grade'];
                  } elseif($patientOccupDeets['Occupation_ID']==2){
                    echo $patientOccupDeets['Unit'];
                  }
                ?>
              </option>

              <?php
                if($patientOccupDeets['Occupation_ID']==1){
                  foreach($Grades as $Grade) {
              ?>
                <option value="<?php echo $Grade['Grade_ID'];?>"><?php echo $Grade['Grade'];?></option>
              <?php }} else {
                  foreach($Units as $Unit) {
              ?>
                <option value="<?php echo $Unit['Unit_ID'];?>"><?php echo $Unit['Unit'];?></option>
              <?php }} ?>
            </select>
          </div>  

          <div class="form-group">
            <label for="Section">Section</label>
            <select name="Section" id="Sections" class="form-control" <?php if ($patientOccupDeets['Occupation_ID'] == 2){ echo "disabled = 'disabled'";};?>>
              <option selected value="<?php
                if ($patientOccupDeets['Occupation_ID'] == 1){
                  echo $patientOccupDeets['Section_ID'];
                } else {
                  echo NULL;
                }
              ?>">
                <?php
                  if ($patientOccupDeets['Occupation_ID'] == 1){
                   echo $patientOccupDeets['Section'];
                  } else {
                    echo "Unavailable";
                  }
                ?>
              </option>

              <?php
                foreach($Sections as $Section) {
              ?>
              <option value="<?php echo $Section['Section_ID'];?>"> <?php echo $Section['Section'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>                                                


        <div class="container col-8 p-0 ms-5 rightCont"> 
          <div id="patientInfoTableHeading" class="container p-3 h-60">
            Contact Details
          </div>

          <div id="patientInfoTable" class="container row row-cols-2 px-4">
            <div class="container px-3 py-2 col-sm-4" id="RowNameTopLeft">
              Name
            </div>
            <input type="text" id="PatientName" name="PatientName" class="container px-3 py-2 col-sm-8" id="RowDataTopRight"
              <?php
                if($priorInfo['Name']!=NULL){
                  echo ' value="'.$priorInfo['Name'].'" ';
                } else {
                  echo ' placeholder="First Name M.I. Last Name"';
                }
              ?>
            >

            <div class="container px-3 py-2 col-sm-4" id="RowName">
              Contact No.
            </div>
            <input type="text" id="PatientContact" name="PatientContact" class="container px-3 py-2 col-sm-8" id="RowData"
              <?php
                if($priorInfo['ContactNo']!=NULL){
                  echo ' value="'.$priorInfo['ContactNo'].'" ';
                } else {
                  echo ' placeholder="09xx xxx xxxx"';
                }
              ?>
              >

            <div class="container px-3 py-2 col-sm-4" id="RowName">
              Emergency Contact Name
            </div>
            <input type="text" id="PatientEmergencyName" name="PatientEmergencyName" class="container px-3 py-2 col-sm-8" id="RowData"
              <?php
                if($priorInfo['EmergencyContactName']!=NULL){
                  echo ' value="'.$priorInfo['EmergencyContactName'].'" ';
                } else {
                  echo ' placeholder="First Name M.I. Last Name"';
                }
              ?>
            >

            <div class="container px-3 py-2 col-sm-4" id="RowName">
              Emergency Contact No. 1
            </div>
            <input type="text" id="PatientEmergencyContact1" name="PatientEmergencyContact1"class="container px-3 py-2 col-sm-8" id="RowData"
              <?php
                if($priorInfo['EmergencyContactNo1']!=NULL){
                  echo ' value="'.$priorInfo['EmergencyContactNo1'].'" ';
                } else {
                  echo ' placeholder="09xx xxx xxxx"';
                }
              ?>
            >

            <div class="container px-3 py-2 col-sm-4" id="RowNameBottomLeft">
              Emergency Contact No. 2
            </div>
            <input type="text" id="PatientEmergencyContact2" name="PatientEmergencyContact2" class="container px-3 py-2 col-sm-8" id="RowDataBottomRight"
              <?php
                if($priorInfo['EmergencyContactNo2']!=NULL){
                  echo ' value="'.$priorInfo['EmergencyContactNo2'].'" ';
                } else {
                  echo ' placeholder="09xx xxx xxxx"';
                }
              ?>
            >
            </div>                                          

            <input id="pgButtons3" class="btn mt-4 buttonGroup" type="submit" value="Update" name="Submit">
            </form>
        </div>                                                
      </div>                                                   
    </div>                                                     
  </body>
</html>

