<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/6/2017
 * Time: 2:56 PM
 */

namespace humhub\libs;


use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\log\Logger;

class CheckGridColumn extends DataColumn
{
    /**
     * @var array list of attributes which should be aditionally submitted (e.g. id)
     */
    public $submitAttributes = ['id'];
    /**
     * @var array html options
     */
    public $htmlOptions = [];

    /**
     * @var array ajax options
     */
    public $ajaxOptions = array();

    public function init()
    {

        if (!isset($this->ajaxOptions['type'])) {
            $this->ajaxOptions['type'] = 'POST';
        }
        $this->ajaxOptions['data'] = new \yii\web\JsExpression('data');

        $this->grid->view->registerJs("$('.checkCell').click(function() {
                data = {};
                data[$(this).attr('name')] = ($(this).val() == 1)? 0 : 1;
                submitAttributes = $(this).data('submit-attributes').split(',');
               
                for (var i in submitAttributes) {
                    data[submitAttributes[i]] = $(this).data('attribute'+i);
                }
                data['checkSubmit'] = true;
                $.ajax(" . \yii\helpers\Json::encode($this->ajaxOptions) . ");
        });");

        return parent::init();
    }

    public function renderDataCellContent($model, $key, $index)
    {

        if (isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] .= 'checkCell';
        } else {
            $this->htmlOptions['class'] = 'checkCell';
        }

        $this->htmlOptions['data-submit-attributes'] = implode(',', $this->submitAttributes);
        $i = 0;
        foreach ($this->submitAttributes as $attribute) {
            $this->htmlOptions['data-attribute' . $i] = $model[$attribute];
            $i++;
        }

        $inputName = (is_array($model)) ? $this->attribute : Html::getInputName($model, $this->attribute);
        $checked = ($model[$this->attribute] == 1)? true: false;

        return Html::checkbox($inputName, $checked, $this->htmlOptions);
    }
}