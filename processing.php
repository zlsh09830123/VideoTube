<?php
    require_once("includes/header.php");
    require_once("includes/classes/VideoUploadData.php");
    require_once("includes/classes/VideoProcessor.php");

    if(!isset($_POST['uploadButton'])) {
        echo "No file sent to page.";
        exit();
    }

    // Create file upload data
    $videoUploadData = new VideoUploadData($_FILES['fileInput'],
                                           $_POST['titleInput'], 
                                           $_POST['descriptionInput'], 
                                           $_POST['privacyInput'], 
                                           $_POST['categoryInput'],
                                           $userLoggedInObj->getUsername()
                                          );

    // Process video data (upload)
    $videoProcessor = new VideoProcessor($con);
    $wasSuccessful = $videoProcessor->upload($videoUploadData);

    // Check if upload was successful
    echo "Upload successful";
?>

