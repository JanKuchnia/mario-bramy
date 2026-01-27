<?php
/**
 * Common Footer - Used on all public pages
 */
?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div>
                    <img src="/mario-bramy/assets/images/logo-white.png" alt="Mario Bramy" height="40" class="mb-4" loading="lazy">
                    <p class="text-gray-400 text-sm">
                        Profesjonalne bramy i ogrodzenia aluminiowe. Serwis, gwarancja, doświadczenie.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold mb-4">Produkty</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/mario-bramy/public/nasze-projekty.php?category=bramy-przesuwne" class="hover:text-white transition">Bramy Przesuwne</a></li>
                        <li><a href="/mario-bramy/public/nasze-projekty.php?category=bramy-dwuskrzydlowe" class="hover:text-white transition">Bramy Dwuskrzydłowe</a></li>
                        <li><a href="/mario-bramy/public/nasze-projekty.php?category=balustrady" class="hover:text-white transition">Balustrady</a></li>
                        <li><a href="/mario-bramy/public/nasze-projekty.php?category=przesla-ogrodzeniowe" class="hover:text-white transition">Przęsła Ogrodzeniowe</a></li>
                    </ul>
                </div>
                
                <!-- Navigation -->
                <div>
                    <h4 class="font-bold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/mario-bramy/" class="hover:text-white transition">Strona główna</a></li>
                        <li><a href="/mario-bramy/public/nasze-projekty.php" class="hover:text-white transition">Realizacje</a></li>
                        <li><a href="/mario-bramy/public/opinie.php" class="hover:text-white transition">Opinie klientów</a></li>
                        <li><a href="/mario-bramy/public/kontakt.php" class="hover:text-white transition">Kontakt</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4">Kontakt</h4>
                    <p class="text-sm text-gray-400 mb-2">
                        <strong>Tel:</strong> <a href="tel:+48668197170" class="hover:text-white transition">+48 668 197 170</a>
                    </p>
                    <p class="text-sm text-gray-400 mb-2">
                        <strong>Email:</strong> <a href="mailto:mario.bramy@gmail.com" class="hover:text-white transition">mario.bramy@gmail.com</a>
                    </p>
                    <p class="text-sm text-gray-400">
                        <strong>Adres:</strong><br>Wiśniowa 782, 32-412 Wiśniowa
                    </p>
                </div>
            </div>
            
            <!-- Divider -->
            <hr class="border-gray-800 mb-8">
            
            <!-- Bottom -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
                <p>&copy; 2026 Mario Bramy. Wszystkie prawa zastrzeżone.</p>
                <div class="flex gap-4">
                    <a href="https://www.facebook.com" target="_blank" rel="noopener" class="hover:text-white transition">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="/mario-bramy/assets/js/common.js"></script>
    <?php if (isset($additional_scripts)): ?>
        <?php foreach ($additional_scripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
