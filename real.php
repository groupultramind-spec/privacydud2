<?php
ob_start();
$siteDataFile = __DIR__ . '/site_data.json';
$siteData = file_exists($siteDataFile) ? json_decode(file_get_contents($siteDataFile), true) : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy - Eduarda Ofc</title>
    <link rel="icon" type="image/png" href="images/219-images-favicon.png">
    <link rel="apple-touch-icon" sizes="128x128" href="favicons/5022-images-favicon.html">
    <link rel="icon" sizes="192x192" href="favicons/2402-images-favicon.html">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Preload de mídias críticas para carregamento instantâneo -->
    <?php
    $preloadAvatar = $siteData['avatar'] ?? 'images/fotoperfileduarda.jpg';
    $preloadBanner = $siteData['banner'] ?? 'media/videoeduarda2.mp4';
    $defaultGrid = ['images/fotoeduarda1.jpg', 'media/videoeduarda3.mp4', 'media/videoeduarda4.mp4'];
    $gridItems = !empty($siteData['grid']) && is_array($siteData['grid']) ? $siteData['grid'] : $defaultGrid;
    ?>
    <link rel="preload" href="<?= $preloadAvatar ?>" as="image">
    <?php if (preg_match('/\.(mp4|webm|ogg)$/i', $preloadBanner)): ?>
      <link rel="preload" href="<?= $preloadBanner ?>" as="video" type="video/mp4">
    <?php else: ?>
      <link rel="preload" href="<?= $preloadBanner ?>" as="image">
    <?php endif; ?>
    <?php
    foreach (array_slice($gridItems, 0, 4) as $media) {
        if (preg_match('/\.(mp4|webm|ogg)$/i', $media)) {
            echo '<link rel="preload" href="' . $media . '" as="video" type="video/mp4">';
        } else {
            echo '<link rel="preload" href="' . $media . '" as="image">';
        }
    }
    ?>
    <!-- Pixel -->
    <script src="pixel.js"></script>
  <script>
    (function(){
      var _C = <?= json_encode([
        'username' => $siteData['username'] ?? '@dudinhaprivacy',
        'bio' => $siteData['bio'] ?? '',
        'bonus' => $siteData['bonus'] ?? ''
      ]) ?>;
      
      function initConteudo() {
        var u = document.getElementById('username-text');
        var b = document.getElementById('texto');
        var btn = document.getElementById('botao');
        if (u && _C.username) u.textContent = _C.username;
        if (b && _C.bio) {
          b.innerHTML = _C.bio + '<br><br><b>' + _C.bonus + '</b>';
        }
        if (btn && b) {
          btn.addEventListener('click', function() {
            if (b.classList.contains('expandido')) {
              b.classList.remove('expandido');
              btn.textContent = 'Mostrar mais';
            } else {
              b.classList.add('expandido');
              btn.textContent = 'Mostrar menos';
            }
          });
        }
      }
      // Run immediately when DOM is ready
      document.addEventListener('DOMContentLoaded', initConteudo);
      // Fallback if already loaded
      if (document.readyState === 'complete' || document.readyState === 'interactive') {
          initConteudo();
      }
    })();
  </script>
 
 <script>
    const link = 'desconto/index.html';

    function setBackRedirect(url) {
      let urlBackRedirect = url;
      urlBackRedirect = urlBackRedirect =
        urlBackRedirect.trim() +
        (urlBackRedirect.indexOf('?') > 0 ? '&' : '?') +
        document.location.search.replace('?', '').toString();

      history.pushState({}, '', location.href);
      history.pushState({}, '', location.href);
      history.pushState({}, '', location.href);

      window.addEventListener('popstate', () => {
        console.log('onpopstate', urlBackRedirect);
        setTimeout(() => {
          location.href = urlBackRedirect;
        }, 1);
      });
    }

    setBackRedirect(link);
  </script>

	<style>
      :root {
        --orange-50: #fff7ed;
        --orange-100: #ffedd5;
        --orange-200: #fed7aa;
        --orange-300: #fdba74;
        --orange-400: #fb923c;
        --orange-500: #f97316;
        --orange-600: #ea580c;
        --orange-700: #c2410c;
        --orange-800: #9a3412;
        --orange-900: #7c2d12;

        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;

        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1),
          0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
          0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
          0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
          0 10px 10px -5px rgba(0, 0, 0, 0.04);

        --radius: 0.5rem;
        --radius-sm: 0.25rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
      }

      * {
        font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI",
          Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue",
          sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
        -webkit-touch-callout: none;
        outline: none;
      }

      body {
        background-color: var(--gray-50);
        color: var(--gray-800);
        line-height: 1.5;
      }

      .container {
        width: 100%;
        max-width: 768px;
        margin: 0 auto;
        padding: 0 1rem;
      }

      /* Header */
      .header {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: white;
        border-bottom: 1px solid var(--gray-100);
        box-shadow: var(--shadow-sm);
      }

      .header-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 4rem;
      }

      .logo {
        display: flex;
        align-items: center;
      }

      .logo-image {
        height: 32px;
        width: auto;
      }

      /* Main Content */
      main {
        padding: 2rem 1rem;
      }

      /* Profile Section */
      .profile-section {
        position: relative;
        margin-bottom: 4rem;
      }

      /* Banner */
      .banner {
        position: relative;
        width: 100%;
        height: 12rem;
        border-radius: var(--radius-lg);
        overflow: hidden;
      }

      .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
      }

      .banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 0.1),
          rgba(0, 0, 0, 0.4)
        );
      }

      .banner-content {
        position: absolute;
        top: 1.5rem;
        left: 1.5rem;
        right: 1.5rem;
      }

      .banner-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        margin-bottom: 0.5rem;
      }

      .banner-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: white;
      }

      .stat {
        display: flex;
        align-items: center;
        gap: 0.25rem;
      }

      /* Profile Image */
      .profile-image-container {
        position: absolute;
        bottom: -2.5rem;
        left: 1.5rem;
        width: 7rem;
        height: 7rem;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid white;
        box-shadow: var(--shadow);
        z-index: 5;
      }

      .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      /* Cards */
      .card {
        background-color: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 1.5rem;
        overflow: hidden;
      }

      .card-header {
        padding: 1.5rem 1.5rem 0.5rem;
      }
      .card-header h3 {
        font-size: 1.125rem;
        font-weight: 500;
      }

      .card-content {
        padding: 1.5rem;
        padding-top: 0.2rem;
      }

      /* Profile Card */
      .profile-card {
        padding-top: 0.5rem;
        margin-top: 2rem;
      }

      .profile-card span {
        display: block;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
      }

      .username {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin-bottom: 0.5rem;
      }

      .bio {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-top: 0.5rem;
      }

      /* Subscription Buttons */
      .subscription-link {
        display: block;
        text-decoration: none;
        margin-bottom: 0.75rem;
      }

      .subscription-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 3.5rem;
        padding: 0 1.5rem;
        border-radius: var(--radius);
        border: none;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .primary-button {
        background: linear-gradient(
          to right,
          var(--orange-400),
          var(--orange-500)
        );
        color: white;
        border: none;
      }

      .primary-button:hover {
        background: linear-gradient(
          to right,
          var(--orange-500),
          var(--orange-600)
        );
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
      }

      .primary-button:active {
        transform: translateY(0);
      }

      .outline-button {
        background-color: white;
        border: 1px solid var(--orange-200);
        color: var(--gray-800);
      }

      .outline-button:hover {
        background-color: var(--orange-50);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
      }

      .outline-button:active {
        transform: translateY(0);
      }

      .button-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .badge {
        display: inline-block;
        padding: 0.125rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
        background-color: var(--orange-100);
        color: var(--orange-800);
      }

      .price {
        font-weight: 700;
      }

      .highlight {
        color: var(--orange-600);
      }

      /* Pulse Animation */
      @keyframes pulse {
        0% {
          box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7);
        }
        70% {
          box-shadow: 0 0 0 10px rgba(249, 115, 22, 0);
        }
        100% {
          box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
        }
      }

      .pulse {
        animation: pulse 2s infinite;
      }

      /* Promotions */
      .promotions {
        margin-top: 1.5rem;
      }

      .promotions-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        cursor: pointer;
        padding: 0.5rem 0;
        transition: opacity 0.2s;
      }

      .promotions-header:hover {
        opacity: 0.7;
      }

      .promotions-header h4 {
        font-size: 0.875rem;
        font-weight: 500;
      }

      .chevron {
        color: var(--gray-400);
        transition: transform 0.3s ease;
      }

      .promotions .subscription-link {
        display: none;
      }

      .promotions.active .subscription-link {
        display: block;
      }

      .promotions.active .chevron {
        transform: rotate(180deg);
      }

      /* Unlock Video Modal */
      .unlock-modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.85);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease-out;
      }
      .unlock-modal-overlay.active {
        display: flex;
      }
      .unlock-modal-container {
        background: #111;
        border: 1px solid #222;
        border-radius: 16px;
        max-width: 420px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        position: relative;
        display: flex;
        flex-direction: column;
        color: white;
      }
      .unlock-modal-close {
        position: absolute;
        top: 12px; right: 12px;
        background: rgba(0,0,0,0.6);
        border: none; color: white;
        width: 30px; height: 30px;
        border-radius: 50%;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        z-index: 10;
        font-size: 18px;
        font-weight: bold;
        transition: background 0.2s;
      }
      .unlock-modal-close:hover {
        background: rgba(0,0,0,0.8);
      }
      .unlock-media-container {
        position: relative;
        height: 250px;
        width: 100%;
        background: #000;
        overflow: hidden;
      }
      .unlock-media {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: blur(15px);
        transform: scale(1.1);
      }
      .unlock-media-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(17,17,17,1));
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .unlock-lock-icon {
        background: rgba(249,115,22,0.1);
        border: 2px solid #f97316;
        color: #f97316;
        width: 60px; height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(249,115,22,0.4);
        animation: pulse 2s infinite;
      }
      .unlock-modal-content {
        padding: 20px;
        text-align: center;
      }
      .unlock-modal-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #fff;
        margin-top: 0;
      }
      .unlock-modal-content p {
        font-size: 0.875rem;
        color: #aaa;
        margin-bottom: 20px;
        line-height: 1.4;
      }
      .unlock-modal-btn {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        text-transform: uppercase;
      }
      .unlock-modal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249,115,22,0.4);
      }
      .unlock-modal-btn:active {
        transform: translateY(0);
      }

      /* Content Tabs */
      .content-tabs {
        /* margin-bottom: 2rem; */
      }

      .tabs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 1rem;
      }

      .tab {
        padding: 0.75rem;
        text-align: center;
        font-size: 0.875rem;
        background-color: var(--gray-100);
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--gray-600);
      }

      .tab.active {
        background-color: var(--orange-50);
        color: var(--orange-500);
        font-weight: 500;
      }

      .tab:first-child.active {
        box-shadow: inset -1px 0 0 var(--orange-200);
      }

      .tab:last-child.active {
        box-shadow: inset 1px 0 0 var(--orange-200);
      }

      .tab-content {
        display: none;
      }

      .tab-content.active {
        display: block;
      }

      /* Media Subtabs */
      .media-subtabs {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
      }

      .subtab {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        background-color: var(--gray-100);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: background-color 0.2s;
        color: var(--gray-600);
        white-space: nowrap;
      }

      .subtab.active {
        background-color: var(--orange-100);
        color: var(--orange-700);
        font-weight: 500;
      }

      /* Post Card */
      .post-card {
        background-color: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
      }

      .post-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        border-bottom: 1px solid var(--gray-100);
      }

      .post-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
      }

      .post-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        object-fit: cover;
      }

      .post-user h4 {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.125rem;
      }

      .post-username {
        font-size: 0.75rem;
        color: var(--gray-500);
      }

      .post-menu {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        border: none;
        background-color: transparent;
        color: var(--gray-500);
        cursor: pointer;
        transition: background-color 0.2s;
      }

      .post-menu:hover {
        background-color: var(--gray-100);
      }

      /* Post Content */
      .post-content {
        position: relative;
        height: 300px;
        overflow: hidden;
      }

      .post-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        filter: blur(4px);
      }

      /* Video Content */
      .post-video-container {
        position: relative;
        width: 100%;
        height: auto;
        overflow: hidden;
      }

      .post-video {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
        filter: blur(4px);
      }

      .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 2;
      }

      /* Locked Content */
      .locked-content {
        position: relative;
        height: 15rem;
        background-color: var(--orange-50);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
      }

      .logo-background {
        position: absolute;
        width: 80%;
        max-width: 200px;
        height: auto;
        opacity: 0.1;
        z-index: 0;
      }

      .lock-icon {
        position: relative;
        z-index: 1;
        color: var(--gray-400);
        background-color: rgba(255, 255, 255, 0.5);
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
      }

      /* Post Stats */
      .post-stats {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        color: var(--gray-600);
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: var(--radius);
      }

      .post-stat {
        display: flex;
        align-items: center;
        gap: 0.25rem;
      }

      /* Post Actions */
      .post-actions {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
      }

      .action-button {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem;
        border: none;
        background-color: transparent;
        color: var(--gray-600);
        font-size: 0.875rem;
        cursor: pointer;
        border-radius: var(--radius-sm);
        transition: background-color 0.2s;
      }

      .action-button:hover {
        background-color: var(--gray-100);
      }

      .action-button.bookmark {
        margin-left: auto;
      }

      /* Media Grid */
      .media-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
      }

      .media-item {
        position: relative;
        aspect-ratio: 1 / 1;
        background-color: var(--orange-50);
        border-radius: var(--radius-sm);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .media-item .lock-icon {
        width: 3rem;
        height: 3rem;
        margin-bottom: 0;
      }

      /* Promo Banner */
      .promo-banner {
        background-color: #ff641c;
        color: #ffffff;
        text-align: center;
        padding: 10px 20px;
        font-weight: bold;
        font-family: Inter, sans-serif;
        text-transform: uppercase;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        font-size: 15px;
      }
      
      body {
        margin: 0;
        padding-top: 50px;
      }
      
      .promotions .subscription-button {
        font-size: 0.85rem !important;
      }

      .promotions .subscription-button .badge {
        font-size: 0.7rem !important;
      }

      .promotions .subscription-button .price {
        font-size: 0.9rem !important;
      }
      
      .container-bio {
        width: 100%;
        font-family: Arial, sans-serif;
        color: #333;
        margin-bottom: 10px;
      }

      .texto-bio {
        position: relative;
        max-height: 40px; 
        overflow: hidden;
        opacity: 0.7;
        transition: all 0.3s ease;
      }

      .texto-bio::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 25px;
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0.95));
        pointer-events: none;
        transition: opacity 0.3s ease;
      }

      .texto-bio.expandido::after {
        opacity: 0;
      }

      .texto-bio.expandido {
        max-height: none;
        opacity: 1;
      }

      .saiba-mais {
        cursor: pointer;
        font-size: 0.9em !important;
        font-weight: bold;
        display: inline-block;
        margin-top: -5px;
      }

      .margin-up {
        margin-top: 5px !important;
      }

      /* MODAL DE CONFIRMAçO */
      .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9998;
        animation: fadeIn 0.3s ease-out;
        backdrop-filter: blur(4px);
      }

      .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
      }

      @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

      .modal-container {
        background: white;
        border-radius: var(--radius-xl);
        max-width: 400px;
        width: 100%;
        max-height: calc(100dvh - 24px);
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: var(--shadow-xl);
        animation: slideUp 0.25s ease-out;
      }

      @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
      }

      .modal-header {
        background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
        padding: 1rem 1rem 0.85rem;
        text-align: center;
        position: relative;
      }

      .modal-close {
        position: absolute;
        top: 0.65rem; right: 0.65rem;
        background: rgba(255,255,255,0.2);
        border: none; color: white;
        width: 28px; height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background-color 0.2s;
        -webkit-tap-highlight-color: transparent;
      }

      .modal-close:hover { background: rgba(255,255,255,0.3); }

      .modal-icon {
        width: 50px; height: 50px;
        background: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 0.6rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      }

      .modal-icon svg { color: var(--orange-500); }

      .modal-header h3 {
        color: white;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
      }

      .modal-header p {
        color: rgba(255,255,255,0.9);
        font-size: 0.8rem;
      }

      .modal-body { padding: 1rem; }

      .modal-plan-details {
        background: var(--gray-50);
        border-radius: var(--radius);
        padding: 0.6rem 0.85rem;
        margin-bottom: 1rem;
      }

      .modal-plan-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.45rem 0;
        border-bottom: 1px solid var(--gray-200);
      }

      .modal-plan-row:last-child {
        border-bottom: none;
        padding-top: 0.55rem;
        margin-top: 0.25rem;
      }

      .modal-plan-label { font-size: 0.82rem; color: var(--gray-600); }
      .modal-plan-value { font-weight: 600; color: var(--gray-800); font-size: 0.85rem; }
      .modal-plan-total { font-size: 1.15rem; color: var(--orange-600); }

      .modal-benefits { margin-bottom: 1rem; }

      .modal-benefits h4 {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
      }

      .modal-benefit-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.3rem 0;
        font-size: 0.8rem;
        color: var(--gray-600);
      }

      .modal-benefit-icon {
        width: 18px; height: 18px;
        border-radius: 50%;
        background: var(--orange-100);
        color: var(--orange-600);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
      }

      .modal-actions { display: flex; gap: 0.6rem; }

      .modal-button {
        flex: 1;
        padding: 0.8rem 0.6rem;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        -webkit-tap-highlight-color: transparent;
      }

      .modal-button-primary {
        background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
      }

      .modal-button-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(249,115,22,0.3);
      }

      .modal-button-primary:active { transform: translateY(0); }

      .modal-button-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
      }

      .modal-button-secondary:hover { background: var(--gray-200); }

      @media (max-height: 600px) {
        .modal-header { padding: 0.65rem 1rem; }
        .modal-icon { width: 38px; height: 38px; margin-bottom: 0.4rem; }
        .modal-header h3 { font-size: 1rem; }
        .modal-body { padding: 0.75rem; }
      }

      /* â”€â”€ RESPONSIVIDADE â”€â”€ */
      @media (min-width: 768px) {
        .profile-image-container {
          width: 6rem;
          height: 6rem;
        }
        .banner { height: 14rem; }
      }

      @media (max-width: 600px) {
        main { padding: 1rem 0.5rem; }
        .container { padding: 0 0.5rem; }
        .banner { height: 10rem; }
        .banner-content h2 { font-size: 1.1rem; }
        .banner-stats { font-size: 0.7rem; gap: 0.5rem; }
        .profile-image-container {
          width: 4.5rem;
          height: 4.5rem;
          bottom: -2.25rem;
          left: 0.75rem;
        }
        .profile-section { margin-bottom: 3rem !important; }
        .card-content { padding: 0.75rem; }
        .card-header { padding: 0.75rem 0.75rem 0.25rem; }
        .post-header { padding: 6px 8px; }
        .post-user h4 { font-size: 0.82rem; }
        .post-username { font-size: 0.7rem; }
        .post-avatar { width: 2.2rem; height: 2.2rem; }
        .post-actions { padding: 0.5rem; gap: 0.25rem; }
        .action-button { font-size: 0.75rem; padding: 0.4rem; }
        .subscription-button { height: 3rem; padding: 0 1rem; font-size: 0.9rem; }
        .badge { font-size: 0.65rem; padding: 0.1rem 0.4rem; }
        .promotions .subscription-button { font-size: 0.8rem !important; }
        .media-grid { gap: 0.25rem; }
        .promo-banner { font-size: 12px; padding: 8px 12px; }
      }

      @media (max-width: 360px) {
        .banner-content h2 { font-size: 0.95rem; }
        .subscription-button { font-size: 0.82rem; }
      }
    </style>

  <!-- Anti-DevTools & Image Protection -->
  <script>
    // Bloquear F12, Ctrl+Shift+I/J/U, Ctrl+U
    document.addEventListener('keydown', function(e) {
      if (
        e.key === 'F12' ||
        (e.ctrlKey && e.shiftKey && ['I','J','C'].includes(e.key.toUpperCase())) ||
        (e.ctrlKey && e.key.toUpperCase() === 'U')
      ) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      }
    }, true);

    // Bloquear clique direito globalmente
    document.addEventListener('contextmenu', function(e) {
      e.preventDefault();
      return false;
    });

    // Detectar DevTools aberto (técnica de tamanho de janela)
    (function devToolsDetect() {
      var threshold = 160;
      setInterval(function() {
        if (
          window.outerWidth - window.innerWidth > threshold ||
          window.outerHeight - window.innerHeight > threshold
        ) {
          document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Inter,sans-serif;font-size:1.2rem;color:#ea580c;text-align:center;">Acesso bloqueado.<br>Feche as ferramentas do desenvolvedor para continuar.</div>';
        }
      }, 1000);
    })();
  </script>
  <style>
    /* Proteção de imagens */
    img, video {
      -webkit-user-drag: none;
      -khtml-user-drag: none;
      -moz-user-drag: none;
      -o-user-drag: none;
      user-drag: none;
      pointer-events: none;
    }
    /* Exceção: imagens que precisam de click (lock icons) */
    .lock-icon, .video-overlay, .media-item {
      pointer-events: all;
      cursor: pointer;
    }
    .lock-icon img, .video-overlay img, .media-item img {
      pointer-events: none;
    }
    /* Seleção de texto bloqueada */
    body {
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
  </style>

  <style>
    /* â”€â”€ SPINNER â”€â”€ */
    @keyframes spn { to { transform: rotate(360deg); } }
    .subtab-spinner {
      width: 36px; height: 36px;
      border: 3px solid rgba(249,115,22,0.2);
      border-top-color: #f97316;
      border-radius: 50%;
      animation: spn 0.65s linear infinite;
      flex-shrink: 0;
    }
    /* â”€â”€ LIKE BUTTON â”€â”€ */
    .like-btn {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 0.5rem;
      border: none;
      background: transparent;
      color: var(--gray-500);
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      border-radius: var(--radius-sm);
      position: relative;
      -webkit-tap-highlight-color: transparent;
      transition: color 0.2s;
    }
    .like-btn svg {
      transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                  fill 0.25s ease,
                  stroke 0.25s ease,
                  filter 0.25s ease;
      flex-shrink: 0;
    }
    .like-btn.liked {
      color: #e11d48;
    }
    .like-btn.liked svg {
      fill: #e11d48;
      stroke: #e11d48;
      filter: drop-shadow(0 0 6px rgba(225,29,72,0.55));
      transform: scale(1.25);
    }
    .like-btn.pop svg {
      animation: heartDepth 0.42s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes heartDepth {
      0%   { transform: scale(1);    filter: drop-shadow(0 0 0px rgba(225,29,72,0)); }
      25%  { transform: scale(0.72); filter: drop-shadow(0 3px 10px rgba(225,29,72,0.7)); }
      65%  { transform: scale(1.45); filter: drop-shadow(0 0 14px rgba(225,29,72,0.9)); }
      100% { transform: scale(1.25); filter: drop-shadow(0 0 6px rgba(225,29,72,0.55)); }
    }
    .like-btn.unpop svg {
      animation: heartUnpop 0.28s ease forwards;
    }
    @keyframes heartUnpop {
      0%   { transform: scale(1.25); }
      50%  { transform: scale(0.85); }
      100% { transform: scale(1); fill: none; filter: none; }
    }
  
    /* â”€â”€ NOTIFICAí‡í•ES DE BOTS â”€â”€ */
    #bot-notif {
      position: fixed;
      bottom: 24px;
      left: 16px;
      z-index: 9000;
      pointer-events: none;
    }

    .bot-card {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(20, 20, 20, 0.96);
      border: 1px solid rgba(255,255,255,0.1);
      border-left: 3px solid #ff6b35;
      border-radius: 12px;
      padding: 10px 14px;
      max-width: 260px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.5);
      backdrop-filter: blur(12px);
      transform: translateX(-110%);
      opacity: 0;
      transition: transform 0.4s cubic-bezier(0.34,1.3,0.64,1), opacity 0.3s ease;
      will-change: transform;
    }

    .bot-card.show {
      transform: translateX(0);
      opacity: 1;
    }

    .bot-card.hide {
      transform: translateX(-110%);
      opacity: 0;
    }

    .bot-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ff6b35, #e8551e);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .bot-text {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .bot-name {
      font-size: 12px;
      font-weight: 700;
      color: #f0f0f0;
      font-family: -apple-system, sans-serif;
    }

    .bot-action {
      font-size: 11px;
      color: #999;
      font-family: -apple-system, sans-serif;
      line-height: 1.3;
    }

    .bot-action span {
      color: #ff6b35;
      font-weight: 600;
    }

    .bot-time {
      font-size: 10px;
      color: #555;
      margin-top: 1px;
      font-family: -apple-system, sans-serif;
    }
    @media (max-width: 360px) {
      .bot-card { max-width: 220px; }
    }
</style>
    <script>
    // Carrega planos.php sem cache e atualiza preços após load
    (function(){
      var s = document.createElement('script');
      s.src = 'planos.php?v=' + Date.now();
      s.onload = function() {
        var fmt = function(v){ return 'R$ '+Number(v).toFixed(2).replace('.',','); };
        var map = {
          'nome-mensal':_P.mensal.nome,         'preco-mensal':fmt(_P.mensal.preco),
          'nome-semanal':_P.semanal.nome,        'preco-semanal':fmt(_P.semanal.preco),
          'nome-trimestral':_P.trimestral.nome,  'preco-trimestral':fmt(_P.trimestral.preco),
          'nome-anual':_P.anual.nome,            'preco-anual':fmt(_P.anual.preco),
          'nome-vitalicio':_P.vitalicio.nome,    'preco-vitalicio':fmt(_P.vitalicio.preco)
        };
        Object.keys(map).forEach(function(id){
          var el = document.getElementById(id);
          if(el) el.textContent = map[id];
        });
      };
      document.head.appendChild(s);
    })();
    </script>
</head>
<body>
    
    <!-- Cabeçalho -->
    <header class="header">
      <div class="container header-container">
        <div class="logo">
          <img src="images/images-logo.webp" alt="Logo do Privacy" class="logo-image">
        </div>
      </div>
    </header>

    <!-- Conteúdo Principal -->
    <div class="promo-banner" id="promoBanner">
    ESSA PROMOÇÃO É VÁLIDA ATÉ XX/XX/XXXX
  </div>

    <main class="container">
      <!-- Seção de Perfil com Banner e Foto -->
      <div class="profile-section" style="margin-bottom: 50px">
        <!-- Banner -->
        <div class="banner">
<?php
$bannerMedia = $siteData['banner'] ?? 'images/fotocapaeduarda1.jpg';
$isBannerVideo = preg_match('/\.(mp4|webm|ogg)$/i', $bannerMedia);
if ($isBannerVideo): ?>
          <video data-src="<?= $bannerMedia ?>#t=0.1" class="banner-image lazy-video" style="object-fit: cover; width: 100%; height: 100%;" loop muted playsinline webkit-playsinline preload="none"></video>
<?php else: ?>
          <img src="<?= $bannerMedia ?>" alt="Foto de Capa" class="banner-image" style="object-fit: cover; width: 100%; height: 100%;">
<?php endif; ?>
          <div class="banner-overlay"></div>
          <div class="banner-content">
            <h2> <?= $siteData['nome_modelo'] ?? 'Eduarda Oficial' ?> <svg style="display:inline;vertical-align:middle;width:20px;height:20px" viewBox="0 0 24 24" fill="#ff6b8a"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg></h2>
            <div class="banner-stats">
              <div class="stat">
                <svg class="svg-inline--fa fa-image" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="image" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 17px">
                  <path class="" fill="currentColor" d="M64 64C46.3 64 32 78.3 32 96V329.4l67.7-67.7c15.6-15.6 40.9-15.6 56.6 0L224 329.4 355.7 197.7c15.6-15.6 40.9-15.6 56.6 0L480 265.4V96c0-17.7-14.3-32-32-32H64zM32 374.6V416c0 17.7 14.3 32 32 32h41.4l96-96-67.7-67.7c-3.1-3.1-8.2-3.1-11.3 0L32 374.6zM389.7 220.3c-3.1-3.1-8.2-3.1-11.3 0L150.6 448H448c17.7 0 32-14.3 32-32V310.6l-90.3-90.3zM0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm160 48a16 16 0 1 0 -32 0 16 16 0 1 0 32 0zm-64 0a48 48 0 1 1 96 0 48 48 0 1 1 -96 0z"></path>
                </svg>
                <span>401</span>
              </div>
              <div class="stat">
                <svg class="svg-inline--fa fa-video" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="video" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 17px">
                  <path class="" fill="currentColor" d="M64 96c-17.7 0-32 14.3-32 32V384c0 17.7 14.3 32 32 32H320c17.7 0 32-14.3 32-32V128c0-17.7-14.3-32-32-32H64zM0 128C0 92.7 28.7 64 64 64H320c35.3 0 64 28.7 64 64v47.2V336.8 384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zM519.4 411.3L416 354.4V317.9l118.8 65.4c.9 .5 1.9 .8 3 .8c3.4 0 6.2-2.8 6.2-6.2V134.2c0-3.4-2.8-6.2-6.2-6.2c-1 0-2.1 .3-3 .8L416 194.1V157.6l103.4-56.9c5.6-3.1 12-4.7 18.4-4.7c21.1 0 38.2 17.1 38.2 38.2V377.8c0 21.1-17.1 38.2-38.2 38.2c-6.4 0-12.8-1.6-18.4-4.7z"></path>
                </svg>
                <span>438</span>
              </div>
              <div class="stat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <span>229K</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Foto de Perfil Sobreposta -->
        <div class="profile-image-container">
          <img src="<?= $siteData['avatar'] ?? 'images/fotoperfileduarda.jpg' ?>" alt="Foto de Perfil da Mirella" class="profile-image" style="scale: 1.0;">
        </div>
      </div>

      <!-- Informações do Perfil -->
      <div class="card profile-card" style="margin-top: 0px">
        <div class="card-content" style="padding-top: 14px">
          <div style="display: flex; align-items: center; gap: 4px">
            <span><?= $siteData['nome_modelo'] ?? 'Eduarda Oficial' ?></span>
            <div style="width: 18px; height: 18px; margin-top: -4px">
              <svg class="svg-inline--fa fa-badge-check" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="badge-check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path class="" fill="rgb(253, 115, 80)" d="M190.6 71.4C203 47.9 227.7 32 256 32s53 15.9 65.4 39.4c3.6 6.8 11.5 10.1 18.8 7.8c25.4-7.8 54.1-1.6 74.1 18.4s26.2 48.7 18.4 74.1c-2.3 7.3 1 15.2 7.8 18.8C464.1 203 480 227.7 480 256s-15.9 53-39.4 65.4c-6.8 3.6-10.1 11.5-7.8 18.8c7.8 25.4 1.6 54.1-18.4 74.1s-48.7 26.2-74.1 18.4c-7.3-2.3-15.2 1-18.8 7.8C309 464.1 284.3 480 256 480s-53-15.9-65.4-39.4c-3.6-6.8-11.5-10.1-18.8-7.8c-25.4 7.8-54.1 1.6-74.1-18.4s-26.2-48.7-18.4-74.1c2.3-7.3-1-15.2-7.8-18.8C47.9 309 32 284.3 32 256s15.9-53 39.4-65.4c6.8-3.6 10.1-11.5 7.8-18.8c-7.8-25.4-1.6-54.1 18.4-74.1s48.7-26.2 74.1-18.4c7.3 2.3 15.2-1 18.8-7.8zM256 0c-36.1 0-68 18.1-87.1 45.6c-33-6-68.3 3.8-93.9 29.4s-35.3 60.9-29.4 93.9C18.1 188 0 219.9 0 256s18.1 68 45.6 87.1c-6 33 3.8 68.3 29.4 93.9s60.9 35.3 93.9 29.4C188 493.9 219.9 512 256 512s68-18.1 87.1-45.6c33 6 68.3-3.8 93.9-29.4s35.3-60.9 29.4-93.9C493.9 324 512 292.1 512 256s-18.1-68-45.6-87.1c6-33-3.8-68.3-29.4-93.9s-60.9-35.3-93.9-29.4C324 18.1 292.1 0 256 0zM363.3 203.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L224 297.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"></path>
              </svg>
            </div>
          </div>

      <p class="username" id="username-text">@eduardaoficial1_</p>

      <div class="container-bio">
        <div class="texto-bio bio" id="texto"></div>
        <span class="saiba-mais" id="botao">Mostrar mais</span>
      </div>

      <!-- Opções de Assinatura -->
      <div class="card">
        <div class="card-header">
          <h3>Assinaturas</h3>
        </div>
        <div class="card-content">
          <p class="badge" style="display: block; width: fit-content">
            MAIS POPULAR <svg style="display:inline;vertical-align:middle;width:14px;height:14px" viewBox="0 0 24 24" fill="#ea580c"><path d="M17.66 11.2c-.23-.3-.51-.56-.77-.82-.67-.6-1.43-1.03-2.07-1.66C13.33 7.26 13 4.85 13.95 3c-.95.23-1.78.75-2.49 1.32-2.59 2.08-3.61 5.75-2.39 8.9.04.1.08.2.08.33 0 .22-.15.42-.35.5-.23.1-.47.04-.66-.12a.58.58 0 0 1-.14-.17c-1.13-1.43-1.31-3.48-.55-5.12C5.78 10 4.87 12.3 5 14.47c.06.5.12 1 .29 1.5.14.6.41 1.2.71 1.73 1.08 1.73 2.95 2.97 4.96 3.22 2.14.27 4.43-.12 6.07-1.6 1.83-1.66 2.47-4.32 1.53-6.6l-.13-.26c-.21-.46-.77-1.26-.77-1.26m-3.16 6.3c-.28.24-.74.5-1.1.6-1.12.4-2.24-.16-2.9-.82 1.19-.28 1.9-1.16 2.11-2.05.17-.8-.15-1.46-.28-2.23-.12-.74-.1-1.37.17-2.06.19.38.39.75.63 1.06.77 1 1.98 1.44 2.24 2.8.04.14.06.28.06.43.03.82-.33 1.72-.93 2.27z"/></svg><svg style="display:inline;vertical-align:middle;width:14px;height:14px" viewBox="0 0 24 24" fill="#ea580c"><path d="M17.66 11.2c-.23-.3-.51-.56-.77-.82-.67-.6-1.43-1.03-2.07-1.66C13.33 7.26 13 4.85 13.95 3c-.95.23-1.78.75-2.49 1.32-2.59 2.08-3.61 5.75-2.39 8.9.04.1.08.2.08.33 0 .22-.15.42-.35.5-.23.1-.47.04-.66-.12a.58.58 0 0 1-.14-.17c-1.13-1.43-1.31-3.48-.55-5.12C5.78 10 4.87 12.3 5 14.47c.06.5.12 1 .29 1.5.14.6.41 1.2.71 1.73 1.08 1.73 2.95 2.97 4.96 3.22 2.14.27 4.43-.12 6.07-1.6 1.83-1.66 2.47-4.32 1.53-6.6l-.13-.26c-.21-.46-.77-1.26-.77-1.26m-3.16 6.3c-.28.24-.74.5-1.1.6-1.12.4-2.24-.16-2.9-.82 1.19-.28 1.9-1.16 2.11-2.05.17-.8-.15-1.46-.28-2.23-.12-.74-.1-1.37.17-2.06.19.38.39.75.63 1.06.77 1 1.98 1.44 2.24 2.8.04.14.06.28.06.43.03.82-.33 1.72-.93 2.27z"/></svg>
          </p>
          <a href="javascript:void(0)" class="subscription-link" onclick="abrirModal('mensal',     _P.mensal.nome,     _P.mensal.preco,     _P.mensal.bonus); return false;">
            <button class="subscription-button primary-button pulse">
              <b id="nome-mensal">30 DIAS</b>
              <span class="price" id="preco-mensal">R$ 32,99</span>
            </button>
          </a>
          <p class="badge" style="
              margin-top: -16px;
              display: block;
              width: fit-content;
              font-weight: bold;
            ">
            <svg style="display:inline;vertical-align:middle;width:13px;height:13px;margin-right:3px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg> BÔNUS EXCLUSIVO HOJE - VAGAS LIMITADAS!
          </p>

          <div class="promotions">
            <div class="promotions-header">
              <h4>Mais Planos</h4>
              <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>

            <a href="javascript:void(0)" class="subscription-link" onclick="abrirModal('semanal',    _P.semanal.nome,    _P.semanal.preco,    _P.semanal.bonus); return false;">
              <button class="subscription-button outline-button">
                <div class="button-left">
                  <span id="nome-semanal">7 Dias</span>
                  <span class="badge">Semanal</span>
                </div>
                <span class="price highlight" id="preco-semanal">R$ 19,90</span>
              </button>
            </a>

            <a href="javascript:void(0)" class="subscription-link" onclick="abrirModal('trimestral', _P.trimestral.nome, _P.trimestral.preco, _P.trimestral.bonus); return false;">
              <button class="subscription-button outline-button">
                <div class="button-left">
                  <span id="nome-trimestral">3 Meses</span>
                  <span class="badge">Economia 40%</span>
                </div>
                <span class="price highlight" id="preco-trimestral">R$ 121,00</span>
              </button>
            </a>

            <a href="javascript:void(0)" class="subscription-link" onclick="abrirModal('anual',      _P.anual.nome,      _P.anual.preco,      _P.anual.bonus); return false;">
              <button class="subscription-button outline-button">
                <div class="button-left">
                  <span id="nome-anual">1 ANO</span>
                  <span class="badge">Economia 55%</span>
                </div>
                <span class="price highlight" id="preco-anual">R$ 299,00</span>
              </button>
            </a>

            <a href="javascript:void(0)" class="subscription-link" onclick="abrirModal('vitalicio',  _P.vitalicio.nome,  _P.vitalicio.preco,  _P.vitalicio.bonus); return false;">
              <button class="subscription-button outline-button" style="border: 2px solid var(--orange-500);">
                <div class="button-left">
                  <span style="display:flex;align-items:center;gap:4px"><svg style="width:15px;height:15px" viewBox="0 0 24 24" fill="#f97316" stroke="#f97316" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> VITALÍCIO</span>
                  <span class="badge" style="background: linear-gradient(135deg, #ff7643, #ff6530); color: white;">ACESSO ETERNO</span>
                </div>
                <span class="price highlight" id="preco-vitalicio">R$ 525,50</span>
              </button>
            </a>
          </div>
        </div>
      </div>

      <!-- Abas de Conteíºdo -->
      <div class="content-tabs">
        <div class="tabs">
          <button class="tab active" data-tab="posts">93 postagens</button>
          <button class="tab" data-tab="media">412 mídias</button>
        </div>

        <!-- Spinner entre abas e conteúdo -->
        <div id="tab-spinner-wrap" style="display:none; justify-content:center; align-items:center; padding: 3rem 0; width:100%;">
          <div class="subtab-spinner"></div>
        </div>

        <!-- Conteíºdo da Aba de Postagens -->
        <div class="tab-content active" id="posts-content">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px">
<?php
$defaultGrid = ['images/fotoeduarda1.jpg', 'media/videoeduarda3.mp4', 'media/videoeduarda4.mp4'];
$gridItems = !empty($siteData['grid']) && is_array($siteData['grid']) ? $siteData['grid'] : $defaultGrid;
foreach ($gridItems as $index => $media) {
    $is_video = preg_match('/\.(mp4|webm|ogg)$/i', $media);
    $post_id = "p" . ($index + 1);
    $like_count = rand(1000, 5000);
    $comment_count = rand(100, 900);
?>
            <div class="post-card" style="margin-bottom: 20px">
              <div class="post-header">
                <div class="post-user">
                  <img src="<?= $siteData['avatar'] ?? 'images/fotoperfileduarda.jpg' ?>" alt="Foto de Perfil" class="post-avatar" style="object-position: 0% 9%;">
                  <div>
                    <h4><?= $siteData['nome_modelo'] ?? 'Eduarda Oficial' ?></h4>
                    <p class="post-username"><?= $siteData['username'] ?? '@eduardaoficial1_' ?></p>
                  </div>
                </div>
                <button class="post-menu">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                  </svg>
                </button>
              </div>

              <div class="post-content">
                <div class="post-video-container" style="height: 100%">
                  <?php if ($is_video): ?>
                  <video data-src="<?= $media ?>#t=0.1" style="scale: 1.2;" class="post-video lazy-video" loop muted playsinline webkit-playsinline="true" x-webkit-airplay="allow" preload="none"></video>
                  <?php else: ?>
                  <img src="<?= $media ?>" style="object-fit: cover; width: 100%; height: 100%" alt="">
                  <?php endif; ?>
                  <div class="video-overlay" onclick="abrirModal('mensal', _P.mensal.nome, _P.mensal.preco, _P.mensal.bonus); return false;" style="cursor:pointer">
                    <div class="lock-icon">
                      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                      </svg>
                    </div>
                    <div class="post-stats">
                      <div class="post-stat">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span><?= $like_count ?></span>
                      </div>
                      <div class="post-stat">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span><?= $comment_count ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="post-actions">
                <button class="like-btn action-button" data-id="<?= $post_id ?>" data-start="<?= $like_count ?>" onclick="handleLike(this)">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                  </svg>
                  <span class="like-count"><?= $like_count ?></span>
                </button>
                <button class="action-button" onclick="animarEAssinar(this)">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                  </svg>
                </button>
                <button class="action-button bookmark" onclick="animarEAssinar(this)">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                  </svg>
                </button>
              </div>
            </div>
<?php } ?>
          </div>
        </div>
        <!-- Conteúdo da Aba de Mídias -->
        <div class="tab-content" id="media-content" style="display: none;">
          <style>
            .mini-carousel {
              position: relative;
              width: 100%;
              height: 100%;
              overflow: hidden;
            }
            .mini-carousel-item {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              opacity: 0;
              transition: opacity 0.8s ease-in-out;
              object-fit: cover;
            }
            .mini-carousel-item.active {
              opacity: 1;
            }
          </style>
          <div class="media-grid" style="grid-template-rows: repeat(2, 1fr);">
            <?php
            // Configurações do admin
            $blurLevel = isset($siteData['config']['blur_level']) ? intval($siteData['config']['blur_level']) : 10;
            $mediaSource = isset($siteData['config']['media_source']) ? $siteData['config']['media_source'] : 'misto';
            $mediaDisplay = isset($siteData['config']['media_display']) ? $siteData['config']['media_display'] : 'misto';

            // Ler arquivos da pasta padrão
            $dirPadrao = __DIR__ . '/media_padrao';
            $filesPadrao = [];
            if (is_dir($dirPadrao)) {
                $scan = scandir($dirPadrao);
                foreach ($scan as $file) {
                    if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|mp4|webm)$/i', $file)) {
                        $filesPadrao[] = 'media_padrao/' . $file;
                    }
                }
            }
            if (empty($filesPadrao)) {
                $filesPadrao = ['media/videoeduarda3.mp4', 'media/videoeduarda4.mp4', 'media/videoeduarda1.mp4', 'media/videoeduarda2.mp4'];
            }

            $filesAdicionadas = !empty($siteData['grid']) && is_array($siteData['grid']) ? $siteData['grid'] : [];

            if ($mediaSource === 'padrao') {
                $listaMídias = $filesPadrao;
            } elseif ($mediaSource === 'adicionadas') {
                $listaMídias = !empty($filesAdicionadas) ? $filesAdicionadas : $filesPadrao;
            } else { // misto
                $listaMídias = array_merge($filesAdicionadas, $filesPadrao);
            }

            if (empty($listaMídias)) {
                $listaMídias = $filesPadrao;
            }

            // Exibir exatamente 6 itens (2 fileiras de 3)
            for ($i = 0; $i < 6; $i++) {
                // Decidir se é carrossel ou padrão
                $isCarousel = false;
                if ($mediaDisplay === 'carrosel') {
                    $isCarousel = true;
                } elseif ($mediaDisplay === 'misto') {
                    // Alterna carrossel nos índices pares
                    $isCarousel = ($i % 2 === 0);
                }

                // Se houver apenas 1 mídia disponível, não dá para fazer carrossel
                if (count($listaMídias) < 2) {
                    $isCarousel = false;
                }
            ?>
              <div class="media-item" onclick="abrirModal('mensal', _P.mensal.nome, _P.mensal.preco, _P.mensal.bonus); return false;" style="cursor: pointer;">
                <?php if ($isCarousel): 
                    // Pegar 3 mídias distintas sequencialmente para o carrossel
                    $carouselMedia = [];
                    for ($j = 0; $j < 3; $j++) {
                        $mediaIndex = ($i * 3 + $j) % count($listaMídias);
                        $carouselMedia[] = $listaMídias[$mediaIndex];
                    }
                ?>
                  <div class="mini-carousel">
                    <?php foreach ($carouselMedia as $idx => $cMedia): 
                        $is_video = preg_match('/\.(mp4|webm|ogg)$/i', $cMedia);
                    ?>
                      <?php if ($is_video): ?>
                        <video data-src="<?= $cMedia ?>#t=0.1" class="mini-carousel-item lazy-video <?= $idx === 0 ? 'active' : '' ?>" style="filter: blur(<?= $blurLevel ?>px); transform: scale(1.3);" loop muted playsinline webkit-playsinline preload="none"></video>
                      <?php else: ?>
                        <img src="<?= $cMedia ?>" class="mini-carousel-item <?= $idx === 0 ? 'active' : '' ?>" style="filter: blur(<?= $blurLevel ?>px); transform: scale(1.3);" alt="Locked">
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                <?php else: 
                    // Padrão: 1 mídia estática
                    $media = $listaMídias[$i % count($listaMídias)];
                    $is_video = preg_match('/\.(mp4|webm|ogg)$/i', $media);
                ?>
                  <?php if ($is_video): ?>
                    <video data-src="<?= $media ?>#t=0.1" class="lazy-video" style="width: 100%; height: 100%; object-fit: cover; filter: blur(<?= $blurLevel ?>px); transform: scale(1.3);" loop muted playsinline webkit-playsinline preload="none"></video>
                  <?php else: ?>
                    <img src="<?= $media ?>" style="width: 100%; height: 100%; object-fit: cover; filter: blur(<?= $blurLevel ?>px); transform: scale(1.3);" alt="Locked">
                  <?php endif; ?>
                <?php endif; ?>
                
                <div class="lock-icon" style="position: absolute; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.45); border-radius: 50%; z-index: 2; pointer-events: none;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                  </svg>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal de Desbloqueio de Vídeo -->
    <div class="unlock-modal-overlay" id="unlock-modal">
      <div class="unlock-modal-container">
        <button class="unlock-modal-close" onclick="fecharUnlockModal()">×</button>
        
        <div class="unlock-media-container">
          <div class="mini-carousel" style="width: 100%; height: 100%;">
            <?php 
            $modalMedia = $siteData['modal_gif'] ?? 'images/modal_gif.gif';
            $popupMediaList = array_merge([$modalMedia], $listaMídias);
            $popupMediaList = array_slice($popupMediaList, 0, 5);
            foreach ($popupMediaList as $idx => $pMedia):
                $is_video = preg_match('/\.(mp4|webm|ogg)$/i', $pMedia);
            ?>
              <?php if ($is_video): ?>
                <video data-src="<?= $pMedia ?>#t=0.1" class="mini-carousel-item lazy-video <?= $idx === 0 ? 'active' : '' ?>" style="filter: blur(8px); transform: scale(1.3); width: 100%; height: 100%; object-fit: cover;" loop muted playsinline webkit-playsinline preload="none"></video>
              <?php else: ?>
                <img src="<?= $pMedia ?>" class="mini-carousel-item <?= $idx === 0 ? 'active' : '' ?>" style="filter: blur(8px); transform: scale(1.3); width: 100%; height: 100%; object-fit: cover;" alt="Preview">
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <div class="unlock-media-overlay">
            <div class="unlock-lock-icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="unlock-modal-content">
          <h3>Proposta Exclusiva - Liberação de Pacote Especial</h3>
          <p>Desbloqueie agora o vídeo exclusivo sem tarjas + fotos privadas da modelo!</p>
          <a href="javascript:void(0)" class="unlock-modal-btn" id="unlock-btn">
            DESBLOQUEAR CONTEÚDO
          </a>
        </div>
      </div>
    </div>

    <!-- Script principal (modal, abas) -->
    <script src="script.js?v=1781064364"></script>
    <script>
    (function() {
        var videos = document.querySelectorAll('.lazy-video');
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                var v = entry.target;
                if (entry.isIntersecting) {
                    if (!v.src) {
                        v.src = v.getAttribute('data-src');
                        v.load();
                    }
                    v.muted = true;
                    v.defaultMuted = true;
                    var p = v.play();
                    if (p !== undefined) {
                        p.catch(function(e){ console.log("Autoplay block:", e); });
                    }
                } else {
                    if (!v.paused) v.pause();
                }
            });
        }, { rootMargin: '200px' });
        
        videos.forEach(function(v) {
            observer.observe(v);
        });
        
        var forcePlayAll = function() {
            videos.forEach(function(v) {
                if (v.src && v.paused) {
                    v.muted = true;
                    v.defaultMuted = true;
                    v.play().catch(function(e){});
                }
            });
        };
        document.body.addEventListener('touchstart', forcePlayAll, {once: true});
        document.body.addEventListener('click', forcePlayAll, {once: true});
    })();
    </script>
</body>
</html>
<?php
$html = ob_get_clean();
$encoded = base64_encode($html);
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Loading...</title></head><body>';
echo '<script>';
echo 'let html = decodeURIComponent(escape(window.atob("'.$encoded.'")));';
echo 'document.open(); document.write(html); document.close();';
echo '</script><noscript>Acesso negado</noscript></body></html>';
?>