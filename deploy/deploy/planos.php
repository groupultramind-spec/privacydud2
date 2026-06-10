<?php
header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

$siteDataFile = __DIR__ . '/site_data.json';
$siteData = file_exists($siteDataFile) ? json_decode(file_get_contents($siteDataFile), true) : [];

$precos = $siteData['precos'] ?? [];
$mensalPreco = isset($precos['mensal']) ? floatval($precos['mensal']) : 32.99;
$semanalPreco = isset($precos['semanal']) ? floatval($precos['semanal']) : 19.90;
$trimestralPreco = isset($precos['trimestral']) ? floatval($precos['trimestral']) : 121.00;
$anualPreco = isset($precos['anual']) ? floatval($precos['anual']) : 299.00;
$vitalicioPreco = isset($precos['vitalicio']) ? floatval($precos['vitalicio']) : 525.50;
$pacoteVideoPreco = isset($precos['pacote_video']) ? floatval($precos['pacote_video']) : 14.90;
$descontoPreco = isset($precos['desconto']) ? floatval($precos['desconto']) : 9.90;
?>
// ============================================================
//  CONFIGURAÇÃO DOS PLANOS — carregada dinamicamente
// ============================================================
var _P = {
  mensal:     { nome: "30 DIAS",   preco: <?= $mensalPreco ?>,  bonus: true  },
  semanal:    { nome: "7 Dias",    preco: <?= $semanalPreco ?>,  bonus: false },
  trimestral: { nome: "3 Meses",   preco: <?= $trimestralPreco ?>, bonus: false },
  anual:      { nome: "1 ANO",     preco: <?= $anualPreco ?>, bonus: false },
  vitalicio:  { nome: "VITALICIO", preco: <?= $vitalicioPreco ?>, bonus: false },
  pacote_video: { nome: "Liberação de Vídeo", preco: <?= $pacoteVideoPreco ?>, bonus: false },

  // ── Notificações de bots ─────────────────────────────────────
  bots: {
    ativo: true,        // true = ativa as notificações | false = desativa
    intervalo_min: 3,   // segundos mínimos entre notificações
    intervalo_max: 8,  // segundos máximos entre notificações
    duracao: 6          // segundos que a notificação fica visível
  },

  // ── Página de desconto (desconto/index.html) ──────────────
  desconto: {
    preco:      <?= $descontoPreco ?>,   // preço promocional exibido
    precoAntes: 29.90,  // riscado "De R$ XX,XX"
    label:     "APENAS HOJE"
  }
};