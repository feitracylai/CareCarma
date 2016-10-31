<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/31/2016
 * Time: 9:57 AM
 */

namespace humhub\modules\user\libs;


use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\log\Logger;

class CircleInviteDropDownColumn extends DataColumn
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
     * @var array dropdown options
     */
    public $dropDownOptions = [];
    /**
     * @var array ajax options
     */
    public $ajaxOptions = array();


    public $selection = array();

    public function init()
    {

        if (!isset($this->ajaxOptions['type'])) {
            $this->ajaxOptions['type'] = 'POST';
        }
        $this->ajaxOptions['data'] = new \yii\web\JsExpression('data');

        $this->grid->view->registerJs("$('.editableCell').change(function() {
                data = {};
                data[$(this).attr('name')] = $(this).val();
                submitAttributes = $(this).data('submit-attributes').split(',');
                for (var i in submitAttributes) {
                    data[submitAttributes[i]] = $(this).data('attribute'+i);
                }
                data['dropDownColumnSubmit'] = true;
                $.ajax(" . \yii\helpers\Json::encode($this->ajaxOptions) . ");
        });");



        return parent::init();
    }


    protected function renderDataCellContent($model, $key, $index)
    {

        if (isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] .= 'editableCell form-control circleInvite';
        } else {
            $this->htmlOptions['class'] = 'editableCell form-control circleInvite';
        }

        // We need to number the submit attributes because data attribute is not case sensitive
        $this->htmlOptions['data-submit-attributes'] = implode(',', $this->submitAttributes);
        $i = 0;
        foreach ($this->submitAttributes as $attribute) {
            $this->htmlOptions['data-attribute' . $i] = $model[$attribute];
            $i++;
        }

        $options = [];
        if (is_array($this->dropDownOptions)) {
            $options = $this->dropDownOptions;
        } else {
            $options = call_user_func($this->dropDownOptions, $model, $key, $index, $this);
        }

        $selection = [];
        if (is_array($this->attribute)){
            $selection = $this->attribute;
        } elseif ($this->attribute == null){
            $selection = call_user_func($this->selection, $model, $key, $index, $this);
        } else {
            $selection = call_user_func($this->attribute, $model, $key, $index, $this);
        }

        $disabled = array();
        $d = 0;
        foreach ($options as $option){

            foreach ($selection as $select){
                if ($select == $option){
                    $disabled[$d] = ['disabled' => true];
                }
            }
            $d++;
        }



        $this->htmlOptions['options'] = $disabled;

        return Html::dropDownList('circle', $selection, $options, $this->htmlOptions);
    }

}