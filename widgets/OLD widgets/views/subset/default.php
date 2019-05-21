<?php $id = uniqid('subset') ?>

<table class="table table-striped table-hover table-condensed" id="<?=$id?>">
    <?= $header ?>
    <?php foreach( $rows as $row ): ?>
        <?= $row ?>
    <?php endforeach; ?>
</table>
<?php if ( $add ): ?>
    <script>
        $(function(){
            $('[data-subset-target="<?=$id?>"]').click(function(){
                $('#<?=$id?>').append( <?= json_encode( $empty ) ?> );
            });
        })
    </script>
    <p class="text-right">
        <a class="btn btn-success" data-subset-target="<?=$id?>">Добавить</a>
    </p>

<?php endif;
