<?php
echo "<html><body style='font-family: sans-serif; text-align: center; padding: 50px;'>";

if (extension_loaded('gd')) {
    echo "<h1 style='color: green;'>✅ Biblioteka GD jest AKTYWNA!</h1>";
    echo "<p>Twój serwer obsługuje konwersję zdjęć.</p>";
    
    echo "<h3>Szczegóły:</h3>";
    echo "<pre style='text-align: left; display: inline-block; background: #f0f0f0; padding: 20px; border-radius: 10px;'>" . print_r(gd_info(), true) . "</pre>";
    
    if (function_exists('imagewebp')) {
        echo "<h3 style='color: green;'>✅ Obsługa WebP jest dostępna!</h3>";
    } else {
        echo "<h3 style='color: red;'>❌ Brak obsługi WebP (jest tylko podstawowe GD)</h3>";
    }
    
} else {
    echo "<h1 style='color: red;'>❌ Biblioteka GD NIE jest aktywna</h1>";
    echo "<p>Musisz ją włączyć w panelu hostingu.</p>";
    echo "<hr>";
    echo "<h3>Jak to włączyć na Hostingerze?</h3>";
    echo "<ol style='text-align: left; display: inline-block;'>";
    echo "<li>Zaloguj się do hPanel.</li>";
    echo "<li>Wejdź w <strong>Zaawansowane (Advanced)</strong> > <strong>Konfiguracja PHP (PHP Configuration)</strong>.</li>";
    echo "<li>Kliknij zakładkę <strong>Rozszerzenia PHP (PHP Extensions)</strong>.</li>";
    echo "<li>Znajdź na liście <strong>gd</strong> i zaznacz checkbox.</li>";
    echo "<li>Kliknij Zapisz.</li>";
    echo "</ol>";
}

echo "</body></html>";
