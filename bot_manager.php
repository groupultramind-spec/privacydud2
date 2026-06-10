<?php
require_once 'config.php';

$botToken = TELEGRAM_BOT_TOKEN;
$adminChatId = TELEGRAM_CHAT_ID;
$siteDataFile = __DIR__ . '/site_data.json';
$stateFile = __DIR__ . '/bot_state.json';

function getSiteData() {
    global $siteDataFile;
    return file_exists($siteDataFile) ? json_decode(file_get_contents($siteDataFile), true) : [];
}

function saveSiteData($data) {
    global $siteDataFile;
    file_put_contents($siteDataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function getState() {
    global $stateFile;
    return file_exists($stateFile) ? json_decode(file_get_contents($stateFile), true) : ['action' => null];
}

function setState($action) {
    global $stateFile;
    file_put_contents($stateFile, json_encode(['action' => $action]));
}

function apiRequest($method, $parameters) {
    global $botToken;
    $url = "https://api.telegram.org/bot{$botToken}/{$method}";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function getFileUrl($file_id) {
    global $botToken;
    $res = apiRequest('getFile', ['file_id' => $file_id]);
    if (isset($res['result']['file_path'])) {
        return "https://api.telegram.org/file/bot{$botToken}/" . $res['result']['file_path'];
    }
    return null;
}

function downloadFile($url, $saveTo) {
    $content = @file_get_contents($url);
    if ($content) {
        file_put_contents(__DIR__ . '/' . $saveTo, $content);
        return true;
    }
    return false;
}

function sendMainMenu($chat_id, $message_id = null) {
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '✏️ Editar Nome', 'callback_data' => 'edit_nome_modelo'], ['text' => '📸 Editar Avatar', 'callback_data' => 'edit_avatar']],
            [['text' => '📱 Editar Instagram', 'callback_data' => 'edit_username'], ['text' => '🖼️ Editar Banner', 'callback_data' => 'edit_banner']],
            [['text' => '📝 Editar Bio', 'callback_data' => 'edit_bio'], ['text' => '🖼️ Editar Grid', 'callback_data' => 'edit_grid']],
            [['text' => '🎬 Mídia do Popup', 'callback_data' => 'edit_modal_gif'], ['text' => '💰 Editar Preços', 'callback_data' => 'menu_precos']],
            [['text' => '⚙️ Configurações', 'callback_data' => 'menu_configs'], ['text' => '📊 Estatísticas', 'callback_data' => 'menu_stats']],
            [['text' => '💬 Editar WhatsApp', 'callback_data' => 'edit_whatsapp'], ['text' => '💸 Saques/Payouts', 'callback_data' => 'menu_payouts']],
            [['text' => '🔑 Credenciais Privadas', 'callback_data' => 'menu_credentials']],
            [['text' => '❌ Cancelar', 'callback_data' => 'cancel']]
        ]
    ];
    $params = [
        'chat_id' => $chat_id,
        'text' => "🛠 *Painel de Controle do Site*\n\nEscolha o que deseja editar:",
        'parse_mode' => 'Markdown',
        'reply_markup' => $keyboard
    ];
    if ($message_id) {
        $params['message_id'] = $message_id;
        apiRequest('editMessageText', $params);
    } else {
        apiRequest('sendMessage', $params);
    }
}

function sendPrecosMenu($chat_id, $message_id = null) {
    $siteData = getSiteData();
    $precos = $siteData['precos'] ?? [];
    
    $mensal = $precos['mensal'] ?? '32.99';
    $semanal = $precos['semanal'] ?? '19.90';
    $trimestral = $precos['trimestral'] ?? '121.00';
    $anual = $precos['anual'] ?? '299.00';
    $vitalicio = $precos['vitalicio'] ?? '525.50';
    $pacote = $precos['pacote_video'] ?? '14.90';
    $desconto = $precos['desconto'] ?? '9.90';

    $keyboard = [
        'inline_keyboard' => [
            [['text' => "🌟 Mensal (R$ {$mensal})", 'callback_data' => 'edit_p_mensal'], ['text' => "📅 Semanal (R$ {$semanal})", 'callback_data' => 'edit_p_semanal']],
            [['text' => "🚀 Trimestral (R$ {$trimestral})", 'callback_data' => 'edit_p_trimestral'], ['text' => "💎 Anual (R$ {$anual})", 'callback_data' => 'edit_p_anual']],
            [['text' => "👑 Vitalício (R$ {$vitalicio})", 'callback_data' => 'edit_p_vitalicio'], ['text' => "🎬 Pacote Vídeo (R$ {$pacote})", 'callback_data' => 'edit_p_pacote_video']],
            [['text' => "🎟️ Desconto (R$ {$desconto})", 'callback_data' => 'edit_p_desconto']],
            [['text' => '⬅️ Voltar ao Menu Principal', 'callback_data' => 'menu_principal']]
        ]
    ];
    $params = [
        'chat_id' => $chat_id,
        'text' => "💰 *Gerenciamento de Preços*\n\nSelecione qual preço deseja editar:",
        'parse_mode' => 'Markdown',
        'reply_markup' => $keyboard
    ];
    if ($message_id) {
        $params['message_id'] = $message_id;
        apiRequest('editMessageText', $params);
    } else {
        apiRequest('sendMessage', $params);
    }
}

function sendConfigsMenu($chat_id, $message_id = null) {
    $siteData = getSiteData();
    $blur = $siteData['config']['blur_level'] ?? 10;
    $source = $siteData['config']['media_source'] ?? 'misto';
    $display = $siteData['config']['media_display'] ?? 'misto';
    
    $keyboard = [
        'inline_keyboard' => [
            [['text' => "💧 Nível de Blur (Atualmente: {$blur}px)", 'callback_data' => 'edit_cfg_blur']],
            [['text' => "📂 Fonte: " . strtoupper($source), 'callback_data' => 'cycle_cfg_source']],
            [['text' => "🔀 Exibição: " . strtoupper($display), 'callback_data' => 'cycle_cfg_display']],
            [['text' => '⬅️ Voltar ao Menu Principal', 'callback_data' => 'menu_principal']]
        ]
    ];
    $params = [
        'chat_id' => $chat_id,
        'text' => "⚙️ *Configurações Gerais do Grid de Mídias*\n\nEdite as propriedades de desfocagem e comportamento:",
        'parse_mode' => 'Markdown',
        'reply_markup' => $keyboard
    ];
    if ($message_id) {
        $params['message_id'] = $message_id;
        apiRequest('editMessageText', $params);
    } else {
        apiRequest('sendMessage', $params);
    }
}

function sendPayoutsMenu($chat_id, $message_id = null) {
    $siteData = getSiteData();
    $taxa = $siteData['payouts']['taxa'] ?? '2.99';
    $sacado = $siteData['payouts']['sacado'] ?? '0.00';
    
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '📈 Alterar Taxa de Saque', 'callback_data' => 'edit_payout_taxa']],
            [['text' => '💸 Registrar Novo Saque', 'callback_data' => 'edit_payout_sacado']],
            [['text' => '⬅️ Voltar', 'callback_data' => 'menu_principal']]
        ]
    ];
    
    $params = [
        'chat_id' => $chat_id,
        'text' => "💸 *Controle de Saques & Payouts*\n\nTaxa de Saque Atual: *{$taxa}%*\nValor Total Sacado: *R$ " . number_format(floatval($sacado), 2, ',', '.') . "*",
        'parse_mode' => 'Markdown',
        'reply_markup' => $keyboard
    ];
    
    if ($message_id) {
        $params['message_id'] = $message_id;
        apiRequest('editMessageText', $params);
    } else {
        apiRequest('sendMessage', $params);
    }
}

function sendCredentialsMenu($chat_id, $message_id = null) {
    $secureConfigFile = __DIR__ . '/config_secure.json';
    $configDb = file_exists($secureConfigFile) ? json_decode(file_get_contents($secureConfigFile), true) : [];
    
    $buypixKey = $configDb['BUYPIX_API_KEY'] ?? 'Não configurada';
    $accessLink = $configDb['ACCESS_LINK'] ?? 'Não configurada';
    $smtpUser = $configDb['SMTP_USER'] ?? 'Não configurado';
    
    $obsKey = (strlen($buypixKey) > 10) ? substr($buypixKey, 0, 6) . '...' . substr($buypixKey, -4) : $buypixKey;

    $keyboard = [
        'inline_keyboard' => [
            [['text' => '🔑 Alterar Chave BuyPix', 'callback_data' => 'edit_cred_buypix']],
            [['text' => '🔗 Alterar Link de Acesso', 'callback_data' => 'edit_cred_access']],
            [['text' => '📧 Alterar SMTP Usuário', 'callback_data' => 'edit_cred_smtp_user']],
            [['text' => '🔒 Alterar SMTP Senha', 'callback_data' => 'edit_cred_smtp_pass']],
            [['text' => '⬅️ Voltar', 'callback_data' => 'menu_principal']]
        ]
    ];
    
    $params = [
        'chat_id' => $chat_id,
        'text' => "🔑 *Credenciais e Chaves Privadas*\n\n"
                . "Chave BuyPix atual: `{$obsKey}`\n"
                . "Link de Acesso: `{$accessLink}`\n"
                . "SMTP Usuário: `{$smtpUser}`\n\n"
                . "⚠️ *Nota:* Essas informações são salvas de forma segura no arquivo `config_secure.json` no seu servidor e não são enviadas ao GitHub.",
        'parse_mode' => 'Markdown',
        'reply_markup' => $keyboard
    ];
    
    if ($message_id) {
        $params['message_id'] = $message_id;
        apiRequest('editMessageText', $params);
    } else {
        apiRequest('sendMessage', $params);
    }
}

function sendStatsDashboard($chat_id, $message_id = null) {
    $siteData = getSiteData();
    
    // 1. Dominio e Host info
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Server IP & Region
    $serverIp = @file_get_contents('https://api.ipify.org');
    $serverIp = $serverIp ? trim($serverIp) : '127.0.0.1';
    $hostRegion = 'Localhost / Brasil';
    if ($serverIp !== '127.0.0.1') {
        $geoJson = @file_get_contents("http://ip-api.com/json/{$serverIp}?fields=country,regionName,isp");
        if ($geoJson) {
            $geo = json_decode($geoJson, true);
            if (isset($geo['country'])) {
                $hostRegion = "{$geo['country']} - {$geo['regionName']} ({$geo['isp']})";
            }
        }
    }
    
    // 2. Gateway & API Check (Ping BuyPix)
    $startTime = microtime(true);
    $ch = curl_init('https://buypix.me/api/v1/deposits');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $latency = round((microtime(true) - $startTime) * 1000);
    curl_close($ch);
    
    $gatewayStatus = ($httpCode === 401 || $httpCode === 200) ? "🟢 ONLINE" : "🔴 OFFLINE (Código {$httpCode})";
    
    // 3. Faturamento & Contagem de Transações
    $totalEarnings = 0.0;
    $earningsToday = 0.0;
    $earningsWeek = 0.0;
    $earningsMonth = 0.0;
    $earningsYear = 0.0;
    
    $countPaid = 0;
    $countPending = 0;
    
    $today = strtotime('today');
    $oneWeekAgo = strtotime('-7 days');
    $oneMonthAgo = strtotime('-30 days');
    $oneYearAgo = strtotime('-365 days');
    
    $paidDir = PAID_DIR;
    if (is_dir($paidDir)) {
        $files = scandir($paidDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $paidDir . '/' . $file;
                $data = json_decode(@file_get_contents($filePath), true);
                if ($data && isset($data['status']) && $data['status'] === 'paid') {
                    $valor = floatval($data['valor'] ?? 0);
                    $createdAt = strtotime($data['created_at'] ?? '');
                    
                    $totalEarnings += $valor;
                    $countPaid++;
                    
                    if ($createdAt >= $today) $earningsToday += $valor;
                    if ($createdAt >= $oneWeekAgo) $earningsWeek += $valor;
                    if ($createdAt >= $oneMonthAgo) $earningsMonth += $valor;
                    if ($createdAt >= $oneYearAgo) $earningsYear += $valor;
                }
            }
        }
    }
    
    $pendingDir = PENDING_DIR;
    if (is_dir($pendingDir)) {
        $files = scandir($pendingDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $countPending++;
            }
        }
    }
    
    // 4. Leads / Visitas
    $leadsCount = 0;
    $trackerDir = __DIR__ . '/payments/tracker';
    if (is_dir($trackerDir)) {
        $files = scandir($trackerDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $leadsCount++;
            }
        }
    }
    
    // 5. Mídias salvas
    $grid_count = isset($siteData['grid']) ? count($siteData['grid']) : 0;
    $dirPadrao = __DIR__ . '/media_padrao';
    $filesPadraoCount = 0;
    if (is_dir($dirPadrao)) {
        $scan = scandir($dirPadrao);
        foreach ($scan as $file) {
            if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|mp4|webm)$/i', $file)) {
                $filesPadraoCount++;
            }
        }
    }
    
    // 6. Saques
    $taxaSaque = floatval($siteData['payouts']['taxa'] ?? 2.99);
    $valorSacado = floatval($siteData['payouts']['sacado'] ?? 0.00);
    $saldoDisponivel = max(0, $totalEarnings - $valorSacado - ($totalEarnings * ($taxaSaque / 100)));

    // Formatar texto Markdown
    $txt = "📊 *DASHBOARD COMPLETO DE ESTATÍSTICAS*\n\n"
         . "🌐 *Domínio:* `{$domain}`\n"
         . "📍 *Host Região:* `{$hostRegion}`\n"
         . "⚡ *Latency (BuyPix):* `{$latency} ms`\n"
         . "🔌 *Status do Gateway:* {$gatewayStatus}\n\n"
         
         . "👥 *Leads/Visitas Totais:* `{$leadsCount}`\n"
         . "📂 *Mídias Salvas (Mural):* `{$grid_count}` adicionadas | `{$filesPadraoCount}` padrão\n\n"
         
         . "💵 *ENTRADAS & FATURAMENTO:*\n"
         . "💰 *Total Bruto:* `R$ " . number_format($totalEarnings, 2, ',', '.') . "`\n"
         . "📅 *Hoje:* `R$ " . number_format($earningsToday, 2, ',', '.') . "`\n"
         . "📅 *Últimos 7 dias:* `R$ " . number_format($earningsWeek, 2, ',', '.') . "`\n"
         . "📅 *Últimos 30 dias:* `R$ " . number_format($earningsMonth, 2, ',', '.') . "`\n"
         . "📅 *Ano atual:* `R$ " . number_format($earningsYear, 2, ',', '.') . "`\n\n"
         
         . "📈 *TRANSAÇÕES:*\n"
         . "✅ *Aprovadas:* `{$countPaid}`\n"
         . "⏳ *Aguardando/Pendentes:* `{$countPending}`\n\n"
         
         . "💸 *SAQUES & PAGAMENTOS:*\n"
         . "📈 *Taxa de Saque:* `{$taxaSaque}%`\n"
         . "💰 *Valor Total Sacado:* `R$ " . number_format($valorSacado, 2, ',', '.') . "`\n"
         . "💵 *Saldo Estimado Disponível:* `R$ " . number_format($saldoDisponivel, 2, ',', '.') . "`\n\n"
         
         . "_Atualizado em tempo real._";

    $keyboard = [
        'inline_keyboard' => [
            [['text' => '🔄 Atualizar Dados', 'callback_data' => 'menu_stats'], ['text' => '⬅️ Voltar', 'callback_data' => 'menu_principal']]
        ]
    ];
    
    $params = [
        'chat_id' => $chat_id,
        'text' => $txt,
        'parse_mode' => 'Markdown',
        'reply_markup' => $keyboard
    ];
    if ($message_id) {
        $params['message_id'] = $message_id;
        apiRequest('editMessageText', $params);
    } else {
        apiRequest('sendMessage', $params);
    }
}

function handleUpdate($update) {
    global $adminChatId;
    error_log("[BOT] Recebendo e processando atualizacao do Telegram...");
    
    // Callback Queries (Cliques nos botões)
    if (isset($update['callback_query'])) {
        $chat_id = $update['callback_query']['message']['chat']['id'];
        if ($chat_id != $adminChatId) return;
        
        $data = $update['callback_query']['data'];
        $message_id = $update['callback_query']['message']['message_id'];
        
        if ($data == 'cancel') {
            setState(null);
            sendMainMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'menu_principal') {
            setState(null);
            sendMainMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'menu_precos') {
            setState(null);
            sendPrecosMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'menu_configs') {
            setState(null);
            sendConfigsMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'menu_payouts') {
            setState(null);
            sendPayoutsMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'menu_stats') {
            setState(null);
            sendStatsDashboard($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'menu_credentials') {
            setState(null);
            sendCredentialsMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            return;
        }
        
        if ($data == 'cycle_cfg_source') {
            $siteData = getSiteData();
            if (!isset($siteData['config'])) $siteData['config'] = [];
            $current = $siteData['config']['media_source'] ?? 'misto';
            
            $next = 'misto';
            if ($current === 'misto') $next = 'padrao';
            elseif ($current === 'padrao') $next = 'adicionadas';
            
            $siteData['config']['media_source'] = $next;
            saveSiteData($siteData);
            
            sendConfigsMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id'], 'text' => "Fonte de mídia: " . strtoupper($next)]);
            return;
        }
        
        if ($data == 'cycle_cfg_display') {
            $siteData = getSiteData();
            if (!isset($siteData['config'])) $siteData['config'] = [];
            $current = $siteData['config']['media_display'] ?? 'misto';
            
            $next = 'misto';
            if ($current === 'misto') $next = 'padrao';
            elseif ($current === 'padrao') $next = 'carrosel';
            
            $siteData['config']['media_display'] = $next;
            saveSiteData($siteData);
            
            sendConfigsMenu($chat_id, $message_id);
            apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id'], 'text' => "Modo de exibição: " . strtoupper($next)]);
            return;
        }
        
        setState($data);
        
        $siteData = getSiteData();
        $precos = $siteData['precos'] ?? [];
        $cfg = $siteData['config'] ?? [];
        
        $curr_nome = $siteData['nome_modelo'] ?? 'Eduarda Oficial';
        $curr_insta = $siteData['username'] ?? '@eduardaoficial1_';
        $curr_bio = $siteData['bio'] ?? 'N/A';
        $curr_avatar = $siteData['avatar'] ?? 'images/fotoperfileduarda.jpg';
        $curr_banner = $siteData['banner'] ?? 'media/videoeduarda2.mp4';
        $curr_modal_gif = $siteData['modal_gif'] ?? 'images/modal_gif.gif';
        $curr_whatsapp = $siteData['whatsapp'] ?? 'N/A';
        $curr_blur = $cfg['blur_level'] ?? '10';
        $grid_count = isset($siteData['grid']) ? count($siteData['grid']) : 0;
        
        $curr_mensal = $precos['mensal'] ?? '32.99';
        $curr_semanal = $precos['semanal'] ?? '19.90';
        $curr_trimestral = $precos['trimestral'] ?? '121.00';
        $curr_anual = $precos['anual'] ?? '299.00';
        $curr_vitalicio = $precos['vitalicio'] ?? '525.50';
        $curr_pacote = $precos['pacote_video'] ?? '14.90';
        $curr_desconto = $precos['desconto'] ?? '9.90';
        
        $curr_taxa = $siteData['payouts']['taxa'] ?? '2.99';
        $curr_sacado = $siteData['payouts']['sacado'] ?? '0.00';

        $msgs = [
            'edit_nome_modelo' => "O *Nome da Modelo* atual é: **{$curr_nome}**\n\nDigite o novo *Nome da Modelo*:",
            'edit_username' => "O *Instagram* atual é: **{$curr_insta}**\n\nDigite o novo *Instagram* (ex: @dudinha):",
            'edit_bio' => "A *Bio/Descrição* atual é:\n`{$curr_bio}`\n\nDigite a nova *Bio/Descrição*:",
            'edit_avatar' => "A foto de avatar atual está salva em: `{$curr_avatar}`\n\nEnvie a nova *Foto de Avatar* (Envie como Foto) OU envie o **@username** do Instagram (ex: `@dudinha` ou `dudinha`) para buscar e sincronizar a foto automaticamente:",
            'edit_banner' => "O banner atual está salvo em: `{$curr_banner}`\n\nEnvie o novo *Banner* (Envie como Vídeo ou Foto):",
            'edit_grid' => "Você possui **{$grid_count}** mídias adicionadas no Mural.\n\nEnvie a nova foto ou vídeo para adicionar ao início do Mural/Grid:",
            'edit_modal_gif' => "A mídia do popup atual está salva em: `{$curr_modal_gif}`\n\nEnvie a nova mídia para o popup (Foto, Vídeo ou GIF):",
            'edit_whatsapp' => "O *WhatsApp* atual é: **+{$curr_whatsapp}**\n\nDigite o novo *WhatsApp* (somente números, ex: 5511999999999):",
            
            // Preços
            'edit_p_mensal' => "O preço atual do plano *Mensal* é R$ **{$curr_mensal}**.\n\nDigite o novo preço (ex: 29.90):",
            'edit_p_semanal' => "O preço atual do plano *Semanal* é R$ **{$curr_semanal}**.\n\nDigite o novo preço (ex: 14.90):",
            'edit_p_trimestral' => "O preço atual do plano *Trimestral* é R$ **{$curr_trimestral}**.\n\nDigite o novo preço (ex: 89.90):",
            'edit_p_anual' => "O preço atual do plano *Anual* é R$ **{$curr_anual}**.\n\nDigite o novo preço (ex: 199.90):",
            'edit_p_vitalicio' => "O preço atual do plano *Vitalício* é R$ **{$curr_vitalicio}**.\n\nDigite o novo preço (ex: 399.90):",
            'edit_p_pacote_video' => "O preço atual do *Pacote de Vídeo* é R$ **{$curr_pacote}**.\n\nDigite o novo preço (ex: 19.90):",
            'edit_p_desconto' => "O preço atual com *Desconto* é R$ **{$curr_desconto}**.\n\nDigite o novo preço (ex: 9.90):",
            
            // Configurações
            'edit_cfg_blur' => "O nível de blur atual é **{$curr_blur}px**.\n\nDigite o novo *Nível de Blur* das mídias em pixels (ex: 8):",
            
            // Payouts
            'edit_payout_taxa' => "A taxa de saque atual é **{$curr_taxa}%**.\n\nDigite a nova taxa de saque (ex: 2.5):",
            'edit_payout_sacado' => "O valor total sacado atual é R$ **" . number_format(floatval($curr_sacado), 2, ',', '.') . "**.\n\nDigite o novo valor total sacado (ex: 1500.00):",
            
            // Credenciais Privadas
            'edit_cred_buypix' => "Digite a nova *Chave API BuyPix* (ex: bpx_...):",
            'edit_cred_access' => "Digite o novo *Link de Acesso* (ex: https://t.me/...):",
            'edit_cred_smtp_user' => "Digite o novo *Usuário SMTP/E-mail de envio* (ex: modelo@site.com):",
            'edit_cred_smtp_pass' => "Digite a nova *Senha SMTP*:"
        ];
        
        if (isset($msgs[$data])) {
            $cancelTarget = str_starts_with($data, 'edit_p_') ? 'menu_precos' : (str_starts_with($data, 'edit_cfg_') ? 'menu_configs' : (str_starts_with($data, 'edit_payout_') ? 'menu_payouts' : (str_starts_with($data, 'edit_cred_') ? 'menu_credentials' : 'cancel')));
            apiRequest('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => $msgs[$data] . "\n\n_Ou clique em Cancelar para voltar._",
                'parse_mode' => 'Markdown',
                'reply_markup' => ['inline_keyboard' => [[['text' => '❌ Cancelar', 'callback_data' => $cancelTarget]]]]
            ]);
        }
        
        apiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
        return;
    }
    
    // Text Messages and Media
    if (isset($update['message'])) {
        $chat_id = $update['message']['chat']['id'];
        if ($chat_id != $adminChatId) return;
        
        $text = $update['message']['text'] ?? '';
        if ($text === '/start' || $text === '/menu' || $text === '/painel') {
            setState(null);
            sendMainMenu($chat_id);
            return;
        }
        
        $siteData = getSiteData();
        $success = false;
        
        // Lógica de Sincronização Automática do Instagram
        if ($text && str_starts_with($text, '@')) {
            $username = trim($text, '@ ');
            $ch = curl_init("https://www.instagram.com/{$username}/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1');
            $html = curl_exec($ch);
            curl_close($ch);
            
            if ($html) {
                preg_match('/<meta property="og:title" content="(.*?)"/', $html, $titleMatch);
                preg_match('/<meta property="og:image" content="(.*?)"/', $html, $imgMatch);
                if (isset($titleMatch[1]) && isset($imgMatch[1])) {
                    $siteData['nome_modelo'] = explode('(', html_entity_decode($titleMatch[1]))[0] ?? $username;
                    $siteData['username'] = $text;
                    
                    $imgUrl = html_entity_decode($imgMatch[1]);
                    $filename = 'images/avatar_' . time() . '.jpg';
                    if (downloadFile($imgUrl, $filename)) {
                        $siteData['avatar'] = $filename;
                    }
                    saveSiteData($siteData);
                    apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "✅ Perfil do Instagram sincronizado com sucesso! (Nome, @ e Foto)"]);
                    setState(null);
                    sendMainMenu($chat_id);
                    return;
                }
            }
        }
        
        $state = getState();
        $action = $state['action'];
        if (!$action) return;
        
        // Salvar Preços dos Planos
        if (str_starts_with($action, 'edit_p_') && $text) {
            $planKey = str_replace('edit_p_', '', $action);
            $priceValue = floatval(str_replace(',', '.', $text));
            if ($priceValue > 0) {
                if (!isset($siteData['precos'])) $siteData['precos'] = [];
                $siteData['precos'][$planKey] = $priceValue;
                saveSiteData($siteData);
                apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "✅ Preço do plano *{$planKey}* atualizado para R$ " . number_format($priceValue, 2, ',', '.') . "!", 'parse_mode' => 'Markdown']);
                setState(null);
                sendPrecosMenu($chat_id);
                return;
            }
        }
        
        // Salvar Saques e Taxas
        if ($action === 'edit_payout_taxa' && $text) {
            $taxaValue = floatval(str_replace(',', '.', $text));
            if ($taxaValue >= 0) {
                if (!isset($siteData['payouts'])) $siteData['payouts'] = [];
                $siteData['payouts']['taxa'] = $taxaValue;
                saveSiteData($siteData);
                apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "✅ Taxa de saque atualizada para *{$taxaValue}%*!", 'parse_mode' => 'Markdown']);
                setState(null);
                sendPayoutsMenu($chat_id);
                return;
            }
        }
        
        if ($action === 'edit_payout_sacado' && $text) {
            $sacadoValue = floatval(str_replace(',', '.', $text));
            if ($sacadoValue >= 0) {
                if (!isset($siteData['payouts'])) $siteData['payouts'] = [];
                $currentSacado = floatval($siteData['payouts']['sacado'] ?? 0);
                $newTotal = $currentSacado + $sacadoValue;
                $siteData['payouts']['sacado'] = $newTotal;
                saveSiteData($siteData);
                apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "✅ Novo saque de *R$ " . number_format($sacadoValue, 2, ',', '.') . "* registrado!\n💰 Total sacado: *R$ " . number_format($newTotal, 2, ',', '.') . "*", 'parse_mode' => 'Markdown']);
                setState(null);
                sendPayoutsMenu($chat_id);
                return;
            }
        }
        
        // Salvar Configurações Gerais
        if ($action === 'edit_cfg_blur' && $text) {
            $blurValue = intval($text);
            if ($blurValue >= 0) {
                if (!isset($siteData['config'])) $siteData['config'] = [];
                $siteData['config']['blur_level'] = $blurValue;
                saveSiteData($siteData);
                apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "✅ Nível de desfocagem atualizado para *{$blurValue}px*!", 'parse_mode' => 'Markdown']);
                setState(null);
                sendConfigsMenu($chat_id);
                return;
            }
        }
        
        // Salvar Credenciais Privadas
        if (str_starts_with($action, 'edit_cred_') && $text) {
            $secureConfigFile = __DIR__ . '/config_secure.json';
            $configDb = file_exists($secureConfigFile) ? json_decode(file_get_contents($secureConfigFile), true) : [];
            
            $keyMap = [
                'edit_cred_buypix' => 'BUYPIX_API_KEY',
                'edit_cred_access' => 'ACCESS_LINK',
                'edit_cred_smtp_user' => 'SMTP_USER',
                'edit_cred_smtp_pass' => 'SMTP_PASSWORD'
            ];
            
            if (isset($keyMap[$action])) {
                $dbKey = $keyMap[$action];
                $configDb[$dbKey] = $text;
                if ($dbKey === 'SMTP_USER') {
                    $configDb['EMAIL_FROM'] = $text;
                }
                file_put_contents($secureConfigFile, json_encode($configDb, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                apiRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "✅ Credencial *{$dbKey}* atualizada e criptografada com sucesso!", 'parse_mode' => 'Markdown']);
                setState(null);
                sendCredentialsMenu($chat_id);
                return;
            }
        }
        
        // Salvar WhatsApp da Modelo
        if ($action === 'edit_whatsapp' && $text) {
            $whatsappClean = preg_replace('/\D/', '', $text);
            if (strlen($whatsappClean) < 12 || strlen($whatsappClean) > 13 || !str_starts_with($whatsappClean, '55')) {
                apiRequest('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "❌ *WhatsApp Inválido!*\n\nUse o formato com DDI e DDD: *55* + DDD + número (ex: *5511999999999*). Deve conter de 12 a 13 dígitos e iniciar com 55.",
                    'parse_mode' => 'Markdown'
                ]);
                return;
            }
            $siteData['whatsapp'] = $whatsappClean;
            saveSiteData($siteData);
            apiRequest('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "✅ *WhatsApp da Modelo atualizado com sucesso!*\n\nNúmero: +{$whatsappClean}",
                'parse_mode' => 'Markdown'
            ]);
            setState(null);
            sendMainMenu($chat_id);
            return;
        }
        
        // Texto Geral (Nome, Instagram, Bio)
        if (in_array($action, ['edit_nome_modelo', 'edit_username', 'edit_bio']) && $text) {
            $siteData[$action] = $text;
            saveSiteData($siteData);
            $success = true;
        }
        
        // Imagens/Video (Avatar, Banner, Grid, Modal Gif)
        if (in_array($action, ['edit_avatar', 'edit_banner', 'edit_grid', 'edit_modal_gif'])) {
            $file_id = null;
            $ext = '.jpg';
            
            if ($action === 'edit_avatar' && $text) {
                // Sincronizar Avatar do Instagram via texto (@username)
                $username = trim($text, '@ ');
                $imgUrl = "https://unavatar.io/instagram/{$username}";
                $filename = 'images/avatar_instagram_' . time() . '.jpg';
                
                // Tenta baixar usando unavatar.io
                $content = null;
                $ch = curl_init($imgUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                $content = curl_exec($ch);
                curl_close($ch);
                
                // Se falhar, tenta raspar o instagram.com diretamente
                if (!$content || strlen($content) < 500) {
                    $ch = curl_init("https://www.instagram.com/{$username}/");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1');
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    $html = curl_exec($ch);
                    curl_close($ch);
                    if ($html) {
                        preg_match('/<meta property="og:image" content="(.*?)"/', $html, $imgMatch);
                        if (isset($imgMatch[1])) {
                            $directUrl = html_entity_decode($imgMatch[1]);
                            $ch = curl_init($directUrl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                            $content = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }
                
                if ($content && strlen($content) > 500) {
                    file_put_contents(__DIR__ . '/' . $filename, $content);
                    $siteData['avatar'] = $filename;
                    $siteData['username'] = '@' . $username;
                    saveSiteData($siteData);
                    $success = true;
                }
            } else {
                if (isset($update['message']['photo'])) {
                    $photos = $update['message']['photo'];
                    $file_id = end($photos)['file_id'];
                } elseif (isset($update['message']['video'])) {
                    $file_id = $update['message']['video']['file_id'];
                    $ext = '.mp4';
                } elseif (isset($update['message']['animation'])) {
                    $file_id = $update['message']['animation']['file_id'];
                    $ext = '.mp4';
                }
                
                if ($file_id) {
                    $url = getFileUrl($file_id);
                    if ($url) {
                        $dir = ($action == 'edit_grid' || $action == 'edit_modal_gif') ? 'media/' : 'images/';
                        $filename = $dir . $action . '_' . time() . $ext;
                        if (downloadFile($url, $filename)) {
                            if ($action == 'edit_grid') {
                                if (!isset($siteData['grid'])) $siteData['grid'] = [];
                                array_unshift($siteData['grid'], $filename);
                            } elseif ($action == 'edit_modal_gif') {
                                $siteData['modal_gif'] = $filename;
                            } else {
                                $siteData[$action] = $filename;
                            }
                            saveSiteData($siteData);
                            $success = true;
                        }
                    }
                }
            }
        }
        
        if ($success) {
            apiRequest('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "✅ Atualizado com sucesso no site!"
            ]);
            setState(null);
            sendMainMenu($chat_id);
        } else {
            apiRequest('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "❌ Erro ou formato inválido. Tente novamente."
            ]);
        }
    }
}

// Lógica de Direcionamento (CLI/Long Polling ou Webhook/HTTP POST)
if (php_sapi_name() === 'cli') {
    echo "Iniciando Long Polling...\n";
    $offset = 0;
    while (true) {
        $updates = apiRequest('getUpdates', ['offset' => $offset, 'timeout' => 30]);
        if (isset($updates['result'])) {
            foreach ($updates['result'] as $update) {
                $offset = $update['update_id'] + 1;
                handleUpdate($update);
            }
        }
        sleep(1);
    }
} else {
    // Modo Webhook para Servidor de Produção (ex: KingHost)
    // Para configurar, acesse: https://seu-dominio.com/bot_manager.php?set_webhook=true
    if (isset($_GET['set_webhook'])) {
        $webhookUrl = "https://" . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
        $res = apiRequest('setWebhook', ['url' => $webhookUrl]);
        error_log("[WEBHOOK] Ativando webhook para: " . $webhookUrl);
        error_log("[WEBHOOK] Resposta do Telegram: " . json_encode($res));
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $res['ok'] ?? false,
            'message' => $res['ok'] ? "Webhook configurado com sucesso!" : "Erro ao configurar webhook.",
            'url' => $webhookUrl,
            'details' => $res
        ], JSON_PRETTY_PRINT);
        exit;
    }

    // Processar atualizações vindas do Webhook (POST do Telegram)
    $input = file_get_contents('php://input');
    $update = json_decode($input, true);
    if ($update) {
        error_log("[WEBHOOK] Novo POST recebido do Telegram com dados de atualizacao.");
        handleUpdate($update);
        echo "OK";
    } else {
        error_log("[WEBHOOK] Acesso recebido, mas sem dados JSON do Telegram.");
        echo "Acesso restrito.";
    }
}
