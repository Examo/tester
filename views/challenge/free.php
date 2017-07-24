<?php if( count($challenges) ): ?>

    <?php foreach( $challenges as $challenge ): ?>

        <?= $this->render( '_item', ['challenge' => $challenge] ) ?>

    <?php endforeach; ?>

<?php else: ?>

    Хм, сейчас в системе нет тестов, доступных для прохождения без регистрации :(

<?php endif; ?>
