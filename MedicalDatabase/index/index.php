<?php
require_once('../database/database.php');

$Occupations = selectQuery("SELECT Occupation, Occupation_ID FROM occupations");
$Grades = selectQuery("SELECT Grade, Grade_ID FROM studentgrades WHERE Grade_ID != 0");
$Faculty = selectQuery("SELECT * FROM patientinfo WHERE Occupation = 2");
$Units = selectQuery("SELECT Unit, Unit_ID FROM employeesunits WHERE Unit_ID != 0");
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Patient List</title>
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="../CSS_JS/JS.js"></script>
    <link rel="stylesheet" href="../CSS_JS/CSSOverwrite.css">
  </head>

  <body>
    <nav class="h-auto p-3">
      <ul class="nav justify-content-end">
        <li class="nav-item">
          <a class="nav-link btn disabled" href="index.php">Patient List</a>
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

      <div id="genContSubCont" class="z-1 container row h-75 m-3 mt-5 pt-3 pe-5 pb-3">
      <div class="z-1 accordion" id="accordionFlushOccupations">
      <?php
          foreach($Occupations as $Occupation) {
        ?>        
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-occupation-<?php echo $Occupation['Occupation_ID']; ?>" aria-expanded="false" aria-controls="flush-occupation-<?php echo $Occupation['Occupation_ID']; ?>">
                <?php
                  if($Occupation['Occupation']=="Student"){
                    echo $Occupation['Occupation'];
                  } else {
                    echo "Employees";
                  }
                ?>
              </button>
            </h2>

            <div id="flush-occupation-<?php echo $Occupation['Occupation_ID']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushOccupations">
              <div class="accordion-body">
              <?php
                if($Occupation['Occupation']=="Student"){
                  foreach($Grades as $Grade) {
                ?>
                    <div class="accordion" id="accordionFlushGrade">
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-grade-<?php echo $Grade['Grade_ID']; ?>" aria-expanded="false" aria-controls="flush-grade-<?php echo $Grade['Grade_ID']; ?>">
                            Grade <?php echo $Grade['Grade']; ?>
                          </button>
                        </h2>
                        <div id="flush-grade-<?php echo $Grade['Grade_ID']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushGrade">
                          <div class="accordion-body">



                            <div class="accordion" id="accordionFlushSections">
                              <?php
                                $Sections = selectQuery("SELECT studentsections.Section, studentsections.Section_ID FROM studentsections
                                INNER JOIN studentgrades ON studentsections.Grade_ID = studentgrades.Grade_ID
                                WHERE studentgrades.Grade = ".$Grade['Grade']);

                                foreach($Sections as $Section){
                                ?>
                                  <div class="accordion-item">
                                    <h2 class="accordion-header">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-section-<?php echo $Section['Section_ID']; ?>" aria-expanded="false" aria-controls="flush-section-<?php echo $Section['Section_ID']; ?>">
                                        <?php echo $Section['Section']; ?>
                                      </button>
                                    </h2>
                                    <div id="flush-section-<?php echo $Section['Section_ID']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushSections">
                                      <div class="accordion-body">


                                        <div class="accordion" id="accordionFlushStudents">
                                          <?php
                                            $Students = selectQuery("SELECT * FROM patientinfo
                                            INNER JOIN studentsections ON studentsections.Section_ID = patientinfo.StudentSection
                                            WHERE studentsections.Section_ID = '".$Section['Section_ID']."'");                                          
                                            foreach($Students as $Student){
                                            ?>
                                            
                                            <a class="indexName" href="../PatientInfo/patientinfo.php?patientID=<?php echo $Student['Patient_ID'];?>">
                                              <div class="indexListNames"><?php echo $Student['Name'];?></div>
                                            </a>

                                          <?php
                                            }
                                          ?>
                                        </div>
                                      
                                      </div>
                                    </div>
                                  </div>
                              <?php
                                }
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                  }
                } elseif($Occupation['Occupation']=="Faculty"){
                    foreach($Units as $Unit){
                    ?>
                      <div class="accordion" id="accordionFlushUnits">
                        <div class="accordion-item">
                          <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-unit-<?php echo $Unit['Unit_ID']; ?>" aria-expanded="false" aria-controls="flush-unit-<?php echo $Unit['Unit_ID']; ?>">
                              <?php echo $Unit['Unit']; ?>
                            </button>
                          </h2>
                          <div id="flush-unit-<?php echo $Unit['Unit_ID']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushUnits">
                            <div class="accordion-body">
                                <?php
                                  $Faculty = selectQuery("SELECT * FROM patientinfo
                                  INNER JOIN employeesunits ON employeesunits.Unit_ID = patientinfo.FacultyUnit
                                  WHERE employeesunits.Unit_ID = '".$Unit['Unit_ID']."'");                                          
                                  foreach($Faculty as $FacultyMember){
                                  ?>                              
                                    <a class="indexName" href="../PatientInfo/patientinfo.php?patientID=<?php echo $FacultyMember['Patient_ID'];?>">
                                      <div class="indexListNames"><?php echo $FacultyMember['Name'];?></div>
                                    </a>
                                  <?php } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                    <?php }} ?>
              </div>
            </div>
          </div>
      <?php } ?>
      </div>
      </div>                                                                    
    </div>
  </body>
</html>
