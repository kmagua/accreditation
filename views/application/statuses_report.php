<?php
use dosamigos\chartjs\ChartJs;

$this->title = 'Supplier Applications Statuses';
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => [
    '/site/reports']];
$this->params['breadcrumbs'][] = $this->title;


echo ChartJs::widget([
    'type' => 'pie',
    'id' => 'structurePie',
    'options' => [
        'height' => 400,
        'width' => 400,
    ],
    'data' => [
        'radius' =>  "90%",
        'labels' => $labels, // Your labels
        'datasets' => [
            [
                'data' => $data_percentage, // Your dataset
                'label' => '',
                'backgroundColor' => [
                    '#ADC3FF',
                    'yellow',
                    'rgba(190, 124, 145, 0.8)',
                    'red',
                    'green',
                    //'rgba(190, 124, 145, 0.8)',
                    'rgba(124, 12, 145, 0.8)',
                    'rgba(190, 20, 145, 0.8)',
                    'rgba(230, 180, 85, 0.7)',
                    'rgba(23, 124, 32, 0.4)',
                    'rgba(190, 235, 20, 0.4)',
                    'rgba(202, 82, 145, 0.6)',
                    'rgba(102, 40, 235, 0.8)',
                    'rgba(245, 22, 80, 0.7)',
                    'rgba(30, 50, 190, 0.8)',
                    'rgba(20, 150, 145, 0.2)',
                    'rgba(100, 122, 145, 0.8)',
                    'rgba(190, 180, 230, 0.6)',
                    'rgba(180, 240, 32, 0.8)',
                    'rgba(220, 124, 230, 0.5)',
                ],
                /*'borderColor' =>  [
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff',
                    '#fff'
                ],*/
                'borderWidth' => 0,
                'hoverBorderColor'=>["#999","#999","#999"],                
            ]
        ]
    ],
    'clientOptions' => [
        'legend' => [
            'display' => true,
            'position' => 'right',
            'labels' => [
                'fontSize' => 12,
                'fontColor' => "#425062",
            ]
        ],
        'tooltips' => [
            'enabled' => true,
            'intersect' => true
        ],
        'hover' => [
            'mode' => false
        ],
        'maintainAspectRatio' => false,

    ],
    'plugins' =>
        new \yii\web\JsExpression("
        [{
            afterDatasetsDraw: function(chart, easing) {
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(0, 0, 0)';

                            var fontSize = 16;
                            var fontStyle = 'normal';
                            var fontFamily = 'Helvetica';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString()+'%';

                            // Make sure alignment settings are correct
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        }]")
]);