/**
 * Google Reviews Fetcher and Renderer
 * Fetches reviews from local backend proxy and renders them.
 */

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('google-reviews-container');
    if (!container) return; // Exit if container doesn't exist

    // Show loading state
    container.innerHTML = `
        <div class="text-center py-10">
            <i class="fas fa-spinner fa-spin text-4xl text-[var(--primary-color)] mb-4"></i>
            <p class="text-[var(--gray-text-color)]">Ładowanie opinii...</p>
        </div>
    `;

    fetch('api/fetch_reviews.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'OK' && data.result && data.result.reviews) {
                renderReviews(data.result.reviews);
                updateRatingHeader(data.result.rating, data.result.user_ratings_total);
            } else if (data.error) {
                showError(`Konfiguracja wymagana: ${data.error}`);
            } else {
                showError('Nie udało się pobrać opinii. Sprawdź konfigurację API.');
                console.error('Google Places API Response:', data);
            }
        })
        .catch(error => {
            showError('Wystąpił błąd podczas ładowania opinii.');
            console.error('Fetch error:', error);
        });

    function renderReviews(reviews) {
        container.innerHTML = ''; // Clear loading state

        // Sort reviews by rating (descending) or date if preferred. Google sends 'most relevant' by default.
        // Let's stick with default order or limit to top 5.
        const limitedReviews = reviews.slice(0, 5);

        limitedReviews.forEach(review => {
            const reviewEl = document.createElement('div');
            reviewEl.className = 'bg-white border border-gray-200 p-6 rounded-xl shadow-sm transition-transform hover:scale-[1.01] duration-300';

            const starsHtml = generateStars(review.rating);
            const relativeTime = review.relative_time_description; // e.g. "a month ago" -> PL translation needed usually comes from API if language=pl is set

            const fallbackAvatar = "https://ui-avatars.com/api/?name=" + encodeURIComponent(review.author_name) + "&background=random";

            reviewEl.innerHTML = `
                <div class="flex items-start">
                    <img
                        class="w-12 h-12 rounded-full mr-4 object-cover"
                        src="${review.profile_photo_url}"
                        alt="${review.author_name}"
                        onerror="this.onerror=null; this.src='${fallbackAvatar}'" 
                    />
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-lg text-[var(--dark-text-color)]">
                                    ${review.author_name}
                                </h4>
                                <p class="text-sm text-[var(--gray-text-color)]">
                                    ${relativeTime}
                                </p>
                            </div>
                            <a href="${review.author_url}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-google text-2xl text-gray-400 hover:text-[var(--primary-color)] transition-colors"></i>
                            </a>
                        </div>
                        <div class="flex items-center my-2">
                           ${starsHtml}
                        </div>
                        <p class="text-[var(--dark-text-color)] leading-relaxed text-sm sm:text-base">
                            ${review.text}
                        </p>
                    </div>
                </div>
            `;
            container.appendChild(reviewEl);
        });
    }

    function updateRatingHeader(rating, total) {
        // Optional: Update a header element with total rating if it exists
        // For now, we just log it or maybe update a specific element if you want to add one later
        // e.g. "4.9/5 (120 opinii)"
    }

    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star text-yellow-400 text-sm"></i>';
            } else if (i - 0.5 <= rating) {
                stars += '<i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>';
            } else {
                stars += '<i class="far fa-star text-gray-300 text-sm"></i>';
            }
        }
        return stars;
    }

    function showError(message) {
        container.innerHTML = `
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <i class="fas fa-exclamation-circle text-2xl text-gray-400 mb-2"></i>
                <p class="text-[var(--gray-text-color)]">${message}</p>
            </div>
        `;
    }
});
