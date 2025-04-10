<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require_once('../connectPDO.php'); 

    // Check if request contains query parameters
    if (!isset($_POST['cardQuery'])) {
        echo json_encode(["error" => "No query parameters provided."]);
        exit;
    }

    // Get the current logged-in user

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['userid'])) {
        echo json_encode(["error" => "User not authenticated."]);
        exit;
    }


    $queryConditions =  json_decode($_POST['cardQuery'], true);
    

    $query = [];

    $myPDO = connectedPDO();

    $userType = "generic";

    $conditions = [];
    $params = [];

    $currentUserId = $_SESSION['userid']; 

    

   if ($queryConditions != null) {

        if (!array_key_exists( "owner", $queryConditions) || !empty($queryConditions->owner)) {
            $userTypeQuery = $myPDO->prepare("SELECT usertype FROM Users WHERE id = ?");
            $userTypeQuery->execute([
                $currentUserId
            ]);
            $userType = $userTypeQuery->fetchColumn();
        };
    
        // Build SQL query based on provided conditions
        foreach ($queryConditions as $key => $value) {
            if ($value !== null) {
                if(is_array($value)){
                    foreach( $value as $subValue){
                        if(str_contains($subValue,"%")) {
                            array_push($conditions, "$key LIKE ?");
                            array_push($params,  $subValue);
                        }else{
                            array_push($conditions, "$key = ?");
                            array_push($params,  $subValue);  
                        }
                    }
                }else{
                    if(str_contains($value,"%")) {
                        array_push($conditions, "$key LIKE ?");
                        array_push($params,  $value);
                    }else{
                        array_push($conditions, "$key = ?");
                        array_push($params,  $value);  
                    }
                }
                
            }
        }
    } else{
        $userTypeQuery = $myPDO->prepare("SELECT usertype FROM Users WHERE id = ?");
        $userTypeQuery->execute([
            $currentUserId
        ]);
        $userType = $userTypeQuery->fetchColumn();
    }



    // Determine the type of jobs to fetch based on user type
    if ($userType === "student") {
        $sql = "SELECT 
                    JobPosts.id AS postId,
                    Applications.status AS status,
                    JobPosts.posttitle AS jobTitle,
                    JobPosts.description AS jobDescription
                FROM Applications
                INNER JOIN JobPosts ON Applications.jobpostid = JobPosts.id
                WHERE Applications.userid = ?";

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        array_unshift($params, $currentUserId);

        $stmt = $myPDO->prepare($sql);
        $stmt->execute($params);
        
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if(count($jobs) > 0){
            foreach ($jobs as &$job) {
                $job['type'] = "student";
            }
        }else{
            $job['type'] = "student";
        }
    } elseif ($userType === "employer") {
        $sql = "SELECT 
            JobPosts.id AS postId,
            CASE 
                WHEN JobPosts.adminstatus = 'accepted' THEN JobPosts.poststatus 
                ELSE JobPosts.adminstatus 
            END AS status,
            JobPosts.posttitle AS jobTitle,
            JobPosts.description AS jobDescription,
            (SELECT COUNT(*) FROM Applications WHERE Applications.jobpostid = JobPosts.id) AS applicantsCount
            FROM JobPosts 
            WHERE JobPosts.userid = ?";

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        array_unshift($params, $currentUserId);

        $stmt = $myPDO->prepare($sql);
        $stmt->execute($params);
        
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($jobs) > 0){
            foreach ($jobs as &$job) {
                $job['type'] = "employer";
            }
        }else{
            $job['type'] = "employer";
        }
    } elseif($userType === "admin"){

        $sql = "SELECT  
            JobPosts.id AS postId, 
            JobPosts.adminstatus AS status, 
            JobPosts.posttitle AS jobTitle, 
            JobPosts.description AS jobDescription, 
            Users.username AS companyName,
            (SELECT COUNT(*) FROM Applications WHERE jobpostid = JobPosts.id) AS applicantsCount
            FROM JobPosts
            JOIN Users ON JobPosts.userid = Users.id
            ";

        if (!empty($conditions)) {
            $sql .= " WHERE  " . implode(" AND ", $conditions);
        }

        $stmt = $myPDO->prepare($sql);
        $stmt->execute($params);
        
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($jobs as &$job) {
            $job['type'] = "admin";
        }

    }else {
        // Generic job posts for users browsing jobs
        $sql = "SELECT 
            JobPosts.id AS postId, 
            JobPosts.posttitle AS jobTitle,
            Users.username AS companyName,
            JobPosts.description AS jobDescription
        FROM JobPosts
        INNER JOIN Users ON JobPosts.userid = Users.id
        LEFT JOIN Applications 
            ON JobPosts.id = Applications.jobpostid 
            AND Applications.userid = ? 
        WHERE JobPosts.adminstatus = 'accepted' 
        AND JobPosts.poststatus = 'accepting'
        AND Applications.id IS NULL";

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        array_unshift($params, $currentUserId);

        $stmt = $myPDO->prepare($sql);
        $stmt->execute($params);
        
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($jobs as &$job) {
            $job['type'] = "generic";
        }
    }

    // Return the fetched jobs as JSON
   echo json_encode($jobs) == "[]"? json_encode( array((Object) array("type" => $userType))) : json_encode($jobs) ;