<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Document AI Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script> <!-- TensorFlow.js (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script> <!-- Mammoth.js for Word -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script> <!-- PDF.js for PDFs -->
    <style>
        /* Add the styling as before */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 2rem;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #003366;
        }

        .file-upload {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        input[type="file"] {
            font-size: 1rem;
            padding: 10px;
            margin-right: 20px;
            border: 2px solid #003366;
            border-radius: 4px;
        }

        button {
            background-color: #003366;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #00509e;
        }

        pre {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            font-size: 1rem;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        .output {
            background-color: #e9f5ff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #666;
        }

        .footer a {
            color: #003366;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        Legal Document AI Analysis
    </header>
    
    <div class="container">
        <h2>Upload a Legal Document for Analysis</h2>
        <div class="file-upload">
            <input type="file" id="fileInput" />
            <button onclick="analyzeDocument()">Analyze Document</button>
            <button onclick="saveCase()" id="saveButton" style="display:none;">Save Case</button>
        </div>
        <div class="output">
            <h3>AI Analysis Result:</h3>
            <pre id="output">Your analysis will appear here.</pre>
        </div>
    </div>

    <div class="footer">
        <p>Powered by <a href="https://www.tensorflow.org/js" target="_blank">TensorFlow.js</a></p>
    </div>

    <script>
        let caseData = "";  // Variable to hold the case analysis

        // Function to analyze the document text
        async function analyzeDocument() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            
            if (!file) {
                alert("Please upload a document.");
                return;
            }

            // Check the file type
            const fileType = file.type;
            let text = "";

            if (fileType === "application/pdf") {
                // PDF file - Use pdf.js to extract text
                text = await extractTextFromPDF(file);
            } else if (fileType === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                // Word document - Use Mammoth.js to extract text
                text = await extractTextFromWord(file);
            } else {
                alert("Unsupported file type. Please upload a Word (.docx) or PDF file.");
                return;
            }

            // Display extracted text in the output area
            document.getElementById('output').textContent = "Extracting text...\n" + text;

            // Analyze the text with a simple AI model (keyword-based classifier)
            const analysis = analyzeText(text);
            document.getElementById('output').textContent += "\n\nAI Analysis: " + analysis;

            // Store the case data for saving
            caseData = analysis;

            // Show the save button
            document.getElementById('saveButton').style.display = 'block';
        }

        // Function to save the case to local storage
        function saveCase() {
            const caseNumber = "Case " + (localStorage.length + 1);  // Case number
            localStorage.setItem(caseNumber, caseData);  // Save the case data to local storage
            alert(`${caseNumber} saved!`);
        }

        // Function to extract text from PDF
        function extractTextFromPDF(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();

                reader.onload = function(event) {
                    const arrayBuffer = event.target.result;
                    const loadingTask = pdfjsLib.getDocument(arrayBuffer);

                    loadingTask.promise.then(function(pdf) {
                        let textContent = '';
                        let pagePromises = [];

                        for (let i = 1; i <= pdf.numPages; i++) {
                            pagePromises.push(pdf.getPage(i).then(function(page) {
                                return page.getTextContent();
                            }).then(function(text) {
                                textContent += text.items.map(item => item.str).join(' ') + '\n';
                            }));
                        }

                        Promise.all(pagePromises)
                            .then(() => resolve(textContent))
                            .catch(err => reject("Error extracting text from PDF."));
                    }).catch(function(error) {
                        reject("Error loading PDF.");
                    });
                };

                reader.onerror = function() {
                    reject("Error reading file.");
                };

                reader.readAsArrayBuffer(file);  // Use ArrayBuffer for PDF files
            });
        }

        // Function to extract text from Word file
        function extractTextFromWord(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    const arrayBuffer = event.target.result;
                    mammoth.extractRawText({ arrayBuffer: arrayBuffer })
                        .then(function(result) {
                            resolve(result.value);
                        })
                        .catch(function(err) {
                            reject("Error extracting text from Word document.");
                        });
                };
                
                reader.onerror = function() {
                    reject("Error reading file.");
                };
                
                reader.readAsArrayBuffer(file);  // Use ArrayBuffer for Word files
            });
        }

        // Simple text analysis function based on keywords
        function analyzeText(text) {
            const categories = {
                "Contract": ["agreement", "terms", "clause", "contract", "party"],
                "Litigation": ["lawsuit", "court", "case", "plaintiff", "defendant"],
                "Compliance": ["regulation", "compliance", "policy", "audit", "law"]
            };

            const lowerCaseText = text.toLowerCase();

            for (const category in categories) {
                for (const keyword of categories[category]) {
                    if (lowerCaseText.includes(keyword)) {
                        return `This document appears to be related to: ${category}. Please review the details of this category.`;
                    }
                }
            }

            return "Unable to classify the document. Please review manually.";
        }
    </script>
</body>
</html>
