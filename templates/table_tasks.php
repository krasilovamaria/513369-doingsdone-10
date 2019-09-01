<table class="tasks">
    <?php foreach ($tasks as $item) : /*добавляет список задач из массива $tasks*/ ?>
    <?php if ($item['status'] === '0' || ($item['status'] === '1' && $show_complete_tasks === 1)) :
            /*условие для отображения задач из массива $tasks тех что выполнены и невыполнены*/ ?>
    <tr class="tasks__item task <?= $item['status'] === "0" ?: 'task--completed' /*добавляет класс task--completed*/ ?>
        <?= is_date_important($item['deadline']) ? 'task--important' : '' /*добавляет класс task--important*/ ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?= $item['status'] === '1' ? ' checked' : ''
                /*добавляет атрибут "checked"*/ ?>>
                <span class="checkbox__text"><?= htmlspecialchars($item['name']); ?></span>
            </label>
        </td>

        <td class="task__file">
        <?php if(isset($item['file'])) : ?>
            <a class="download-link" href="/uploads/<?= $item['file'];?>">file</a>
        <?php endif; ?>
        </td>

        <td class="task__date">
            <?php if (isset($item['deadline'])) :/*проверяет на null*/ ?>
            <?= htmlspecialchars(date('d.m.Y', strtotime(($item['deadline'])))) ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
</table>
