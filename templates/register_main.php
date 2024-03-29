<h2 class="content__main-heading">Регистрация аккаунта</h2>

<form class="form" action="register.php" method="post" autocomplete="off">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['email'])) : ?>form__input--error<?php endif ?>"
         type="text" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>" placeholder="Введите e-mail">

        <?php if (isset($errors['email'])) : ?><p class="form__message"><?= $errors['email'] ?></p><?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['password'])) : ?>form__input--error<?php endif ?>"
         type="password" name="password" id="password" value="" placeholder="Введите пароль">

        <?php if (isset($errors['password'])) : ?> <p class="form__message"><?= $errors['password'] ?></p> <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="name">Имя <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['name'])) : ?>form__input--error<?php endif ?>"
         type="text" name="name" id="name" value="<?= $_POST['name'] ?? "" ?>" placeholder="Введите имя">

        <?php if (isset($errors['name'])) : ?> <p class="form__message"><?= $errors['name'] ?></p> <?php endif ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if (!empty($errors)) : ?><p class="error-message">Пожалуйста, исправьте ошибки в форме</p><?php endif; ?>

        <input class="button" type="submit" name="" value="Зарегистрироваться">
    </div>
</form>
