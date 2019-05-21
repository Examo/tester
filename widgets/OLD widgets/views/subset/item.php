<tr>
    <?php foreach( $fields as $name => $config ): ?>
        <?php
            $field = $form->field($model, $name)->label(false);

            $params = $config['params'];
            if ( !count($params) ) {
                $params = [[]];
            }

            switch ( $config['widget'] ) {
                case 'checkboxList':
                case 'dropDownList':
                case 'input':
                case 'label':
                case 'listBox':
                case 'radioList':
                case 'widget':
                    $attributesParam = 1;
                    break;
                default:
                    $attributesParam = 0;
            }

            if ( !isset($params[$attributesParam]) || (isset($params[$attributesParam]) && is_array($params[$attributesParam])) ) {
                $params[$attributesParam]['name'] = \yii\helpers\Html::getInputName($model, $name) . '[]';
            }
        ?>
        <?php if( $config['widget'] == 'hiddenInput' ): ?>
            <td class="hidden"><?= call_user_func_array( [$field, $config['widget']], $params ); ?></td>
        <?php else: ?>
            <td><?= call_user_func_array( [$field, $config['widget']], $params ); ?></td>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ( $add ): ?>
        <td class="text-right">
            <a class="btn btn-danger" onclick="if(confirm('Удалить элемент?')) $(this).closest('tr').remove()">Удалить</a>
        </td>
    <?php endif;?>
</tr>