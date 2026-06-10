<?php
session_start();

// 1. Processar verificação do desafio Javascript (Anti-Leads Falsos e Bots de Varredura)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_challenge') {
    header('Content-Type: application/json');
    $token = intval($_POST['token'] ?? 0);
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Calcular hash 32-bit equivalente em PHP
    $expectedHash = 0;
    for ($i = 0; $i < strlen($ua); $i++) {
        $char = ord($ua[$i]);
        $expectedHash = (($expectedHash << 5) - $expectedHash) + $char;
        $expectedHash = $expectedHash & 0xFFFFFFFF;
        if ($expectedHash & 0x80000000) {
            $expectedHash = $expectedHash - 0x100000000;
        }
    }
    
    if ($token === $expectedHash) {
        $_SESSION['is_real_user'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Token incorreto']);
    }
    exit;
}

// Se o parâmetro bot=1 estiver presente, bloqueia imediatamente
if (isset($_GET['bot'])) {
    $_SESSION['is_real_user'] = false;
    include 'safe.html';
    exit;
}

// Verifica se já passou pelo cloaker nesta sessão
if (isset($_SESSION['is_real_user'])) {
    if ($_SESSION['is_real_user']) {
        include 'real.php';
    } else {
        include 'safe.html';
    }
    exit;
}

// 2. Obter IP Real e User-Agent
$ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Função para registrar bot e mostrar página segura
function blockBot($reason) {
    $_SESSION['is_real_user'] = false;
    // Opcional: log do bot para debug
    // file_put_contents('bots.log', date('Y-m-d H:i:s') . " - Bot Blocked: $reason | IP: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
    include 'safe.html';
    exit;
}

// 3. Verificação de User-Agent (Bots conhecidos)
$botKeywords = [
    'bot','spider','crawl','facebookexternalhit','Googlebot','bingbot','PetalBot',
    'Slurp','DuckDuckBot','Baiduspider','YandexBot','Sogou','Exabot','facebot','ia_archiver',
    'WhatsApp','TelegramBot','Twitterbot','Discordbot','headless','selenium','puppeteer','playwright'
];

foreach ($botKeywords as $keyword) {
    if (stripos($userAgent, $keyword) !== false) {
        blockBot("User-Agent matches $keyword");
    }
}

// Bloqueia acessos sem User-Agent (geralmente scripts de varredura)
if (empty($userAgent) || strlen($userAgent) < 10) {
    blockBot("Empty or suspiciously short User-Agent");
}

// 4. Verificação de Hostname (Reverse DNS)
if ($ip !== '127.0.0.1' && $ip !== '::1') {
    $hostname = @gethostbyaddr($ip);
    if ($hostname !== $ip) {
        $hostBots = ['google','facebook','amazon','aws','microsoft','digitalocean','ovh','hetzner','choopa','linode'];
        foreach ($hostBots as $hb) {
            if (stripos($hostname, $hb) !== false) {
                blockBot("Hostname matches datacenter: $hostname");
            }
        }
    }

    // 5. Verificação profunda com API (ISP/Datacenter)
    $ch = curl_init("http://ip-api.com/json/{$ip}?fields=status,countryCode,isp,hosting");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $geo = json_decode($response, true);
        if (isset($geo['status']) && $geo['status'] === 'success') {
            if (isset($geo['hosting']) && $geo['hosting'] === true) {
                blockBot("ISP is marked as hosting/datacenter");
            }
            
            $ispBots = ['Google', 'Facebook', 'Meta', 'Amazon', 'Microsoft', 'Datacenter', 'Hosting', 'Cloud', 'OVH', 'DigitalOcean'];
            if (isset($geo['isp'])) {
                foreach ($ispBots as $ib) {
                    if (stripos($geo['isp'], $ib) !== false) {
                        blockBot("ISP name matched bot list: {$geo['isp']}");
                    }
                }
            }
        }
    }
}

// Se passou pelos filtros iniciais do PHP, apresenta a tela de verificação Javascript
// Isso garante que apenas navegadores reais rodando Javascript e passando no desafio entrem no site
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aguarde um momento...</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0d0d0d;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .challenge-container {
            text-align: center;
            padding: 2.5rem;
            max-width: 420px;
            background: #141414;
            border-radius: 16px;
            border: 1px solid #222;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .logo {
            font-size: 2.2rem;
            font-weight: 700;
            color: #f97316;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: -1px;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.05);
            border-top: 3px solid #f97316;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 2rem auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        p {
            color: #a0a0a0;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="challenge-container">
        <div class="logo">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            privacy
        </div>
        <h2>Verificando conexão segura...</h2>
        <p>Aguarde alguns segundos enquanto validamos sua sessão.</p>
        <div class="spinner"></div>
    </div>
    
    <script>
        (function() {
            // Verificar parâmetros típicos de simuladores e automações (headless)
            const isBot = navigator.webdriver || 
                          (window.outerWidth === 0 && window.outerHeight === 0) ||
                          (!navigator.languages || navigator.languages.length === 0);
            
            if (isBot) {
                window.location.href = '?bot=1';
                return;
            }

            // Algoritmo matemático de Hash 32-bit baseado no User-Agent
            let hash = 0;
            const ua = navigator.userAgent;
            for (let i = 0; i < ua.length; i++) {
                const char = ua.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash; // Limitar a inteiro 32-bit
            }
            
            const payload = new FormData();
            payload.append('action', 'verify_challenge');
            payload.append('token', hash);

            // Pequeno delay para simulação real de verificação
            setTimeout(() => {
                fetch(window.location.href, {
                    method: 'POST',
                    body: payload
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        window.location.href = '?bot=1';
                    }
                })
                .catch(() => {
                    window.location.href = '?bot=1';
                });
            }, 1000);
        })();
    </script>
</body>
</html>
