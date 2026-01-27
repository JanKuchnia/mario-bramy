<?php
/**
 * Admin Settings Page - Toggle shop target
 */
include '../config/database.php';
include '../config/auth.php';
include '../config/security.php';
include '../config/settings.php';

require_login();

$current = get_setting('shop_target', 'wkrotce');
$csrf = generate_csrf_token();

?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ustawienia - Panel Admina</title>
    <link rel="stylesheet" href="admin.css">
  </head>
  <body>
    <div class="panel-container">
      <header class="panel-header">
        <h1>Ustawienia</h1>
      </header>

      <main style="padding:2rem;">
        <form id="shopTargetForm">
          <div>
            <label>
              <input type="radio" name="shop_target" value="wkrotce" <?php echo $current === 'wkrotce' ? 'checked' : ''; ?> />
              Prowadź link "Sklep" do strony "Wkrótce"
            </label>
          </div>
          <div style="margin-top:0.5rem;">
            <label>
              <input type="radio" name="shop_target" value="sklep" <?php echo $current === 'sklep' ? 'checked' : ''; ?> />
              Prowadź link "Sklep" do pełnego konfiguratora
            </label>
          </div>
          <input type="hidden" name="name" value="shop_target" />
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>" />
          <div style="margin-top:1rem;">
            <button type="submit" class="save-btn">Zapisz</button>
          </div>
        </form>

        <div id="result" style="margin-top:1rem; color:green; display:none;"></div>
      </main>
    </div>

    <script>
    document.getElementById('shopTargetForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      try {
        const res = await fetch('/admin/api/settings-update.php', {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        const result = document.getElementById('result');
        if (data.success) {
          result.style.display = 'block';
          result.textContent = 'Zapisano ustawienia.';
        } else {
          result.style.display = 'block';
          result.style.color = 'red';
          result.textContent = data.message || 'Błąd';
        }
      } catch (err) {
        const result = document.getElementById('result');
        result.style.display = 'block';
        result.style.color = 'red';
        result.textContent = 'Błąd komunikacji';
      }
    });
    </script>
  </body>
</html>
