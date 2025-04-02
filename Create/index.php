<?php
  include_once("../allFunctions/createNavBar/createNavBar.php");
  include_once("../allFunctions/connectPDO.php");

  $indexPDO = connectedPDO();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./create.css" >
  
    <!-- for nav  -->
    <link rel="stylesheet" href="../allFunctions/createNavBar/createNavBar.css">
    
</head>
<body>

    <?php
      createNavBar("search");
    ?>

    <div id="jobFormSection">
        <div id="jobForm">
            <h2>Create a Job Posting</h2>

            <label for="title">Post Titles:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="jobTitle">Job Titles:</label>
            <input list="jobTitleData" name="jobTitle" >
            <datalist id="jobTitleData" required>
                <?php
                    $jobTitlesQuery = $indexPDO->query("SELECT DISTINCT jobtitles FROM SettingsOptions WHERE jobtitles IS NOT NULL");
                    while ($jobTitle = $jobTitlesQuery->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$jobTitle['jobtitles']}\">{$jobTitle['jobtitles']}</option>";
                    }
                ?>
            </datalist>
            <div id="jobTitleErrorText"></div>

            <label for="jobType">Job Type:</label>
            <select id="jobType" name="jobType" required>
                <?php
                    $jobTypesQuery = $indexPDO->query("SELECT DISTINCT jobtypes FROM SettingsOptions WHERE jobtypes IS NOT NULL");
                    while ($jobType = $jobTypesQuery->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$jobType['jobtypes']}\">{$jobType['jobtypes']}</option>";
                    }
                ?>
            </select>


            <label for="days">Work Days:</label>
            <div id="days">
                <?php
                    // Fetch distinct work days from the database and generate checkboxes
                    $workDaysQuery = $indexPDO->query("SELECT DISTINCT jobdays FROM SettingsOptions WHERE jobdays IS NOT NULL");
                    while ($workDay = $workDaysQuery->fetch(PDO::FETCH_ASSOC)) {
                        $workDayName = htmlspecialchars($workDay['jobdays']);
                        echo "<label><input type='checkbox' name='days[]' value='$workDayName'> $workDayName</label><br>";
                    }
                ?>
            </div>

            <label for="shifts">Shifts:</label>
            <div id="allShifts">
                <div class="addedShift">
                    <input type="time"  name="startShifts" required>
                    <input type="time"  name="endShifts" required>
                </div>
            </div>
            <div id="addAnotherShiftJobPost" onclick="createAnotherShift();" > Add Another Shift <span>+</span></div>

            <label for="pay">Pay ($/hour):</label>
            <input type="number" id="pay" name="pay" min="0" required>

            <label for="address">Job Location:</label>
            <input type="text" id="address" name="address" required>

            <button type="submit" onclick="createJobPost()">Post Job</button>

            <div id="responseMessage">
                <?php
                    $indexPDO = connectedPDO();

                    $query = "SELECT * FROM JobPosts"; // Select everything from JobPosts table
                    $stmt = $indexPDO->query($query); // Execute the query
                    
                    // Fetch all rows from the query result
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Start the table
                    echo "<table border='1'>";
                    
                    // Generate the table headers dynamically
                    if (!empty($rows)) {
                        echo "<tr>";
                        foreach (array_keys($rows[0]) as $column) {
                            echo "<th>{$column}</th>";
                        }
                        echo "</tr>";
                    }
                    
                    // Generate the table rows
                    foreach ($rows as $row) {
                        echo "<tr>";
                        foreach ($row as $cell) {
                            echo "<td>{$cell}</td>";
                        }
                        echo "</tr>";
                    }
                    
                    // Close the table
                    echo "</table>";
                   
                ?>
            </div>
        </div>
    </div>



    <script src="./create.js"></script>
    <script src="../allFunctions/createNavBar/createNavBar.js"></script>
</body>
</html>