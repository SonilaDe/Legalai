<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['analysis' => 'File upload failed.']);
        exit;
    }

    $uploadDirectory = 'uploads/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true); // Create folder if not exists
    }

    $uploadFile = $uploadDirectory . basename($_FILES['file']['name']);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        
        // Extract the contents of the .docx file
        $zip = new ZipArchive;
        if ($zip->open($uploadFile) === TRUE) {
            
            // Extract the XML file that contains the document content
            $xmlContent = '';
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xmlContent = $zip->getFromIndex($index);
            }
            $zip->close();
            
            // Parse the XML content to extract the text
            if ($xmlContent) {
                $xml = simplexml_load_string($xmlContent);
                $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                
                $texts = $xml->xpath('//w:t');
                $text = '';
                foreach ($texts as $t) {
                    $text .= (string)$t . ' ';
                }
                
                // Now, send this extracted text to an AI API for analysis
                $analysis = analyzeWithAI($text);
                
                echo json_encode(['analysis' => $analysis]);
            } else {
                echo json_encode(['analysis' => 'Error reading the document content.']);
            }
        } else {
            echo json_encode(['analysis' => 'Error opening the file.']);
        }
    } else {
        echo json_encode(['analysis' => 'Error saving the file.']);
    }
} else {
    echo json_encode(['analysis' => 'No file uploaded.']);
}

// This is the function where you'll integrate the AI analysis
function analyzeWithAI($text) {
    // Replace with your AI API endpoint and key
    $apiKey = 'YOUR_API_KEY';
    $apiUrl = 'https://api.your-ai-service.com/analyze';  // Replace with the actual AI API URL

    // Send the extracted text to the AI API for analysis
    $postData = json_encode(['text' => $text]);

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);

    // Handle the AI response (for simplicity, assuming the API sends back a 'result' field)
    $responseData = json_decode($response, true);
    return $responseData['result'] ?? 'Error analyzing the document.';
}
?>
<?php
// This is where you would place your OpenAI API Key
$apiKey = '$apiUrl = 'https://api.openai.com/v1/completions';  // OpenAI API endpoint

// Check if the form is submitted and handle the uploaded file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // Handle the uploaded file
    $uploadedFile = $_FILES['file'];

    // Check for errors in the file upload
    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        // Move the file to a folder on the server
        $targetPath = 'uploads/' . basename($uploadedFile['name']);
        if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
            // Successfully uploaded file, now read its contents
            $fileContent = file_get_contents($targetPath);

            // Call the AI function for analysis
            $analysisResult = analyzeWithAI($fileContent, $apiKey, $apiUrl);

            // Return the result back to the user
            echo json_encode(['analysis' => $analysisResult]);
        } else {
            // Error moving the file
            echo json_encode(['analysis' => 'Error saving the file.']);
        }
    } else {
        // Error with the file upload
        echo json_encode(['analysis' => 'Error uploading the file.']);
    }
} else {
    echo json_encode(['analysis' => 'Invalid request.']);
}
;
// Function to send the text to the AI service (OpenAI)
function analyzeWithAI($text, $apiKey, $apiUrl) {
    // Prepare the request data
    $postData = json_encode([
        'model' => 'gpt-3.5-turbo',  // Example model, can be updated to other models
        'prompt' => "Analyze this legal document: $text",  // Send the document content to AI
        'max_tokens' => 2000  // Limit the length of the AI's response
    ]);

    // Initialize cURL session
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Get response as a string
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey  // Add the API key in the Authorization header
    ]);
    curl_setopt($ch, CURLOPT_POST, true);  // Make the request POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  // Attach the POST data

    // Execute cURL request and get the response
    $response = curl_exec($ch);
    curl_close($ch);

    // Parse the response from AI and return the analysis result
    $responseData = json_decode($response, true);
    return $responseData['choices'][0]['text'] ?? 'Error analyzing the document.';
}
?>

