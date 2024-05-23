<?php
// TRUE echo (isset($_POST['Occupations'])) ? 'true' : 'false';
require_once('database/database.php');

$value_present = (isset($_POST['Occupations'])) ? 'true' : 'false';
console_log("Value is present for occupations: ".$value_present);

if(isset($_GET['patientID'])){
    $patientID = $_GET['patientID'];
    $priorInfo = GetRow("SELECT * FROM patientinfo WHERE Patient_ID = ".$patientID);
    console_log("prior info:");
    console_log($priorInfo);

    if ($priorInfo['Occupation'] == 1){
        $patientOccupDeets = GetRow("SELECT occupations.Occupation, occupations.Occupation_ID,
        Grade, studentgrades.Grade_ID,
        Section, studentsections.Section_ID
        FROM patientinfo
        INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID
        INNER JOIN studentgrades ON patientinfo.StudentGrade = studentgrades.Grade_ID
        INNER JOIN studentsections ON patientinfo.StudentSection = studentsections.Section_ID
        WHERE patientinfo.Patient_ID = ".$patientID); 
    } elseif ($priorInfo['Occupation'] == 2){
        console_log("here");
        $patientOccupDeets = GetRow("SELECT occupations.Occupation, occupations.Occupation_ID,
        Grade, studentgrades.Grade_ID,
        Section, studentsections.Section_ID
        FROM patientinfo
        INNER JOIN occupations ON patientinfo.Occupation = occupations.Occupation_ID
        INNER JOIN employeesunits ON patientinfo.FacultyUnit = employeesunits.Unit_ID
        WHERE patientinfo.Patient_ID = ".$patientID);
        console_log($patientOccupDeets);
    }

    console_log("Occup deets:");
    console_log($patientOccupDeets);
}

if(isset($_POST['Occupations'])){

    $id = $_POST['Occupations'];
    console_log("ID: ");
    console_log($id);

    if($id == 1) {
        $Grades = GetRows("SELECT DISTINCT Grade_ID, Grade FROM studentgrades WHERE Grade_ID != 0 AND Occupation_ID = ?", ["$id"]);
    } elseif($id == 2) {
        $Units = GetRows("SELECT DISTINCT Unit_ID, Unit FROM employeesunits WHERE Unit_ID != 0 AND Occupation_ID = ?", ["$id"]);
    }

    if($Grades != NULL){
        if($id == 1) {
            if(isset($_GET['patientID'])){
                // console_log("NANDITO");
                echo '<option>'.$patientOccupDeets['Grade'].'</option>';
            } else {
                echo '<option>Select Grade</option>';
            }
            
            foreach($Grades as $Grade){
                echo '<option value="'.$Grade['Grade_ID'].'">'.$Grade['Grade'].'</option>';
            }
        }
    } elseif($Units != NULL) {
        if($id == 2) {
            if(isset($_GET['patientID'])){
                // console_log("NANDITO");
                echo '<option>'.$patientOccupDeets['Unit'].'</option>';
            } else {
                echo '<option>Select Unit</option>';
            }
            
            foreach($Units as $Unit){
                echo '<option value="'.$Unit['Unit_ID'].'">'.$Unit['Unit'].'</option>';
            }
        }
    } else {
        echo '<option>Unapplicable</option>';
    }
}

if(isset($_POST['Grades'])){
    $id = $_POST['Grades'];

    if($_POST['Occupation'] == 1) {
        $Sections = GetRows("SELECT DISTINCT Section_ID, Section FROM studentsections WHERE Section_ID != 0 AND Grade_ID = ?", ["$id"]);
    }

    if($Sections != NULL){
        if (isset($_GET['patientID'])){
            echo '<option>'.$patientOccupDeets['Section'].'</option>';
        } else {
            echo '<option>Select Section</option>';
        }
        
        foreach($Sections as $Section){
            echo '<option value="'.$Section['Section_ID'].'">'.$Section['Section'].'</option>';
        }
    } else {
        echo '<option>Unapplicable</option>';
    }
}
?>