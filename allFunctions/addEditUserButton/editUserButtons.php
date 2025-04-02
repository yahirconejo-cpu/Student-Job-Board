<?php
    include_once("../connectPDO.php");

    if(isset($_POST['table']) && isset($_POST['column'])){

        $table = $_POST['table'];
        $column = $_POST['column'];

        $myPDO = connectedPDO();

        if ($table == 'SettingsOptions') {

            $columnsQuery = $myPDO->query("PRAGMA table_info(SettingsOptions)");
            $columns = $columnsQuery->fetchAll(PDO::FETCH_COLUMN, 1); 

            if (!in_array($column, $columns)) {
                echo json_encode(['']);
                exit;
            }
            
            $getOptions = $myPDO->query("SELECT $column FROM SettingsOptions");
            $options = $getOptions->fetchAll(PDO::FETCH_COLUMN); 

            $options = array_filter($options, function($value) {
                return $value !== null;
            });
            
            $options = array_values($options);

            echo json_encode(!empty($options) ? $options : []);

        } else if ($table == 'Settings') {
            // Query the Settings table based on the column name
            // need it to work with sessions eventally for user id ****************************************************************************************
            $getUserSettings = $myPDO->prepare("SELECT $column FROM Settings WHERE userid = ?");
            $getUserSettings->execute([
                 1
            ]);
            $settings = $getUserSettings->fetch(PDO::FETCH_ASSOC);

            // $columnsQuery = $myPDO->query("PRAGMA table_info(Settings)");
            // $columns = $columnsQuery->fetchAll(PDO::FETCH_COLUMN, 1);

            // $getAllSettings = $myPDO->query("SELECT * FROM Settings"); 
            // $settings = $getAllSettings->fetchAll(PDO::FETCH_ASSOC); 

            // echo (json_encode($settings));

            // Return the setting value (this assumes only one result is expected)
            echo $settings[$column] != null ? $settings[$column] : json_encode([]);
        } else {
            echo 'Invalid table specified';
        }
    }

    if(isset($_POST["edit"]) && isset($_POST["column"])){
        $chosenListJson = $_POST['edit']; // Get the JSON string sent from JS
        $column = $_POST['column']; // Get the column name to update

        // Decode the JSON string into an array
        $chosenList = json_decode(urldecode($chosenListJson));

        if ($chosenList === null) {
            echo 'Invalid JSON data';
            exit;
        }
    
        // Convert the array back to a JSON string for storage
        $chosenListJson = json_encode($chosenList);
        
        $myPDO = connectedPDO();

        // $columnsQuery = $myPDO->query("PRAGMA table_info(Settings)");
        // $columns = $columnsQuery->fetchAll(PDO::FETCH_COLUMN, 1);

        // echo json_encode($columns);

        
        // $stmt = $myPDO->prepare("UPDATE Settings SET $column = :chosenList WHERE userid = :currentUserId");
        // $stmt->execute([
        //     ':chosenList' => $chosenListJson,
        //     ':currentUserId' => 1
        // ]);

        $query = "UPDATE Settings SET $column = :chosenList WHERE userid = :currentUserId";
        $stmt = $myPDO->prepare($query);
        $stmt->execute([
            ':chosenList' => $chosenListJson,
            ':currentUserId' => 1
        ]);

        // $columnsQuery = $myPDO->query("PRAGMA table_info(Settings)");
        // $columns = $columnsQuery->fetchAll(PDO::FETCH_COLUMN, 1);

        // echo json_encode($columns);

        $getAllSettings = $myPDO->query("SELECT * FROM Settings"); 
        $settings = $getAllSettings->fetchAll(PDO::FETCH_ASSOC); 

        echo (json_encode($settings));

    }