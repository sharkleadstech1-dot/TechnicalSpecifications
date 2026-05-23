<?php

/** @var array<string, mixed> $result */
$result = $result ?? [];
$errors = $result['errors'] ?? [];
$leads = $result['leads'] ?? [];
$dateFrom = $result['date_from'] ?? '';
$dateTo = $result['date_to'] ?? '';
$source = ($result['source'] ?? 'all') === 'site' ? 'site' : 'all';
$highlightId = trim((string) ($_GET['highlight'] ?? ''));
?>

<section class="page-header">
    <div class="container reveal">
        <span class="eyebrow">
            <i data-lucide="activity"></i>
            Мониторинг лидов
        </span>
        <h1>Статусы лидов</h1>
        <p>Данные загружаются из CRM через API. Заявки с этого сайта помечены «С сайта».</p>
    </div>
</section>

<section class="statuses-section">
    <div class="container">
        <div class="card filter-card reveal">
            <div class="card-header filter-card-header">
                <div class="card-header-main">
                    <div class="card-icon">
                        <i data-lucide="calendar-range"></i>
                    </div>
                    <div>
                        <h2>Фильтр по дате</h2>
                        <p>Выберите период (не более 60 дней назад)</p>
                    </div>
                </div>
            </div>

            <?php if (!empty($errors['form'])): ?>
                <div class="alert alert-error" role="alert">
                    <i data-lucide="alert-circle"></i>
                    <div>
                        <strong>Не удалось загрузить данные</strong>
                        <p><?= htmlspecialchars((string) $errors['form'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="get" action="/" class="filter-form">
                <input type="hidden" name="page" value="statuses">
                <?php if ($highlightId !== ''): ?>
                    <input type="hidden" name="highlight" value="<?= htmlspecialchars($highlightId, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>

                <div class="filter-fields">
                    <label class="field">
                        <span>Дата с</span>
                        <div class="input-wrap input-wrap-date">
                            <i data-lucide="calendar"></i>
                            <input type="date" name="date_from" value="<?= htmlspecialchars((string) $dateFrom, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <?php if (!empty($errors['date_from'])): ?>
                            <small class="field-error"><?= htmlspecialchars((string) $errors['date_from'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </label>

                    <label class="field">
                        <span>Дата по</span>
                        <div class="input-wrap input-wrap-date">
                            <i data-lucide="calendar"></i>
                            <input type="date" name="date_to" value="<?= htmlspecialchars((string) $dateTo, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <?php if (!empty($errors['date_to'])): ?>
                            <small class="field-error"><?= htmlspecialchars((string) $errors['date_to'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </label>

                    <label class="field field-checkbox">
                        <span>Дополнительно</span>
                        <label class="checkbox-row">
                            <input type="checkbox" name="source" value="site" <?= $source === 'site' ? 'checked' : '' ?>>
                            <span>Только заявки с этого сайта</span>
                        </label>
                    </label>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <span>Применить фильтр</span>
                        <i data-lucide="filter"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card table-card reveal delay-1">
            <div class="table-header">
                <div>
                    <h2>Результаты</h2>
                    <p>Найдено лидов: <?= count($leads) ?></p>
                </div>
                <span class="badge">
                    <i data-lucide="database"></i>
                    <?= $source === 'site' ? 'Только с сайта' : 'Данные из API' ?>
                </span>
            </div>

            <div class="table-wrap">
                <table class="status-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Эл. почта</th>
                            <th>Статус</th>
                            <th>Депозит</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($leads === []): ?>
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i data-lucide="inbox"></i>
                                    <span><?= $source === 'site'
                                        ? 'За выбранный период заявок с сайта не найдено. Отправьте форму на главной странице.'
                                        : 'За выбранный период лиды не найдены.' ?></span>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($leads as $lead): ?>
                                <?php
                                $status = trim($lead['status']);
                                $statusLabel = translate_status($status);
                                $statusClass = $status !== '' ? strtolower($status) : 'unknown';
                                $isHighlighted = $highlightId !== '' && $lead['id'] === $highlightId;
                                $isFromSite = !empty($lead['from_site']);
                                ?>
                                <tr class="<?= $isHighlighted ? 'is-highlighted' : '' ?>">
                                    <td data-label="ID">
                                        <span class="id-cell">
                                            #<?= htmlspecialchars($lead['id'], ENT_QUOTES, 'UTF-8') ?>
                                            <?php if ($isFromSite): ?>
                                                <span class="site-pill">С сайта</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td data-label="Эл. почта"><?= htmlspecialchars($lead['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td data-label="Статус">
                                        <span class="status-pill status-<?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td data-label="Депозит">
                                        <?php if ($lead['ftd'] === '1'): ?>
                                            <span class="ftd-yes"><i data-lucide="check"></i> Да</span>
                                        <?php else: ?>
                                            <span class="ftd-no"><i data-lucide="minus"></i> Нет</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="info-cards reveal delay-1">
            <article class="info-card">
                <div class="info-card-icon"><i data-lucide="info"></i></div>
                <h3>Что означают статусы</h3>
                <ul>
                    <li><strong>Новый</strong> — заявка принята и ожидает обработки</li>
                    <li><strong>В обработке</strong> — менеджер работает с вашим обращением</li>
                    <li><strong>Одобрен</strong> — заявка успешно прошла проверку</li>
                    <li><strong>Отклонён</strong> — заявка не прошла проверку или дубликат</li>
                </ul>
            </article>
            <article class="info-card">
                <div class="info-card-icon"><i data-lucide="coins"></i></div>
                <h3>Колонка «Депозит» (FTD)</h3>
                <p>
                    FTD (First Time Deposit) — первый депозит клиента.
                    «Да» — депозит поступил, «Нет» — ещё не поступил.
                </p>
            </article>
            <article class="info-card">
                <div class="info-card-icon"><i data-lucide="globe"></i></div>
                <h3>Откуда берутся данные</h3>
                <p>
                    Таблица наполняется через API <strong>getstatuses</strong>.
                    Записи вроде <strong>qq@qq.qq</strong> — чужие тестовые лиды из общей CRM.
                    Ваши заявки с <?= htmlspecialchars(landing_url(), ENT_QUOTES, 'UTF-8') ?> помечены «С сайта».
                </p>
            </article>
        </div>
    </div>
</section>
