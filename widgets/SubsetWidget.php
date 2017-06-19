<?php

namespace app\widgets;

use yii\base\Exception;
use yii\widgets\ActiveForm;
use yii\widgets\InputWidget;

/**
 * Subset Widget
 * Allows you to edit many-to-many related child models
 *
 * Usage example:
 *      $form->field($model, 'many-to-many-relation-name')->widget(SubsetWidget::className(), [
 *          'form' => $form,                                // ActiveForm instance
 *          'child' => ManyToManyChildClass::className(),   // Relation Child class
 *          'fields' => [
 *              'id' => 'hiddenInput',
 *              'some-attribute' => 'textInput',
 *              'has-one-relation' => ['dropDownList',[QuestionType::getList()]],
 *          ]
 *      ]);
 *
 * @package app\widgets
 */
class SubsetWidget extends InputWidget
{

    /**
     * @var string Base template
     */
    public $template = 'subset/default';

    /**
     * @var string Header template
     */
    public $header = 'subset/header';

    /**
     * @var string Item template
     */
    public $item = 'subset/item';

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var string Child entity class
     */
    public $child;

    /**
     * @var bool Enable items add/remove
     */
    public $add = true;

    /**
     * @var array Editable fields config
     */
    public $fields = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->prepareFields();

        echo $this->render($this->template, [
            'model' => new $this->child,
            'header' => $this->renderHeader(),
            'rows' => $this->renderRows(),
            'empty' => $this->render($this->item, [
                'model' => new $this->child,
                'fields' => $this->fields,
                'form' => $this->form,
                'add' => $this->add,
            ]),
            'add' => $this->add,
            'form' => $this->form
        ]);
    }

    /**
     * Load fields config
     * @throws Exception Wrong fields config
     */
    private function prepareFields()
    {
        $attributes = (new $this->child)->attributeLabels();

        if (!count($this->fields)) {
            $this->fields = array_keys($attributes);
        }

        $fields = [];
        foreach ($this->fields as $name => $params) {
            if (is_numeric($name) && is_string($params)) {
                $fields[$params] = [
                    'widget' => 'textInput',
                    'params' => [],
                    'title' => isset($attributes[$params]) ? $attributes[$params] : $params
                ];
            } elseif (is_string($name) && is_string($params)) {
                $fields[$name] = [
                    'widget' => $params,
                    'params' => [],
                    'title' => isset($attributes[$name]) ? $attributes[$name] : $name
                ];
            } elseif (is_string($name) && is_array($params) && count($params)) {
                $fields[$name] = [
                    'widget' => $params[0],
                    'params' => count($params) > 1 ? $params[1] : [],
                    'title' => isset($attributes[$name]) ? $attributes[$name] : $name
                ];
            } else {
                throw new Exception('Wrong fields config in SubsetWidget');
            }
        }

        $this->fields = $fields;
    }

    /**
     * Render table rows
     * @return array
     */
    private function renderRows()
    {
        $data = $this->model->{'get' . ucfirst($this->attribute)}()->all();

        $rows = [];
        foreach ($data as $subitem) {
            $rows[] = $this->render($this->item, [
                'model' => $subitem,
                'fields' => $this->fields,
                'form' => $this->form,
                'add' => $this->add,
            ]);
        }

        return $rows;
    }

    /**
     * Render table header
     * @return string
     */
    private function renderHeader()
    {
        return $this->render($this->header, [
            'model' => new $this->child,
            'fields' => $this->fields,
            'form' => $this->form,
            'add' => $this->add,
        ]);
    }

}