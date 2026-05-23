<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Ваш персональный брокер', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/css/intlTelInput.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= (int) @filemtime(dirname(__DIR__) . '/public/assets/css/style.css') ?>">
</head>
<body>
    <div class="bg-gradient"></div>
    <div class="bg-grid"></div>

    <header class="site-header">
        <div class="container header-inner">
            <a href="/" class="brand">
                <span class="brand-icon">
                    <i data-lucide="trending-up"></i>
                </span>
                <span class="brand-text">
                    <strong>Персональный брокер</strong>
                    <small>Ваш надёжный партнёр</small>
                </span>
            </a>

            <nav class="site-nav" aria-label="Основная навигация">
                <a href="/" class="nav-link <?= ($page ?? '') === 'lead-form' ? 'is-active' : '' ?>">
                    <i data-lucide="user-plus"></i>
                    <span>Заявка</span>
                </a>
                <a href="/?page=statuses" class="nav-link <?= ($page ?? '') === 'lead-statuses' ? 'is-active' : '' ?>">
                    <i data-lucide="table-2"></i>
                    <span>Статусы лидов</span>
                </a>
            </nav>

            <button class="nav-toggle" type="button" aria-label="Открыть меню" aria-expanded="false">
                <i data-lucide="menu"></i>
            </button>
        </div>
    </header>

    <main class="site-main">
        <?= $content ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>&copy; <?= date('Y') ?> Персональный брокер. Все права защищены.</p>
            <div class="footer-links">
                <span><i data-lucide="shield-check"></i> Защищённое соединение</span>
                <span><i data-lucide="globe-2"></i> Мировые рынки</span>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@0.469.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/intlTelInput.min.js"></script>
    <script src="/assets/js/app.js?v=<?= (int) @filemtime(dirname(__DIR__) . '/public/assets/js/app.js') ?>"></script>
</body>
</html>
