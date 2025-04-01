<?php
    
    include_once("../allFunctions/connectPDO.php");

    if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['description'])&& isset($_POST['jobTitle']) && isset($_POST['jobType']) && isset($_POST['days']) && isset($_POST['pay']) && isset($_POST['address']) && isset($_POST['shifts'])){

        $title = $_POST['title'];
        $description = $_POST['description'];
        $jobTitle = $_POST['jobTitle'];
        $jobType = $_POST['jobType'];
        $days = $_POST['days'];
        $pay = $_POST['pay'];
        $address = $_POST['address'];
        $shifts = $_POST['shifts'];

        // // You can now insert this data into your database or perform other actions

        // // For example, insert into JobPosts table (make sure to sanitize/validate before use)
        $query = "INSERT INTO JobPosts (userid, posttitle, description, jobtitle, jobtype, jobdays, shifts, pay, adminstatus, address, poststatus)
                 VALUES (:userid, :posttitle, :description, :jobtitle, :jobtype, :jobdays, :shifts, :pay, :adminstatus, :address, :poststatus)";


        $indexPDO = connectedPDO();

        // Prepare the statement
        $stmt = $indexPDO->prepare($query);

        $stmt->execute([
            ':userid' => 2,  // Assuming $userid is set earlier in your code
            ':posttitle' => $title,
            ':description' => $description,
            ':jobtitle' => $jobTitle,
            ':jobtype' => $jobType,
            ':jobdays' => $days,
            ':shifts' => $shifts,  
            ':pay' => $pay,
            ':adminstatus' => 'pending',  // Set default admin status
            ':address' => $address,
            ':poststatus' => 'accepting'  // Set default post status
        ]);

        
    } else {
        // Handle the error case where one of the required fields is missing or empty
        echo "All required fields must be filled out.";
    }
