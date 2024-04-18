<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>文件上傳</title>
    <style>
        #drop_zone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
        }
        #show_zone {
            margin-top: 10px;
        }
        img {
            width: 100px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="drop_zone">拖動檔案到這裡或 <input type="file" id="file_input" multiple accept="image/*,application/pdf"></input></div>
    <button onclick="uploadFiles()">上傳檔案</button>
    <div id="show_zone"></div>
    <script>
        // 參數設定
        let dropZone = document.getElementById('drop_zone');
        let showZone = document.getElementById('show_zone');
        let fileInput = document.getElementById('file_input');
        let file_element_name = 'file';  // FILE 表單元件名稱
        let url_upload = 'upload_save.php'; // PHP 處理檔案上傳的 URL

        let files = [];

        dropZone.addEventListener('dragover', (event) => {
            event.preventDefault(); // 防止預設行為
        });

        dropZone.addEventListener('drop', (event) => {
            event.preventDefault();
            addFiles(event.dataTransfer.files);
        });

        fileInput.addEventListener('change', () => {
            addFiles(fileInput.files);
        });

        function addFiles(newFiles) {
            newFiles = Array.from(newFiles); // 將 FileList 轉換為 Array
            newFiles.forEach(file => {
                if (!files.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified)) {
                    files.push(file); // 僅添加新檔案，避免重複
                    displayFile(file);
                }
            });
        }

        function displayFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const fileDiv = document.createElement('div');
                const fileInfo = document.createElement('div');
                fileInfo.textContent = `檔案待上傳: ${file.name}`;

                fileDiv.appendChild(fileInfo);

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    fileDiv.appendChild(img);
                }

                showZone.appendChild(fileDiv);
            };
            reader.readAsDataURL(file);
        }

        function uploadFiles() {
            const formData = new FormData();
            files.forEach(file => {
                formData.append('file[]', file);
            });

            fetch(url_upload, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json()) // 假設後端返回JSON格式
            .then(result => {
                showZone.innerHTML = ''; // 清空舊訊息
                result.messages.forEach(msg => {
                    const message = document.createElement('div');
                    message.textContent = msg;
                    showZone.appendChild(message);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                showZone.innerHTML = `錯誤: ${error}`;
            });
        }
    </script>
</body>
</html>
