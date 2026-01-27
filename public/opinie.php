<?php
/**
 * STRONA 3: opinie.php - Strona opinii klientów
 * Displays: All reviews from database (auto-pulled from Google + manual)
 */

include '../config/database.php';
include '../config/constants.php';

$page_title = 'Opinie Klientów';

// Fetch ALL reviews from database (Google + Manual)
$reviews_query = $db->query("
    SELECT id, author_name, rating, text, source, submitted_at
    FROM reviews 
    WHERE is_visible = 1 
    ORDER BY submitted_at DESC
");

$reviews = [];
if ($reviews_query && $reviews_query->num_rows > 0) {
    while ($row = $reviews_query->fetch_assoc()) {
        $reviews[] = $row;
    }
}

include '../includes/header.php';
?>

        <!-- Page Hero -->
        <section class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold mb-3">Opinie Naszych Klientów</h1>
                <p class="text-gray-300">Przeczytajcie, co mówią nasi zadowoleni klienci o naszych bramach i ogrodzeniach.</p>
            </div>
        </section>

        <!-- Reviews Grid -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <?php if (!empty($reviews)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($reviews as $review): ?>
                            <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
                                <!-- Rating Stars -->
                                <div class="text-yellow-400 text-2xl mb-4 flex items-center gap-2">
                                    <?php 
                                    $rating = intval($review['rating']);
                                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                                    ?>
                                    <span class="text-gray-600 text-sm ml-2">(<?php echo $rating; ?>/5)</span>
                                </div>
                                
                                <!-- Review Text -->
                                <p class="text-gray-700 italic mb-6 leading-relaxed">
                                    "<?php echo htmlspecialchars($review['text']); ?>"
                                </p>
                                
                                <!-- Author -->
                                <p class="font-semibold text-gray-900 mb-2">
                                    — <?php echo htmlspecialchars($review['author_name']); ?>
                                </p>
                                
                                <!-- Date and Source -->
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>
                                        <?php 
                                        $date = new DateTime($review['submitted_at']);
                                        echo $date->format('d.m.Y');
                                        ?>
                                    </span>
                                    <span class="bg-gray-100 px-3 py-1 rounded-full text-xs font-semibold">
                                        <?php echo ($review['source'] === 'google') ? '🔍 Google' : '✍️ Opinia'; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-16">
                        <p class="text-gray-600 text-xl mb-4">Brak opinii do wyświetlenia.</p>
                        <p class="text-gray-500">Bądź pierwszą osobą, która podzieli się swoją opinią!</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">Czym się wyróżniamy?</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-lg shadow">
                        <h3 class="text-xl font-bold mb-3">Doświadczenie</h3>
                        <p class="text-gray-600">
                            Ponad 15 lat na rynku, setki zadowolonych klientów i zrealizowanych projektów.
                        </p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-lg shadow">
                        <h3 class="text-xl font-bold mb-3">Jakość</h3>
                        <p class="text-gray-600">
                            Używamy wyłącznie najwyższej jakości materiały i nowoczesnych technologii.
                        </p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-lg shadow">
                        <h3 class="text-xl font-bold mb-3">Gwarancja</h3>
                        <p class="text-gray-600">
                            10-letnia gwarancja na wszystkie produkty i profesjonalny serwis 24/7.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-primary-color text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-6">Gotowy na swoją bramę?</h2>
                <p class="text-xl mb-8 text-gray-100">Skontaktuj się z nami i uzyskaj bezpłatną wycenę</p>
                
                <a href="/mario-bramy/public/kontakt.php" class="bg-white text-primary-color hover:bg-gray-100 font-bold py-3 px-8 rounded transition inline-block">
                    Napisz do nas
                </a>
            </div>
        </section>

<?php
include '../includes/footer.php';
?>
