<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BY OPEN-AI</title>
    <style>
        :root {
            --background-light: #f0f0f0;
            --background-dark: #121212;
            --chat-background-light: #ffffff;
            --chat-background-dark: #1e1e1e;
            --message-user-light: #dcf8c6;
            --message-user-dark: #00796b;
            --message-bot-light: #f1f0f0;
            --message-bot-dark: #333333;
            --message-hover-light: #e0e0e0;
            --message-hover-dark: #333333;
            --spinner-light: #ddd;
            --spinner-dark: #333;
        }

        body {
            background-color: var(--background-light);
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s, color 0.3s;
        }

        body[data-theme='dark'] {
            background-color: var(--background-dark);
            color: #ddd;
        }

        .chat-container {
            width: 90%;
            max-width: 1000px;
            height: 90vh;
            background: var(--chat-background-light);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding: 20px;
            border: 1px solid #e0e0e0;
            position: relative;
            transition: background-color 0.3s;
        }

        body[data-theme='dark'] .chat-container {
            background: var(--chat-background-dark);
            border: 1px solid #333;
        }

        .message {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
            position: relative;
            user-select: none;
        }

        .user-message {
            background-color: var(--message-user-light);
            align-self: flex-end;
            text-align: left;
            margin-left: auto;
        }

        .bot-message {
            background-color: var(--message-bot-light);
            align-self: flex-start;
            text-align: left;
            margin-right: auto;
        }

        body[data-theme='dark'] .user-message {
            background-color: var(--message-user-dark);
        }

        body[data-theme='dark'] .bot-message {
            background-color: var(--message-bot-dark);
        }

        .message:hover {
            background-color: var(--message-hover-light);
            transform: scale(1.02);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        body[data-theme='dark'] .message:hover {
            background-color: var(--message-hover-dark);
        }

        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        body[data-theme='dark'] .loading {
            background: rgba(0, 0, 0, 0.9);
        }

        .loading .spinner {
            width: 50px;
            height: 50px;
            border: 6px solid var(--spinner-light);
            border-top: 6px solid #00796b;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        body[data-theme='dark'] .loading .spinner {
            border: 6px solid var(--spinner-dark);
            border-top: 6px solid #4caf50;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 600px) {
            .chat-container {
                width: 100%;
                padding: 10px;
            }

            .message {
                max-width: 100%;
                font-size: 14px;
                padding: 10px;
            }

            #theme-toggle {
                font-size: 14px;
                padding: 8px 16px;
                bottom: 10px;
                right: 10px;
            }
        }

        @media (min-width: 1025px) {
            .chat-container {
                width: 70%;
                padding: 20px;
            }

            .message {
                max-width: 80%;
                font-size: 18px;
                padding: 15px;
            }

            #theme-toggle {
                font-size: 16px;
                padding: 10px 20px;
                bottom: 20px;
                right: 20px;
            }
        }


        #theme-toggle,
        #clear-history {
            position: fixed;
            bottom: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            color: #fff;
            cursor: pointer;
            font-size: 24px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #theme-toggle {
            right: 20px;
            background-color: #00796b;
        }

        #theme-toggle:hover {
            background-color: #004d40;
            transform: scale(1.05);
        }

        #theme-toggle::before {
            content: '☀️';
        }

        body[data-theme='dark'] #theme-toggle::before {
            content: '🌙';
        }

        #clear-history {
            right: 100px;
            background-color: #ff5722;
        }

        #clear-history:hover {
            background-color: #e64a19;
            transform: scale(1.05);
        }

        #context-menu {
            display: none;
            position: absolute;
            background-color: var(--chat-background-light);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1001;
            padding: 5px;
            user-select: none;
        }

        body[data-theme='dark'] #context-menu {
            background-color: var(--chat-background-dark);
        }


        #context-menu button {
            background: var(--message-user-light);
            border: 1px solid var(--message-hover-light);
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            text-align: left;
            border-radius: 5px;
            color: var(--background-dark);
            margin-bottom: 5px;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s, transform 0.3s;
            user-select: none;
        }


        #context-menu button:last-child {
            margin-bottom: 0;
        }

        body[data-theme='dark'] #context-menu button {
            background: var(--message-bot-dark);
            color: var(--background-light);
            border: 1px solid var(--message-hover-dark);
        }


        #context-menu button:hover {
            background-color: var(--message-hover-light);
            transform: scale(1.05);
        }

        body[data-theme='dark'] #context-menu button:hover {
            background-color: var(--message-hover-dark);
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <div class="loading">
        <div class="spinner"></div>
    </div>

    <div class="chat-container" id="chat-container">
        <?php
        $id = $_GET['id'] ?? null;
        $token = $_GET['token'] ?? null;

        require('sql.php');

        $res = mysqli_query($connect, "SELECT * FROM user_id WHERE user_id = '$id'");
        if ($row = mysqli_fetch_assoc($res)) {
            $user_id = $row['user_id'];
            $token2 = $row['token'];
            $lang = $row['lang'];

            switch ($lang) {
                case 'uz':
                    $topilmadi = "🤷🏼‍♂️ Ma'lumotlar mavjud emas!";
                    $xato = "⚠️ Noma'lum xatolik!";
                    $tarixTozalandi = "Tarix tozalandi!";
                    $nusxaOlindi = "Xabardan nusxa olindi!";
                    $topilmadi2 = "Topilmadi!";
                    break;
                case 'ru':
                    $topilmadi = "🤷🏼‍♂️ Данные недоступны!";
                    $xato = "⚠️ Неизвестная ошибка!";
                    $tarixTozalandi = "История очищена!";
                    $nusxaOlindi = "Копия сообщения сохранена!";
                    $topilmadi2 = "Не найдено!";
                    break;
                case 'en':
                    $topilmadi = "🤷🏼‍♂️ Data not available!";
                    $xato = "⚠️ Unknown error!";
                    $tarixTozalandi = "History cleared!";
                    $nusxaOlindi = "Message copied!";
                    $topilmadi2 = "Not found!";
                    break;
            }

            if ($id && $token) {
                if ($id == $user_id && $token == $token2) {
                    $file_path = "data/$id.json";
                    if (file_exists($file_path) && ($get = file_get_contents($file_path))) {
                        $json = json_decode($get, true);
                        if ($json !== null) {
                            foreach ($json as $index => $entry) {
                                if (isset($entry['content'])) {
                                    $matn = htmlspecialchars($entry['content'], ENT_QUOTES, 'UTF-8');
                                    $role_class = ($entry['role'] == 'user') ? 'user-message' : 'bot-message';
                                    echo '<div class="message ' . $role_class . '" data-index="' . $index . '">' . nl2br($matn) . '</div>';
                                }
                            }
                        } else {
                            echo $topilmadi;
                        }
                    } else {
                        echo $topilmadi;
                    }
                } else {
                    echo $topilmadi;
                }
            } else {
                echo $xato;
            }
        } else {
            echo $xato;
        }
        ?>
    </div>

    <button id="theme-toggle"></button>
    <button id="clear-history">🧹</button>

    <div id="context-menu">
        <button id="copy-btn">Nusxa olish</button>
        <button id="delete-btn">O'chirish</button>
    </div>

    <script>

        let nusxaOlindi = '<?php echo $nusxaOlindi; ?>';
        let tarixTozalandi = '<?php echo $tarixTozalandi; ?>';
        let topilmadi = '<?php echo $topilmadi2; ?>';

        window.addEventListener('load', function () {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.body.setAttribute('data-theme', savedTheme);
            }

            setTimeout(function () {
                document.querySelector('.loading').style.display = 'none';
            }, 500);

            const chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });

        document.getElementById('theme-toggle').addEventListener('click', function () {
            const currentTheme = document.body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });

        document.addEventListener('contextmenu', function (e) {
            if (e.target.classList.contains('message')) {
                e.preventDefault();
                const contextMenu = document.getElementById('context-menu');
                contextMenu.style.display = 'block';
                contextMenu.style.left = `${e.pageX}px`;
                contextMenu.style.top = `${e.pageY}px`;
                contextMenu.setAttribute('data-index', e.target.getAttribute('data-index'));
            } else {
                document.getElementById('context-menu').style.display = 'none';
            }
        });

        document.getElementById('copy-btn').addEventListener('click', function () {
            const index = document.getElementById('context-menu').getAttribute('data-index');
            const message = document.querySelector(`.message[data-index="${index}"]`).innerText;
            navigator.clipboard.writeText(message).then(() => {
                const notification = document.createElement('div');
                notification.textContent = nusxaOlindi;
                notification.style.position = 'fixed';
                notification.style.top = '10px';
                notification.style.left = '50%';
                notification.style.transform = 'translate(-50%, 0)';
                notification.style.backgroundColor = '#333';
                notification.style.color = '#fff';
                notification.style.padding = '10px';
                notification.style.borderRadius = '5px';
                notification.style.zIndex = '1000';
                document.body.appendChild(notification);
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 2000);
            });
            document.getElementById('context-menu').style.display = 'none';
        });

        document.getElementById('delete-btn').addEventListener('click', function () {
            const index = document.getElementById('context-menu').getAttribute('data-index');
            const messageElement = document.querySelector(`.message[data-index="${index}"]`);
            if (messageElement) {
                messageElement.style.opacity = '0';
                setTimeout(() => {
                    messageElement.remove();
                    fetch(`delete-message.php?id=${<?php echo $id; ?>}&index=${index}`, { method: 'POST' })
                        .then(response => {
                            if (response.ok) {
                                window.location.reload();
                            }
                        });
                }, 500);
            }
            document.getElementById('context-menu').style.display = 'none';
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#context-menu')) {
                document.getElementById('context-menu').style.display = 'none';
            }
        });

        document.getElementById('clear-history').addEventListener('click', function () {
            const id = "<?php echo $id; ?>";

            fetch('clear-history.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    const notification = document.createElement('div');

                    if (data.status === 'success') {
                        notification.textContent = tarixTozalandi;
                        setTimeout(() => {
                            document.body.removeChild(notification);
                            window.location.reload();
                        }, 2000);
                    } else {
                        notification.textContent = topilmadi;
                        setTimeout(() => {
                            document.body.removeChild(notification);
                        }, 2000);
                    }

                    notification.style.position = 'fixed';
                    notification.style.top = '10px';
                    notification.style.left = '50%';
                    notification.style.transform = 'translate(-50%, 0)';
                    notification.style.backgroundColor = '#333';
                    notification.style.color = '#fff';
                    notification.style.padding = '10px';
                    notification.style.borderRadius = '5px';
                    notification.style.zIndex = '1000';
                    document.body.appendChild(notification);
                })
                .catch(error => console.error('Xato:', error));
        });

    </script>

</body>

</html>