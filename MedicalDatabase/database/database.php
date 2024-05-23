<?php
    $servername = "127.0.0.1";
    $db_name = "patientdb";
    $dsn = "mysql:host={$servername};dbname={$db_name}";

	$username = "root";
	$password = "";

    global $conn;

    try {
        $conn = new PDO($dsn, $username, $password);    
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }

      
    function selectQuery($stmt){
        global $conn;
    
        $stmt = $conn->query($stmt);
    
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $rows;
    }

    function update($table, $change, $condition, $data){
        global $conn;
    
        $sql = "UPDATE ".$table." SET ".$change." WHERE ".$condition;
        console_log($sql);

        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    function add ($table, $columns, $values, $data){
        global $conn;
    
        $sql = "INSERT INTO ".$table." (".$columns.")
            VALUES (".$values.")";
    
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }
    function deletefromtable($table, $condition, $data){
        global $conn;

        $sql = "DELETE FROM ".$table." WHERE ".$condition;

        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    function GetRow($query,$params = []){
        global $conn;
        try{
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetch();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    function GetRows($query,$params = []){
        global $conn;
        try{
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    function GetRowsIndex($query){
        global $conn;
        try{
            $conn->query($query);
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' .json_encode($output, JSON_HEX_TAG). ');';
        if ($with_script_tags) {
            $js_code = '<script>' .$js_code. '</script>';
        }
        echo $js_code;
    }
   
?>
