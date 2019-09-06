<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/?<?= buildFilterLinkUrl('all');?>" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/?<?= buildFilterLinkUrl('today');?>" class="tasks-switch__item">Повестка дня</a>
            <a href="/?<?= buildFilterLinkUrl('tomorrow');?>" class="tasks-switch__item">Завтра</a>
            <a href="/?<?= buildFilterLinkUrl('bad');?>" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $show_complete_tasks === 1 ? 'checked' : ''
            /* добавлен атрибут "checked", если переменная $show_complete_tasks равна единице */ ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>
    <?=$content;?>
</main>
