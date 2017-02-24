<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 12/30/2016
 * Time: 9:21 AM
 */

namespace humhub\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\log\Logger;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;
use Yii;



class GoogleChart extends Widget
{
    public $message;
    /**
     * @var string $containerId the container Id to render the visualization to
     */
    public $containerId;
    public $dashboardId;
    public $filterId;

    /**
     * @var string $visualization the type of visualization -ie PieChart
     * @see https://google-developers.appspot.com/chart/interactive/docs/gallery
     */
    public $visualization;

    /**
     * @var string $packages the type of packages, default is corechart
     * @see https://google-developers.appspot.com/chart/interactive/docs/gallery
     */
    public $packages = '"corechart"';  // such as 'orgchart' and so on.

    public $loadVersion = "1.1"; //such as 1 or 1.1  Calendar chart use 1.1.  Add at Sep 16

    /**
     * @var array $data the data to configure visualization
     * @see https://google-developers.appspot.com/chart/interactive/docs/datatables_dataviews#arraytodatatable
     */
    public $data = array();

    /**
     * @var array $options additional configuration options
     * @see https://google-developers.appspot.com/chart/interactive/docs/customizing_charts
     */
    public $options = array();

    /**
     * @var string $scriptAfterArrayToDataTable additional javascript to execute after arrayToDataTable is called
     */
    public $scriptAfterArrayToDataTable = '';

    /**
     * @var array $htmlOption the HTML tag attributes configuration
     */
    public $htmlOptions = array();

    public $ControlType = '';
    public $ControlOptions = array();

    public $addColumn = '';

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Hello World';
        }
    }

    public function run()
    {

        $id = $this->getId();
        if (isset($this->options['id']) and !empty($this->options['id'])) $id = $this->options['id'];
        // if no container is set, it will create one
        if ($this->containerId == null) {
            $this->htmlOptions['id'] = 'div-chart' . $id;
            $this->containerId = $this->htmlOptions['id'];
            $this->dashboardId = 'div-dashboard' . $id;
            $this->filterId = 'div-filter' . $id;
            echo '<div id = ' . $this->dashboardId . '>
                    <div id = ' . $this->filterId . '></div>
                    <div ' . Html::renderTagAttributes($this->htmlOptions) . '></div>
                </div>';
        }
        $this->registerClientScript($id);
        //return Html::encode($this->message);
    }

    /**
     * Registers required scripts
     */
    public function registerClientScript($id)
    {

        $jsData = Json::encode($this->data);
        $jsOptions = Json::encode($this->options);



//        \Yii::getLogger()->log(print_r($this->packages, true), Logger::LEVEL_INFO, 'MyLog');
//        \Yii::getLogger()->log(print_r($jsOptions, true), Logger::LEVEL_INFO, 'MyLog');

//        $script = '
//			google.charts.setOnLoadCallback(drawChart' . $id . ');
//			var ' . $id . '=null;
//			function drawChart' . $id . '() {
//				var data = google.visualization.arrayToDataTable(' . $jsData . ');
//
//				' . $this->scriptAfterArrayToDataTable . '
//
//				var options = ' . $jsOptions . ';
//
//				' . $id . ' = new google.visualization.' . $this->visualization . '(document.getElementById("' . $this->containerId . '"));
//				' . $id . '.draw(data, options);
//			}';
        if ($this->ControlType != ''){
            $jsControl = Json::encode($this->ControlOptions);
            $script = '
            google.charts.setOnLoadCallback(drawChart' . $id . ');
            function drawChart' . $id . '() {

                var data = google.visualization.arrayToDataTable(' . $jsData . ');

                var dashboard = new google.visualization.Dashboard(document.getElementById("'.$this->dashboardId.'"));

                var wrapper = new google.visualization.ChartWrapper({
                    chartType: "'. $this->visualization. '",
                    options: '.$jsOptions.',
                    containerId: "'.$this->containerId.'",
                });

                var control = new google.visualization.ControlWrapper({
                    controlType: '. $this->ControlType .',
                    options: '.$jsControl.',
                    containerId: "'.$this->filterId.'",
                });

                dashboard.bind(control, wrapper);
                dashboard.draw(data);

            }';


        } elseif ($this->addColumn == '') {
            $script = '
            google.charts.setOnLoadCallback(drawChart' . $id . ');
            function drawChart' . $id . '() {                
            
                var wrapper = new google.visualization.ChartWrapper({
                    chartType: "'. $this->visualization. '",
                    dataTable: ' .$jsData. ',
                    options: '.$jsOptions.',
                    containerId: "'.$this->containerId.'",
                });

                wrapper.draw();

            }';
        } else {

            $script = '
            var column = '.Json::encode($this->addColumn).';
            var dataRow = '.$jsData.';
            
            google.charts.setOnLoadCallback(drawChart' . $id . ');
            function drawChart' . $id . '() {                
                
                var data = new google.visualization.DataTable();
                for (var i = 0, len = column.length; i < len; i++){
      	            data.addColumn(column[i][0], column[i][1]);
                }
                
                for (var j = 0, rlen = dataRow.length; j < rlen; j++){
                    data.addRow([new Date(dataRow[j][0]), dataRow[j][1]]);
                    
                }
                
                var formatter = new google.visualization.DateFormat({timeZone: 0});
                formatter.format(data, 0);
                
                var options = '.$jsOptions.';
                
                var chart = new google.visualization.'.$this->visualization.'(
                    document.getElementById("' . $this->containerId . '"));

                chart.draw(data, options);
                
            }';
        }



//        if ($this->scriptAfterArrayToDataTable != ''){
//            Yii::getLogger()->log($this->scriptAfterArrayToDataTable, Logger::LEVEL_INFO, 'MyLog');
//        }
//        Yii::getLogger()->log($script, Logger::LEVEL_INFO, 'MyLog');
        $view = $this->getView();
        $view->registerJsFile('https://www.gstatic.com/charts/loader.js',['position' => View::POS_HEAD]);
        $view->registerJs('google.charts.load("current", {packages:[' . $this->packages . ']});', View::POS_HEAD, __CLASS__ . '#' . $id);
        $view->registerJs($script, View::POS_HEAD, $id);
    }
}