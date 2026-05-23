<?php

/** @var array<string, mixed> $result */
$result = $result ?? [];
$values = $result['values'] ?? [];
$errors = $result['errors'] ?? [];
?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy reveal">
            <span class="eyebrow">
                <i data-lucide="sparkles"></i>
                Премиальный брокерский сервис
            </span>
            <h1>Ваш персональный брокер для умных финансовых решений</h1>
            <p>
                Персональное сопровождение, прозрачные условия и быстрое подключение.
                Оставьте заявку за минуту и начните путь к новым возможностям.
            </p>

            <ul class="hero-features">
                <li><i data-lucide="clock-3"></i> Быстрая регистрация</li>
                <li><i data-lucide="headphones"></i> Личный менеджер</li>
                <li><i data-lucide="line-chart"></i> Аналитика рынков</li>
            </ul>
        </div>

        <div class="card form-card reveal delay-1" id="lead-form">
            <div class="card-header">
                <div class="card-icon">
                    <i data-lucide="send"></i>
                </div>
                <div>
                    <h2>Оставить заявку</h2>
                    <p>Заполните обязательные поля ниже</p>
                </div>
            </div>

            <?php if (!empty($errors['form'])): ?>
                <div class="alert alert-error" role="alert">
                    <i data-lucide="alert-circle"></i>
                    <div>
                        <strong>Ошибка отправки</strong>
                        <p><?= htmlspecialchars((string) $errors['form'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" action="/" class="lead-form" novalidate>
                <div class="form-row">
                    <label class="field">
                        <span>Имя *</span>
                        <div class="input-wrap">
                            <i data-lucide="user"></i>
                            <input
                                type="text"
                                name="firstName"
                                value="<?= htmlspecialchars((string) ($values['firstName'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Иван"
                                required
                                autocomplete="given-name"
                            >
                        </div>
                        <?php if (!empty($errors['firstName'])): ?>
                            <small class="field-error"><?= htmlspecialchars((string) $errors['firstName'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </label>

                    <label class="field">
                        <span>Фамилия *</span>
                        <div class="input-wrap">
                            <i data-lucide="user"></i>
                            <input
                                type="text"
                                name="lastName"
                                value="<?= htmlspecialchars((string) ($values['lastName'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Иванов"
                                required
                                autocomplete="family-name"
                            >
                        </div>
                        <?php if (!empty($errors['lastName'])): ?>
                            <small class="field-error"><?= htmlspecialchars((string) $errors['lastName'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </label>
                </div>

                <label class="field">
                    <span>Эл. почта *</span>
                    <div class="input-wrap">
                        <i data-lucide="mail"></i>
                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars((string) ($values['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="ivan@pochta.ru"
                            required
                            autocomplete="email"
                        >
                    </div>
                    <?php if (!empty($errors['email'])): ?>
                        <small class="field-error"><?= htmlspecialchars((string) $errors['email'], ENT_QUOTES, 'UTF-8') ?></small>
                    <?php endif; ?>
                </label>

                <label class="field">
                    <span>Телефон *</span>
                    <div class="phone-field">
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?= htmlspecialchars((string) ($values['phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required
                            autocomplete="tel"
                        >
                    </div>
                    <?php if (!empty($errors['phone'])): ?>
                        <small class="field-error"><?= htmlspecialchars((string) $errors['phone'], ENT_QUOTES, 'UTF-8') ?></small>
                    <?php endif; ?>
                </label>

                <button type="submit" class="btn btn-primary">
                    <span>Отправить заявку</span>
                    <i data-lucide="arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<section class="stats-strip">
    <div class="container stats-grid">
        <article class="stat-card reveal">
            <i data-lucide="users"></i>
            <strong>24/7</strong>
            <span>Поддержка клиентов</span>
        </article>
        <article class="stat-card reveal delay-1">
            <i data-lucide="badge-check"></i>
            <strong>100%</strong>
            <span>Прозрачные условия</span>
        </article>
        <article class="stat-card reveal delay-2">
            <i data-lucide="zap"></i>
            <strong>Быстро</strong>
            <span>Открытие счёта</span>
        </article>
        <article class="stat-card reveal delay-1">
            <i data-lucide="landmark"></i>
            <strong>150+</strong>
            <span>Инструментов</span>
        </article>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="section-head reveal centered">
            <span class="eyebrow"><i data-lucide="briefcase"></i> Наши направления</span>
            <h2>Чем может помочь персональный брокер</h2>
            <p>Мы сопровождаем клиентов на каждом этапе — от первой консультации до регулярной работы с портфелем.</p>
        </div>

        <div class="features-grid">
            <article class="feature-card reveal">
                <div class="feature-icon"><i data-lucide="bar-chart-3"></i></div>
                <h3>Торговля на рынках</h3>
                <p>Акции, индексы, сырьё и валюты — доступ к ликвидным инструментам с понятной структурой комиссий.</p>
            </article>
            <article class="feature-card reveal delay-1">
                <div class="feature-icon"><i data-lucide="wallet"></i></div>
                <h3>Управление капиталом</h3>
                <p>Помогаем выстроить стратегию под ваши цели: сохранение, рост или диверсификация рисков.</p>
            </article>
            <article class="feature-card reveal delay-2">
                <div class="feature-icon"><i data-lucide="newspaper"></i></div>
                <h3>Аналитика и обзоры</h3>
                <p>Ежедневные рыночные сводки, ключевые уровни и идеи от команды аналитиков.</p>
            </article>
            <article class="feature-card reveal">
                <div class="feature-icon"><i data-lucide="graduation-cap"></i></div>
                <h3>Обучение</h3>
                <p>Материалы для новичков и продвинутых: от базовых понятий до практики управления рисками.</p>
            </article>
            <article class="feature-card reveal delay-1">
                <div class="feature-icon"><i data-lucide="shield"></i></div>
                <h3>Безопасность</h3>
                <p>Защита данных, прозрачные процедуры верификации и контроль операций на каждом шаге.</p>
            </article>
            <article class="feature-card reveal delay-2">
                <div class="feature-icon"><i data-lucide="messages-square"></i></div>
                <h3>Личный менеджер</h3>
                <p>Один специалист ведёт вашу заявку, отвечает на вопросы и помогает с подключением.</p>
            </article>
        </div>
    </div>
</section>

<section class="content-section content-section-alt">
    <div class="container">
        <div class="section-head reveal centered">
            <span class="eyebrow"><i data-lucide="route"></i> Как это работает</span>
            <h2>4 простых шага до старта</h2>
            <p>Минимум бюрократии — максимум внимания к вашим задачам.</p>
        </div>

        <ol class="steps-list">
            <li class="step-item reveal">
                <span class="step-num">01</span>
                <div>
                    <h3>Оставьте заявку</h3>
                    <p>Заполните форму на сайте — нам нужны только базовые контактные данные.</p>
                </div>
            </li>
            <li class="step-item reveal delay-1">
                <span class="step-num">02</span>
                <div>
                    <h3>Подтверждение</h3>
                    <p>Менеджер свяжется с вами, уточнит цели и ответит на вопросы по условиям.</p>
                </div>
            </li>
            <li class="step-item reveal delay-2">
                <span class="step-num">03</span>
                <div>
                    <h3>Верификация</h3>
                    <p>Проходите стандартную проверку документов — быстро и в защищённом формате.</p>
                </div>
            </li>
            <li class="step-item reveal">
                <span class="step-num">04</span>
                <div>
                    <h3>Начало работы</h3>
                    <p>Получаете доступ к платформе, аналитике и поддержке персонального брокера.</p>
                </div>
            </li>
        </ol>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="split-block reveal">
            <div class="split-copy">
                <span class="eyebrow"><i data-lucide="star"></i> Почему мы</span>
                <h2>Брокерский сервис, ориентированный на клиента</h2>
                <p>
                    Мы не просто принимаем заявки — мы выстраиваем долгосрочные отношения.
                    Каждый клиент получает понятные условия, человеческую поддержку и инструменты
                    для принятия взвешенных решений.
                </p>
                <ul class="check-list">
                    <li><i data-lucide="check-circle-2"></i> Без скрытых комиссий в базовом тарифе</li>
                    <li><i data-lucide="check-circle-2"></i> Быстрый ответ службы поддержки</li>
                    <li><i data-lucide="check-circle-2"></i> Удобный личный кабинет и мобильный доступ</li>
                    <li><i data-lucide="check-circle-2"></i> Регулярные обновления по вашим заявкам</li>
                </ul>
            </div>
            <div class="split-panel card">
                <div class="quote-block">
                    <i data-lucide="quote"></i>
                    <p>
                        «Персональный брокер помог разобраться с первых дней.
                        Всё прозрачно, менеджер всегда на связи — именно такого сервиса я и ждал.»
                    </p>
                    <footer>
                        <strong>Алексей М.</strong>
                        <span>Клиент с 2024 года</span>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-section content-section-alt">
    <div class="container">
        <div class="section-head reveal centered">
            <span class="eyebrow"><i data-lucide="help-circle"></i> FAQ</span>
            <h2>Частые вопросы</h2>
            <p>Коротко о том, что важно знать перед отправкой заявки.</p>
        </div>

        <div class="faq-list">
            <details class="faq-item reveal" open>
                <summary>Сколько времени занимает обработка заявки?</summary>
                <p>Обычно менеджер связывается в течение 15–30 минут в рабочее время. Заявки, отправленные ночью, обрабатываются утром следующего дня.</p>
            </details>
            <details class="faq-item reveal delay-1">
                <summary>Какие документы понадобятся?</summary>
                <p>На старте достаточно контактных данных. Для полного подключения потребуется документ, удостоверяющий личность, и подтверждение адреса — список уточнит менеджер.</p>
            </details>
            <details class="faq-item reveal delay-2">
                <summary>Есть ли минимальная сумма для начала?</summary>
                <p>Условия зависят от выбранного направления. После заявки менеджер предложит подходящий тариф и минимальный порог входа под ваши цели.</p>
            </details>
            <details class="faq-item reveal">
                <summary>Как проверить статус моей заявки?</summary>
                <p>На странице «Статусы лидов» можно посмотреть текущий статус обработки по email и ID. Данные обновляются из CRM в реальном времени.</p>
            </details>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-card reveal">
            <div>
                <h2>Готовы начать?</h2>
                <p>Оставьте заявку сейчас — персональный брокер уже ждёт вашего обращения.</p>
            </div>
            <a href="#" class="btn btn-primary scroll-to-form">
                <span>Заполнить форму</span>
                <i data-lucide="arrow-up"></i>
            </a>
        </div>
    </div>
</section>
