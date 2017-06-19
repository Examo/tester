<?php if( count($challenges) ): ?>

    <?php foreach( $challenges as $challenge ): ?>

        <?= $this->render( '_item', ['challenge' => $challenge] ) ?>

    <?php endforeach; ?>

<?php else: ?>

    На данный момент в системе отсутсвуют тесты, доступные для прохождения без регистрации.

<?php endif; ?>
