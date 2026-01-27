<?php
/**
 * Common Header - Used on all public pages
 */
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mario Bramy - Profesjonalne bramy i ogrodzenia aluminiowe">
    <meta name="keywords" content="bramy, ogrodzenia, aluminium, bramki">
    <meta name="author" content="Mario Bramy">
    <meta property="og:title" content="Mario Bramy">
    <meta property="og:description" content="Profesjonalne bramy i ogrodzenia aluminiowe">
    <meta property="og:type" content="website">
    
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Mario Bramy' : 'Mario Bramy - Bramy i Ogrodzenia Aluminiowe'; ?></title>
    
    <link rel="stylesheet" href="/mario-bramy/assets/css/base.css">
    <link rel="stylesheet" href="/mario-bramy/assets/css/style.css">
    <link rel="stylesheet" href="/mario-bramy/assets/css/fontawesome.css">
    <link rel="icon" type="image/png" href="/mario-bramy/assets/images/favicon.png">
    
    <!-- Google Fonts (if needed) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
    <!-- Header Navigation -->
    <header id="global-header" class="sticky top-0 z-50 bg-white shadow">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="logo">
                <a href="/mario-bramy/" class="flex items-center gap-2">
                    <img src="/mario-bramy/assets/images/logo.png" alt="Mario Bramy" height="50" loading="lazy">
                    <span class="font-bold text-xl text-gray-900 hidden sm:inline">Mario Bramy</span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <?php
            // Determine shop link target from settings (fallback to 'wkrotce')
            include_once __DIR__ . '/../config/settings.php';
            $shop_target = get_setting('shop_target', 'wkrotce');
            $shop_href = ($shop_target === 'sklep') ? '/mario-bramy/public/sklep.php' : '/mario-bramy/public/wkrotce.php';
            ?>
            <ul id="nav-menu" class="hidden md:flex gap-8 items-center">
                        <li><a href="/mario-bramy/public/nasze-projekty.php" class="text-gray-700 hover:text-primary-color transition">Realizacje</a></li>
                        <li><a href="/mario-bramy/public/opinie.php" class="text-gray-700 hover:text-primary-color transition">Opinie</a></li>
                        <li><a href="<?php echo htmlspecialchars($shop_href); ?>" class="text-gray-700 hover:text-primary-color transition">Konfigurator</a></li>
                        <li>
                            <a href="/mario-bramy/public/kontakt.php" class="bg-primary-color text-white px-6 py-2 rounded hover:bg-primary-button-hover-bg-color transition">
                                Kontakt
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com" target="_blank" rel="noopener" class="text-gray-700 hover:text-primary-color">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                    </ul>
            
            <!-- Mobile Hamburger Menu -->
            <button id="hamburger-menu" class="md:hidden text-2xl text-gray-900">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
        
        <!-- Mobile Menu (hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <ul class="flex flex-col gap-4 px-4 py-4">
                <li><a href="/mario-bramy/public/nasze-projekty.php" class="text-gray-700 hover:text-primary-color block py-2">Realizacje</a></li>
                <li><a href="/mario-bramy/public/opinie.php" class="text-gray-700 hover:text-primary-color block py-2">Opinie</a></li>
                <li><a href="<?php echo htmlspecialchars($shop_href); ?>" class="text-gray-700 hover:text-primary-color block py-2">Konfigurator</a></li>
                <li><a href="/mario-bramy/public/kontakt.php" class="text-gray-700 hover:text-primary-color block py-2">Kontakt</a></li>
                <li>
                    <a href="https://www.facebook.com" target="_blank" rel="noopener" class="text-gray-700 hover:text-primary-color">
                        Facebook
                    </a>
                </li>
            </ul>
        </div>
    </header>
    
    <main>
