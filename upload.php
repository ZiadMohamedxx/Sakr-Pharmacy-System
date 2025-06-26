<?php
require_once("Database.php");
session_start();


if (!isset($_SESSION['customer_id'])) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($_FILES['file']['name']);
    $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "_", $filename);
    $relativePath = $uploadDir . time() . '_' . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $relativePath)) {
        file_put_contents('log.txt', "Moved file to: $relativePath\n", FILE_APPEND);

        $imagePath = $relativePath;
        $conn = new mysqli("localhost", "root", "", "pharmacy_db");

        if ($conn->connect_error) {
            header('Content-Type: application/json');
            file_put_contents('log.txt', "DB connection failed: {$conn->connect_error}\n", FILE_APPEND);
            echo json_encode(["success" => false, "message" => "DB connection failed"]);
            exit;
        }

        $uploadDate = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO prescription (customer_id, upload_date, image_url, status, comment) VALUES (?, ?, ?, 'Pending', 'None')");

        if (!$stmt) {
            header('Content-Type: application/json');
            file_put_contents('log.txt', "Prepare failed: {$conn->error}\n", FILE_APPEND);
            echo json_encode(["success" => false, "message" => "Prepare failed"]);
            exit;
        }

        $stmt->bind_param("iss", $customer_id, $uploadDate, $imagePath);

        if ($stmt->execute()) {
            header('Content-Type: application/json');
            file_put_contents('log.txt', "INSERTED: $imagePath for Customer ID: $customer_id\n", FILE_APPEND);
            echo json_encode(["success" => true, "message" => "Inserted successfully"]);
        } else {
            header('Content-Type: application/json');
            file_put_contents('log.txt', "Execute failed: {$stmt->error}\n", FILE_APPEND);
            echo json_encode(["success" => false, "message" => "Execute failed"]);
        }

        $stmt->close();
        $conn->close();
    } else {
        header('Content-Type: application/json');
        file_put_contents('log.txt', "move_uploaded_file failed\n", FILE_APPEND);
        echo json_encode(["success" => false, "message" => "File upload failed"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>File Upload</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <style>
        body {
            background: url("images/modified_cross_without_white_shape.png"), linear-gradient(to right, #30b0c7, #e6f0fa);
            font-family: 'Segoe UI', sans-serif;
        }

        .upload-container {
            background: #fff;
            width: 600px;
            margin: 50px auto;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
            padding: 32px 40px 32px 40px;
        }

        .upload-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 16px;
        }

        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin-bottom: 24px;
        }

        .drop-area {
            border: 2px dashed #b3c6e0;
            border-radius: 8px;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 1.2rem;
            margin-bottom: 24px;
            transition: border-color 0.2s;
        }

        .drop-area.dragover {
            border-color: #6cc24a;
            background: #f6fff4;
        }

        .choose-btn {
            display: block;
            margin: 0 auto 24px auto;
            background: #6cc24a;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 32px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .choose-btn:hover {
            background: #57a03a;
        }

        .upload-list {
            margin-left: 20px;
        }

        .upload-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .upload-icon {
            font-size: 1.8rem;
            margin-right: 12px;
        }

        .upload-details {
            flex: 1;
        }

        .upload-filename {
            font-weight: 500;
        }

        .upload-size {
            color: #888;
            font-size: 0.95rem;
            margin-left: 8px;
        }

        .upload-progress-bar {
            height: 5px;
            background: #e0e0e0;
            border-radius: 3px;
            margin: 8px 0;
            overflow: hidden;
        }

        .upload-progress {
            height: 100%;
            background: #6cc24a;
            width: 0;
            transition: width 0.3s;
        }

        .upload-status {
            font-size: 0.95rem;
            color: #888;
        }

        .upload-speed {
            float: right;
            font-size: 0.95rem;
            color: #888;
        }

        .upload-remove {
            background: none;
            border: none;
            color: #888;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: 10px;
        }

        .upload-remove:hover {
            color: #e74c3c;
        }

        .home-button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .home-btn {
            background-color: #3fa9f5;
            color: white;
            padding: 10px 25px;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .home-btn:hover {
            background-color: #2f90d1;
            transform: scale(1.05);
        }
    </style>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="logo-sakr-pharma fs-1 text-center justify-content-between sakr-heading">
        <h1 class="text-center my-5 ">Sakr Pharma</h1>
    </div>
    <div class="home-button-container">
        <a href="home.php" class="home-btn">Home</a>
    </div>
    <div class="upload-container">
        <div class="upload-title">File Upload</div>
        <hr class="divider">
        <div id="drop-area" class="drop-area">
            <div>
                <div style="text-align:center;">
                    <span style="font-size:2rem;color:#6cc24a;">&#8679;</span>
                </div>
                Drag files to upload
            </div>
        </div>
        <input type="file" id="fileElem" multiple style="display:none">
        <button class="choose-btn" onclick="document.getElementById('fileElem').click()">Choose File</button>
        <div class="upload-list" id="uploadList"></div>
    </div>

    <script>
        const dropArea = document.getElementById('drop-area');
        const fileElem = document.getElementById('fileElem');
        const uploadList = document.getElementById('uploadList');
        let uploads = [];

        function formatSize(bytes) {
            if (bytes >= 1024 * 1024)
                return (bytes / (1024 * 1024)).toFixed(1) + ' mb';
            if (bytes >= 1024)
                return (bytes / 1024).toFixed(1) + ' kb';
            return bytes + ' b';
        }

        function getIcon(file) {
            if (file.type.startsWith('image/')) return '🖼️';
            if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) return '📄';
            return '📁';
        }

        function renderUploads() {
            uploadList.innerHTML = '';
            uploads.forEach((upload, idx) => {
                uploadList.innerHTML += `
                <div class="upload-item">
                    <span class="upload-icon">${getIcon(upload.file)}</span>
                    <div class="upload-details">
                        <span class="upload-filename">${upload.file.name}</span>
                        <span class="upload-size">${formatSize(upload.file.size)}</span>
                        <div class="upload-progress-bar">
                            <div class="upload-progress" style="width:${upload.progress}%;background:${upload.progress===100?'#6cc24a':'#3fa9f5'}"></div>
                        </div>
                        <div>
                            <span class="upload-status">${upload.progress===100?'Completed':upload.progress+'% done'}</span>
                            ${upload.progress<100?`<span class="upload-speed">${upload.speed}KB/sec</span>`:''}
                        </div>
                    </div>
                    <button class="upload-remove" onclick="removeUpload(${idx})">&times;</button>
                </div>
                `;
            });
        }

        function removeUpload(idx) {
            uploads.splice(idx, 1);
            renderUploads();
        }

        function simulateUpload(upload) {
            let uploaded = 0;
            let total = upload.file.size;
            let speed = Math.floor(Math.random() * 100) + 50;
            upload.speed = speed;

            function step() {
                if (upload.progress >= 100) return;
                let chunk = speed * 1024 * 0.2;
                uploaded += chunk;
                upload.progress = Math.min(100, Math.round((uploaded / total) * 100));
                renderUploads();
                if (upload.progress < 100) {
                    setTimeout(step, 200);
                } else {
                    upload.speed = '';
                    renderUploads();
                }
            }
            step();
        }

        function uploadToServer(file) {
            const formData = new FormData();
            formData.append("file", file);

            fetch("upload.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("File has been sent to the doctor.");
                    } else {
                        alert("Upload failed: " + result.message);
                    }
                })
                .catch(() => {
                    alert("Image Uploading");

                });
        }

        function handleFiles(files) {
            for (let file of files) {
                let upload = {
                    file,
                    progress: 0,
                    speed: 0
                };
                uploads.push(upload);
                simulateUpload(upload);
                uploadToServer(file); // Server upload
            }
            renderUploads();
        }

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });
        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });
        fileElem.addEventListener('change', (e) => {
            handleFiles(e.target.files);
            fileElem.value = '';
        });
    </script>
</body>

</html>