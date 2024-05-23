<?php
require_once('..\database\database.php');
//echo "<alert>".$_POST['Submit']."</alert>";
$Occupations = GetRows("SELECT DISTINCT Occupation_ID, Occupation FROM occupations");
?>

<?php

if (!empty($_POST)) {
    //echo "<script><alert>".$_POST['Submit']."</alert></script>";

    if(isset($_POST['Submit'])) {

        //variable = select NAME value
        $PatientName = $_POST['PatientName'];
        $PatientContNo = $_POST['PatientContact'];
        $EmContName = $_POST['PatientEmergencyName'];
        $EmContNo1 = $_POST['PatientEmergencyContact1'];
        $EmContNo2 = $_POST['PatientEmergencyContact2'];
        
        $PatientIDNo = $_POST['PatientIDNo'];
        $PatientOccup = $_POST['Occupation'];

        if($PatientOccup == 1) {
          $PatientGrd = $_POST['GradeUnit'];
          $PatientUnit = NULL;
        } else {
          $PatientGrd = NULL;
          $PatientUnit = $_POST['GradeUnit'];
        }

        $PatientSec = $_POST['Section'];
        
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

        add("patientinfo", 
        "Patient_ID, Name, ContactNo, EmergencyContactName, EmergencyContactNo1, EmergencyContactNo2, IDNo, Occupation, StudentGrade, StudentSection, FacultyUnit", 
        "null, :PN, :PCN, :ECN, :EC1, :EC2, :PIDNo, :POCC, :PGRD, :PSEC, :PFAC", 
        $data);

        header("Location:patientinfo.php");
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
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="../CSS_JS/JS.js"></script>

    <link rel="stylesheet" href="../CSS_JS/CSSOverwrite.css">
    <script>
      function FetchGrades(id){
        console.log("FetchGrades id: "+ id)

        $("#Grades").html('');
        $("#Sections").html('<option>Select Section</option>');
        $.ajax({
          type: 'post',
          url: '../getdata.php',
          data: {Occupations:id},
          success:function(data){
            $("#Grades").html(data);
          }
        })
        
      }

      function FetchSections(id, occup){
        $('#Sections').html('');
        $('#Sections').html('<option>Select Section</option>');
        $.ajax({
          type: 'post',
          url: '../getdata.php',
          data: {Grades:id, Occupation:occup},
          success:function(data){
            $('#Sections').html(data);
          }
        })
      }
     </script>
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

      <div id="genContSubCont" class="z-1 container row h-75 m-3 mt-5 pt-3 p-1">                 
      
        <div class="container col-3 p-0 leftCont">                                                 
          <div id="patientOccupDeetsHeading" class="container p-3 h-60">                      
            Occupation Details
          </div>                                      

          <form id="patientOccupDeets" action="" method="post">
          <div class="container row p-0 m-0 mb-3 mt-2">
            <div id="OccupIDNoLabel" class="container py-2 col-sm-4">
              ID No: 
            </div>
            <input type="text" placeholder="xx-xxxx-xxx" name="PatientIDNo" class="container px-3 py-2 col-sm-8">
          </div>

          <div class="form-group">
            <label for="Occupation">Occupation</label>
            <select name="Occupation" id="Occupations" class="form-control" onchange="FetchGrades(this.value)">
              <option value="">Select Occupation</option>
              <?php
                foreach($Occupations as $Occupation) {
              ?>
              <option value="<?php echo $Occupation['Occupation_ID'];?>"> <?php echo $Occupation['Occupation'];?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <div for="Grade">Grade/Unit</div>
            <select name="GradeUnit" id="Grades" class="form-control" onchange="FetchSections(this.value, document.getElementById('Occupations').value)" >
              <option value="">Select Grade</option>
            </select>
          </div>  

          <div class="form-group">
            <label for="Section">Section</label>
            <select name="Section" id="Sections" class="form-control">
              <option value="">Select Section</option>
            </select>
          </div>
        </div>                                              


        <div class="container col-8 p-0 rightCont">
          <div id="patientInfoTableHeading" class="container p-3 h-60">
            Contact Details
          </div>

          <div id="patientInfoTable" class="container row row-cols-2 px-4">                       
            
            <div class="container px-3 py-2 col-sm-4" id="RowNameTopLeft">
              Name
            </div>
            <input type="text" placeholder="First Name M.I. Last Name" id="PatientName" name="PatientName" class="container px-3 py-2 col-sm-8" id="RowDataTopRight">

            <div class="container px-3 py-2 col-sm-4" id="RowName">
              Contact No.
            </div>
            <input type="text" placeholder="09xx xxx xxxx" id="PatientContact" name="PatientContact" class="container px-3 py-2 col-sm-8" id="RowData">

            <div class="container px-3 py-2 col-sm-4" id="RowName">
              Emergency Contact Name
            </div>
            <input type="text" placeholder="First Name M.I. Last Name" id="PatientEmergencyName" name="PatientEmergencyName" class="container px-3 py-2 col-sm-8" id="RowData">

            <div class="container px-3 py-2 col-sm-4" id="RowName">
              Emergency Contact No. 1
            </div>
            <input type="text" placeholder="09xx xxx xxxx" id="PatientEmergencyContact1" name="PatientEmergencyContact1"class="container px-3 py-2 col-sm-8" id="RowData">

            <div class="container px-3 py-2 col-sm-4" id="RowNameBottomLeft">
              Emergency Contact No. 2
            </div>
            <input type="text" placeholder="09xx xxx xxxx" id="PatientEmergencyContact2" name="PatientEmergencyContact2" class="container px-3 py-2 col-sm-8" id="RowDataBottomRight">
            </div>                                          

            <input id="pgButtons3" class="btn mt-4 buttonGroup" type="submit" value="Add" name="Submit">
            </form>
        </div>                                                
      </div>                                                     
    </div>                                            
  </body>

</html>

