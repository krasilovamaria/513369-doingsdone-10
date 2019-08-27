<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $item) : /*добавляет список проектов из массива $projects*/ ?>
            <li class="main-navigation__list-item<?= getProjectsMenuActiveItemClass($item['project_id']) /*выделяет активный проект*/ ?>">
                <a class="main-navigation__list-item-link" href="index.php?project_id=<?= $item['id']; /*добавляет адрес ссылки*/ ?>">
                    <?= htmlspecialchars($item['name']);
                        /* htmlspecialchars фильтрует данные, для защиты от XSS */ ?></a>
                <span class="main-navigation__list-item-count">
                    <?= htmlspecialchars($item['projects_count']); /*подсчет задач через SQL*/ ?>
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="index.html" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input
            <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>"
            type="text" name="name" id="name" value="" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <?php foreach ($projects as $item) : /*добавляет список проектов из массива $projects*/ ?>
                <option value=""><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
