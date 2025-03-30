<?php
    include_once("../connectPDO.php");

    if(isset($_POST['table']) && isset($_POST['column'])){

        $table = $_POST['table'];
        $column = $_POST['column'];

        $myPDO = connectedPDO();

        if ($table == 'SettingsOptions') {
            
            $getOptions = $myPDO->prepare("SELECT :column FROM SettingsOptions WHERE column_name = :column");
            $getOptions->execute([':column' => $column]);
            $options = $getOptions->fetch(PDO::FETCH_ASSOC);
    
            
            echo $options ? json_decode($options[$column]) : [""];

        } else if ($table == 'Settings') {
            // Query the Settings table based on the column name
            // need it to work with sessions eventally for user id ****************************************************************************************
            $getUserSettings = $myPDO->prepare("SELECT :column FROM Settings WHERE column_name = :column AND userid = :currentUserId");
            $getUserSettings->execute([
                ':column' => $column,
                ":currentUserId" => "1"
            ]);
            $settings = $getUserSettings->fetch(PDO::FETCH_ASSOC);
    
            // Return the setting value (this assumes only one result is expected)
            echo $settings ? json_decode($settings[$column]) : [""];
        } else {
            echo 'Invalid table specified';
        }
    }

    if(isset($_POST["edit"]) && isset($_POST["column"])){
        $chosenListJson = $_POST['edit']; // Get the JSON string sent from JS
        $column = $_POST['column']; // Get the column name to update

        // Decode the JSON string into an array
        $chosenList = json_decode($chosenListJson);

        if ($chosenList === null) {
            echo 'Invalid JSON data';
            exit;
        }
    
        // Convert the array back to a JSON string for storage
        $chosenListJson = json_encode($chosenList);

        $myPDO = connectedPDO();

        // Prepare the SQL update statement
        $stmt = $myPDO->prepare("UPDATE Settings SET $column = :chosenList WHERE column_name = :column");
        $stmt->execute([':chosenList' => $chosenListJson, ':column' => $column]);

        echo 'Update successful';  // You can return any response here

    }