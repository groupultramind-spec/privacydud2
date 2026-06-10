<?php
$file = 'd:/Dudinha/real.php';
$content = file_get_contents($file);

$badStart = "    // Carrega planos.php sem cache e atualiza";

$startPos = strpos($content, $badStart);
// Find the first occurrence of .subtab-spinner after startPos
$endPos = strpos($content, ".subtab-spinner", $startPos);

if ($startPos !== false && $endPos !== false) {
    // We want to replace up to the <style> right before .subtab-spinner
    $stylePos = strrpos(substr($content, 0, $endPos), "<style>");
    
    $replacement = <<<EOF
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

  <style>
EOF;

    $content = substr_replace($content, $replacement, $startPos, $stylePos - $startPos + 7);
    file_put_contents($file, $content);
    echo "Fixed header correctly.\n";
} else {
    echo "Could not find bounds.\n";
}
