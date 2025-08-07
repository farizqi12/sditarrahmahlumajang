<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            color: #2c3e50;
            z-index: 10;
            position: relative;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 0px rgba(0,0,0,0.1);
            animation: bounce 2s infinite;
            color: #495057;
        }

        .map-container {
            position: relative;
            margin: 2rem 0;
            animation: mapSway 3s infinite ease-in-out;
        }

        .map-icon {
            font-size: 100px;
        }

        .message {
            font-size: 1.5rem;
            margin: 2rem 0;
            font-weight: 600;
            animation: fadeInOut 3s infinite;
        }

        .sub-message {
            font-size: 1rem;
            margin: 1rem 0;
            opacity: 0.8;
            font-weight: 400;
        }

        .back-button {
            background: linear-gradient(45deg, #5c67f2, #3c4ef2);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .icon {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float 6s infinite linear;
        }

        /* Animations */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        @keyframes mapSway {
            0%, 100% { transform: rotate(5deg); }
            50% { transform: rotate(-5deg); }
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        @media (max-width: 768px) {
            .error-code { font-size: 4rem; }
            .message { font-size: 1.2rem; }
            .map-icon { font-size: 80px; }
        }
    </style>
</head>
<body>
    <div class="floating-icons"></div>
    
    <div class="container">
        <div class="error-code">404</div>
        
        <div class="map-container">
            <div class="map-icon">🗺️</div>
        </div>
        
        <h1 class="message">Ups! Halaman Tidak Ditemukan! 🧭</h1>
        <p class="sub-message">
            Sepertinya Anda tersesat di belantara digital.<br>
            Mari kami bantu Anda kembali ke jalan yang benar!
        </p>
        
        <button class="back-button" onclick="goBack()">
            🏠 Kembali ke Beranda
        </button>
    </div>

    <script>
        // Fungsi untuk tombol kembali
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/';
            }
        }

        // Membuat ikon mengambang
        function createFloatingIcons() {
            const icons = ['🧭', '🗺️', '❓', '🤷‍♀️', '🤷‍♂️', '🛰️', '📡'];
            const container = document.querySelector('.floating-icons');
            
            setInterval(() => {
                const icon = document.createElement('div');
                icon.className = 'icon';
                icon.textContent = icons[Math.floor(Math.random() * icons.length)];
                icon.style.left = Math.random() * 100 + 'vw';
                icon.style.animationDuration = (Math.random() * 3 + 3) + 's';
                
                container.appendChild(icon);
                
                // Hapus ikon setelah animasi selesai
                setTimeout(() => {
                    if (container.contains(icon)) {
                        container.removeChild(icon);
                    }
                }, 6000);
            }, 800);
        }
        
        // Mulai animasi ikon mengambang
        createFloatingIcons();
    </script>
</body>
</html>