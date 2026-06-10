<?php
$file = 'd:/Dudinha/real.php';
$content = file_get_contents($file);

$replacements = [
    'PROMOÇíO' => 'PROMOÇÃO',
    'Ví LIDA' => 'VÁLIDA',
    'VITALí CIO' => 'VITALÍCIO',
    'Bí”NUS' => 'BÔNUS',
    'conteíºdo' => 'conteúdo',
    'Perí­odo' => 'Período',
    'InformaÇíµes' => 'Informações',
    'OpÇíµes' => 'Opções',
    'SeÇí£o' => 'Seção',
    'ví­deos' => 'vídeos',
    'ví­deo' => 'vídeo',
    'AtualizaÇíµes' => 'Atualizações',
    'AÇí£o' => 'Ação',
    'mí­dias' => 'mídias',
    'Vocíª' => 'Você',
    'estí¡' => 'está',
    'Çí£' => 'çã',
    'Çí£o' => 'ção',
    'Çí' => 'ç',
    'Ã‡ÃƒO' => 'ÇÃO',
    'Ã‰' => 'É',
    'VÃ LIDA' => 'VÁLIDA',
    'ATÃ‰' => 'ATÉ',
    'VITALÃ CIO' => 'VITALÍCIO',
    'mÃ­dias' => 'mídias',
    'BÃ”NUS' => 'BÔNUS',
    'VocÃª' => 'Você',
    'estÃ¡' => 'está',
    'conteÃºdo' => 'conteúdo',
    'PerÃ­odo' => 'Período',
    'InformaÃ§Ãµes' => 'Informações',
    'OpÃ§Ãµes' => 'Opções',
    'SeÃ§Ã£o' => 'Seção',
    'vÃ­deos' => 'vídeos',
    'vÃ­deo' => 'vídeo',
    'AtualizaÃ§Ãµes' => 'Atualizações',
    'AÃ§Ã£o' => 'Ação'
];

foreach ($replacements as $k => $v) {
    $content = str_replace($k, $v, $content);
}

// Just to be sure, grid default is:
$oldGrid = "\$gridItems = isset(\$siteData['grid']) && is_array(\$siteData['grid']) ? \$siteData['grid'] : [];";
$newGrid = "\$defaultGrid = ['images/fotoeduarda1.jpg', 'media/videoeduarda3.mp4', 'media/videoeduarda4.mp4'];\n\$gridItems = !empty(\$siteData['grid']) && is_array(\$siteData['grid']) ? \$siteData['grid'] : \$defaultGrid;";
$content = str_replace($oldGrid, $newGrid, $content);

file_put_contents($file, $content);
echo "Fixed secondary";
