<?php
$file = 'd:/Dudinha/real.php';
$content = file_get_contents($file);

$brokenEnd = "<?php } ?>\n          </div>\necho '<!DOCTYPE html>";

$startPos = strpos($content, "<?php } ?>\n          </div>");
if ($startPos !== false) {
    // Replace everything from $startPos to the end of the file
    $replacement = <<<EOF
<?php } ?>
          </div>
        </div>
      </div>
    </main>

    <!-- Script principal (modal, abas) -->
    <script src="script.js"></script>

</body>
</html>
<?php
\$html = ob_get_clean();
\$encoded = base64_encode(\$html);
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Loading...</title></head><body>';
echo '<script>';
echo 'let html = decodeURIComponent(escape(window.atob("'.\$encoded.'")));';
echo 'document.open(); document.write(html); document.close();';
echo '</script><noscript>Acesso negado</noscript></body></html>';
?>
EOF;

    $content = substr($content, 0, $startPos) . $replacement;
    file_put_contents($file, $content);
    echo "Fixed end of real.php\n";
} else {
    echo "Could not find the start position.\n";
}
