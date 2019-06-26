<tr>
    <?php foreach( $fields as $name => $config ): ?>
        <?php if( $config['widget'] == 'hiddenInput' ) continue; ?>
        <th><?= $config['title'] ?></th>
    <?php endforeach; ?>

    <?php if ( $add ): ?>
        <th></th>
    <?php endif;?>
</tr>

