<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $item) : /*добавляет список проектов из массива $projects*/ ?>
            <li class="main-navigation__list-item<?= getProjectsMenuActiveItemClass($item['project_id']) /*выделяет активный проект*/?>">
                <a class="main-navigation__list-item-link" href="index.php?project_id=<?=$item['id']; /*добавляет адрес ссылки*/?>">
                <?= htmlspecialchars($item['name']);
                /* htmlspecialchars фильтрует данные, для защиты от XSS */ ?></a>
                <span class="main-navigation__list-item-count">
                    <?= htmlspecialchars($item['projects_count']); /*подсчет задач через SQL*/ ?>
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $show_complete_tasks === 1 ? 'checked' : ''
            /* добавлен атрибут "checked", если переменная $show_complete_tasks равна единице */ ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>
    <?= $content ?>
</main>
