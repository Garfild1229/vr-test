<!DOCTYPE html>
<html>
<head>
    <title>Formatowanie GUID</title>
    <style>
        .result-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .result {
            min-width: 400px; /* Wystarczająco szerokie, aby pomieścić najdłuższy GUID z klamrami */
            margin-right: 10px;
            word-break: break-all;
        }
        .copy-btn {
            cursor: pointer;
        }
        .guid-input {
            width: 450px; /* Wystarczająco szerokie, aby pomieścić najszerszy GUID */
        }
    </style>
</head>
<body>
<form id="guid-form" method="post" autocomplete="off">
    <button type="button" onclick="generateGuid()">Generuj GUID</button>
    <button type="button" onclick="generateAndFormatGuid()">Generuj i formatuj GUID</button>
    <div id="generated-guid-container"></div>
    <br><br>
    <label for="guid">Wprowadź GUID:</label><br>
    <input type="text" id="guid" name="guid" class="guid-input" required><br><br>
    <input type="submit" name="format" value="FORMATUJ">
</form>

<?php
session_start();

if (isset($_POST['format'])) {
    $guid = $_POST['guid'];
    formatGuid($guid);
}

function formatGuid($guid) {
    $cleanGuid = strtoupper(str_replace(['{', '}', '-'], '', $guid));

    $formattedGuid1 = strtoupper(substr($cleanGuid, 0, 8) . '-' . substr($cleanGuid, 8, 4) . '-' . substr($cleanGuid, 12, 4) . '-' . substr($cleanGuid, 16, 4) . '-' . substr($cleanGuid, 20));
    $formattedGuid2 = $cleanGuid;
    $formattedGuid3 = '{' . $formattedGuid1 . '}';
    $formattedGuid4 = strtolower($formattedGuid1);
    $formattedGuid5 = strtolower($cleanGuid);
    $formattedGuid6 = '{' . $formattedGuid4 . '}';

    $_SESSION['results'] = [
        $formattedGuid1,
        $formattedGuid2,
        $formattedGuid3,
        $formattedGuid4,
        $formattedGuid5,
        $formattedGuid6
    ];

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

if (isset($_SESSION['results'])) {
    echo "<h3>Wyniki formatowania:</h3>";
    $index = 0;
    foreach ($_SESSION['results'] as $result) {
        echo "<div class='result-container'>";
        echo "<span class='result' id='result-$index'>$result</span>";
        echo "<button class='copy-btn' onclick='copyToClipboard($index)'>Kopiuj</button>";
        echo "</div>";
        $index++;
    }
    unset($_SESSION['results']);
}
?>

<script>
    function generateGuid() {
        const guid = generateRandomGuid();
        const container = document.getElementById('generated-guid-container');
        container.innerHTML = ''; // Clear previous GUID
        const resultContainer = document.createElement('div');
        resultContainer.classList.add('result-container');

        const resultSpan = document.createElement('span');
        resultSpan.classList.add('result');
        resultSpan.textContent = guid;

        const copyButton = document.createElement('button');
        copyButton.classList.add('copy-btn');
        copyButton.textContent = 'Kopiuj';
        copyButton.onclick = function(event) {
            event.preventDefault();
            copyText(guid);
        };

        resultContainer.appendChild(resultSpan);
        resultContainer.appendChild(copyButton);
        container.appendChild(resultContainer);
    }

    function generateAndFormatGuid() {
        const guid = generateRandomGuid();
        document.getElementById('guid').value = guid;
        const form = document.createElement('form');
        form.method = 'post';
        form.innerHTML = `<input type="hidden" name="guid" value="${guid}">
                          <input type="hidden" name="format" value="true">`;
        document.body.appendChild(form);
        form.submit();
    }

    function generateRandomGuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        }).toUpperCase().replace(/-/g, '');
    }

    function copyToClipboard(index) {
        const text = document.getElementById(`result-${index}`).innerText;
        copyText(text);
    }

    function copyText(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }

    document.getElementById('guid-form').addEventListener('submit', function(event) {
        const guidInput = document.getElementById('guid');
        if (!guidInput.value) {
            event.preventDefault();
            alert('Proszę wypełnić to pole');
        }
    });
</script>
</body>
</html>
