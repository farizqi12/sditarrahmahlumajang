<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
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

        .guard-container {
            position: relative;
            margin: 2rem 0;
        }

        .guard {
            width: 120px;
            height: 150px;
            margin: 0 auto;
            position: relative;
            animation: guardBounce 3s infinite ease-in-out;
        }

        .guard-body {
            width: 80px;
            height: 100px;
            background: #2c3e50;
            border-radius: 10px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 30px;
        }

        .guard-head {
            width: 60px;
            height: 60px;
            background: #f4d03f;
            border-radius: 50%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
        }

        .guard-eyes {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .eye {
            width: 8px;
            height: 8px;
            background: #2c3e50;
            border-radius: 50%;
            display: inline-block;
            margin: 0 5px;
            animation: blink 4s infinite;
        }

        .guard-mouth {
            width: 20px;
            height: 10px;
            border: 2px solid #2c3e50;
            border-top: none;
            border-radius: 0 0 20px 20px;
            position: absolute;
            top: 35px;
            left: 50%;
            transform: translateX(-50%);
        }

        .guard-arms {
            position: absolute;
            top: 40px;
        }

        .arm {
            width: 15px;
            height: 40px;
            background: #f4d03f;
            border-radius: 10px;
            position: absolute;
        }

        .arm.left {
            left: -10px;
            animation: waveLeft 2s infinite;
        }

        .arm.right {
            right: -10px;
            animation: waveRight 2s infinite;
        }

        .shield {
            width: 50px;
            height: 60px;
            background: #e74c3c;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 50px;
            border-radius: 5px 5px 25px 25px;
            animation: shieldGlow 2s infinite alternate;
        }

        .shield::after {
            content: "X";
            color: white;
            font-size: 24px;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
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
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
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

        @keyframes guardBounce {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes blink {
            0%, 90%, 100% { transform: scaleY(1); }
            95% { transform: scaleY(0.1); }
        }

        @keyframes waveLeft {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(-30deg); }
        }

        @keyframes waveRight {
            0%, 100% { transform: rotate(10deg); }
            50% { transform: rotate(30deg); }
        }

        @keyframes shieldGlow {
            0% { box-shadow: 0 0 10px rgba(231, 76, 60, 0.5); }
            100% { box-shadow: 0 0 20px rgba(231, 76, 60, 0.8); }
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
            .guard { transform: scale(0.8); }
        }
    </style>
</head>
<body>
    <div class="floating-icons"></div>
    
    <div class="container">
        <div class="error-code">403</div>
        
        <div class="guard-container">
            <div class="guard">
                <div class="guard-head">
                    <div class="guard-eyes">
                        <span class="eye"></span>
                        <span class="eye"></span>
                    </div>
                    <div class="guard-mouth"></div>
                </div>
                <div class="guard-body">
                    <div class="guard-arms">
                        <div class="arm left"></div>
                        <div class="arm right"></div>
                    </div>
                </div>
                <div class="shield"></div>
            </div>
        </div>
        
        <h1 class="message">Ups! Akses Ditolak! 🚫</h1>
        <p class="sub-message">
            Sepertinya Anda mencoba masuk ke area terlarang!<br>
            Penjaga keamanan kami sudah siaga penuh! 👮‍♂️
        </p>
        
        <button class="back-button" onclick="goBack()">
            🏠 Kembali ke Tempat Aman
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
            const icons = ['🔒', '🛡️', '⚠️', '🚫', '👮‍♂️', '🔐', '⛔'];
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

        // Efek suara click (opsional)
        function playClickSound() {
            // Membuat efek suara sederhana dengan Web Audio API
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        }

        // Event listener untuk tombol
        document.querySelector('.back-button').addEventListener('click', playClickSound);
        
        // Mulai animasi ikon mengambang
        createFloatingIcons();

        // Easter egg: klik pada penjaga untuk animasi khusus
        document.querySelector('.guard').addEventListener('click', function() {
            this.style.animation = 'guardBounce 0.5s ease-in-out';
            setTimeout(() => {
                this.style.animation = 'guardBounce 3s infinite ease-in-out';
            }, 500);
            playClickSound();
        });
    </script>
</body>
</html>