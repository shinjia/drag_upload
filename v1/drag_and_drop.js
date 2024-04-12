let dropZone = document.getElementById('drop_zone');
let showZone = document.getElementById('show_zone');
let file_element_name = 'file';  // FILE 表單元件名稱

document.addEventListener("DOMContentLoaded", function () {

    dropZone.addEventListener('dragover', function(e) {
        e.stopPropagation();
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    });

    dropZone.addEventListener('drop', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var files = e.dataTransfer.files;

        for (var i=0, f; f=files[i]; i++) {
            uploadFile(f);
        }
    });

    function uploadFile(file) {
        var url = 'upload.php'; // PHP 處理檔案上傳的 URL
        var xhr = new XMLHttpRequest();
        var formData = new FormData();
        xhr.open('POST', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // 檔案上傳成功後的處理
                // alert(xhr.responseText); // 或其他回應處理
                console.log(xhr.responseText);
                // alert(obj.message);
                // 解析 JSON 字符串
                var obj;
                try {
                    obj = JSON.parse(xhr.responseText);
                    showZone.innerHTML += ('<br>' + obj.message);
                } catch (e) {
                    console.error("Error parsing JSON!", e);
                    showZone.innerHTML += "<br>發生錯誤，無法解析伺服器響應。";
                }
            }
            else {
                // console.error("Server responded with status: " + xhr.status);
                // showZone.innerHTML = "上傳失敗，服務器錯誤 " + xhr.status;
            }
        };
        formData.append(file_element_name, file);
        xhr.send(formData);
    }
});
