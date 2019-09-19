<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input
            <?php if (isset($errors['name'])) : ?> form__input--error <?php endif /* добавляет класс form__input--error если поле пустое*/ ?>" type="text" name="name" id="name" value="<?= $_POST['name'] ?? "" ?>" placeholder="Введите название">
            <?php if (isset($errors['name'])) : ?><p class="form__message"><?= $errors['name'] ?></p><?php endif /* добавляет в контейнер поля новый тег p.form__message*/ ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?php if (isset($errors['project'])) : ?>form__input--error<?php endif ?>" name="project" id="project">
                <?php foreach ($projects as $item) : /*добавляет список проектов из массива $projects*/ ?>
                    <option value="<?php echo $item['id'] ?>"><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['project'])) : ?><p class="form__message"><?= $errors['project'] ?></p><?php endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date<?php if (isset($errors['date'])) : ?>
                form__input--error <?php endif ?>" type="text" name="date" id="date" value="<?= $_POST['date'] ?? "" ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?php if (isset($errors['date'])) : ?><p class="form__message"><?= $errors['date'] ?></p> <?php endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="<?= $_POST['file'] ?? "" ?>">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
                <?php if (isset($errors['file'])) : ?><p class="form__message"><?= $errors['file'] ?></p> <?php endif ?>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
