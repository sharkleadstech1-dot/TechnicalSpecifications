<?php

/** @var string $lead_id */
$lead_id = $lead_id ?? '';
?>

<section class="thank-you-section">
    <div class="container">
        <div class="card thank-you-card reveal">
            <div class="thank-you-icon">
                <i data-lucide="circle-check-big"></i>
            </div>

            <span class="eyebrow">
                <i data-lucide="party-popper"></i>
                Заявка принята
            </span>

            <h1>Спасибо за вашу заявку!</h1>
            <p>
                Мы получили ваши данные. Персональный менеджер свяжется с вами в ближайшее время
                для подтверждения деталей и следующих шагов.
            </p>

            <?php if ($lead_id !== ''): ?>
                <div class="lead-id-badge">
                    <i data-lucide="hash"></i>
                    <span>ID лида: <strong>#<?= htmlspecialchars($lead_id, ENT_QUOTES, 'UTF-8') ?></strong></span>
                </div>
            <?php endif; ?>

            <div class="thank-you-actions">
                <a href="/" class="btn btn-primary">
                    <i data-lucide="home"></i>
                    <span>На главную</span>
                </a>
                <a href="/?page=statuses<?= $lead_id !== '' ? '&highlight=' . urlencode($lead_id) : '' ?>" class="btn btn-secondary">
                    <i data-lucide="table-2"></i>
                    <span>Статусы лидов</span>
                </a>
            </div>
        </div>

        <div class="next-steps card reveal delay-1">
            <h2>Что будет дальше</h2>
            <ol class="steps-list steps-list-compact">
                <li class="step-item">
                    <span class="step-num">1</span>
                    <div>
                        <h3>Заявка в системе</h3>
                        <p>Ваши данные переданы в CRM. Вы можете отслеживать статус на странице «Статусы лидов».</p>
                    </div>
                </li>
                <li class="step-item">
                    <span class="step-num">2</span>
                    <div>
                        <h3>Звонок менеджера</h3>
                        <p>Специалист свяжется с вами по указанному телефону или email для уточнения деталей.</p>
                    </div>
                </li>
                <li class="step-item">
                    <span class="step-num">3</span>
                    <div>
                        <h3>Подключение</h3>
                        <p>После верификации вы получите доступ к платформе и инструментам персонального брокера.</p>
                    </div>
                </li>
            </ol>
        </div>
    </div>
</section>

<section class="content-section content-section-alt">
    <div class="container">
        <div class="section-head reveal centered">
            <span class="eyebrow"><i data-lucide="life-buoy"></i> Нужна помощь?</span>
            <h2>Мы рядом, если остались вопросы</h2>
            <p>Пока ждёте звонка менеджера, ознакомьтесь с полезной информацией.</p>
        </div>

        <div class="features-grid features-grid-3">
            <article class="feature-card reveal">
                <div class="feature-icon"><i data-lucide="phone-call"></i></div>
                <h3>Ожидайте звонка</h3>
                <p>Менеджер свяжется в рабочее время. Проверьте, что телефон доступен для входящих.</p>
            </article>
            <article class="feature-card reveal delay-1">
                <div class="feature-icon"><i data-lucide="mail-check"></i></div>
                <h3>Проверьте почту</h3>
                <p>Иногда мы дублируем информацию на email — загляните во «Входящие» и папку «Спам».</p>
            </article>
            <article class="feature-card reveal delay-2">
                <div class="feature-icon"><i data-lucide="file-search"></i></div>
                <h3>Отслеживайте статус</h3>
                <p>На странице статусов лидов можно проверить, на каком этапе находится ваша заявка.</p>
            </article>
        </div>
    </div>
</section>
