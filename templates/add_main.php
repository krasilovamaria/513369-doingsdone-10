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

    <form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input
            <?php if (!empty($errors['name'])) : ?> form__input--error <?php endif /* добавляет класс form__input--error если поле пустое*/?>"
            type="text" name="name" id="name" value="" placeholder="Введите название">
            <?php if (!empty($errors['name'])) : ?> <p class="form__message"><?= $errors['name'] ?></p> <?php endif
            /* добавляет в контейнер поля новый тег p.form__message*/?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?php if (!empty($errors['project'])) : ?>
             form__input--error <?php endif?>" name="project" id="project">
                <?php foreach ($projects as $item) : /*добавляет список проектов из массива $projects*/ ?>
                <option value="<?php $item['id'] ?>"><?= htmlspecialchars($item['name']); ?></option>
                <?php if (!empty($errors['project'])) : ?><p class="form__message"><?= $errors['project'] ?></p><?php endif?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

                <input class="form__input form__input--date<?php if(!empty($errors['date'])) : ?>
                form__input--error <?php endif?>" type="text" name="date" id="date" value="<?= $_POST['date'] ?? "" ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <?php if (!empty($errors['date'])) : ?> <p class="form__message"><?= $errors['date'] ?></p> <?php endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
                <?php if (!empty($errors['file'])) : ?><p class="form__message"><?= $errors['file'] ?></p> <?php endif ?>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
