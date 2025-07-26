<?php

namespace app\models;

class chartGenerator
{
    public static function generateChart($data, $chartType, $canvasId, $chartOptions = [])
    {
        switch ($chartType) {
            case 'bar':
                self::generateBarChart($data, $canvasId, $chartOptions);
                break;
            case 'line':
                self::generateLineChart($data, $canvasId, $chartOptions);
                break;
            case 'doughnut':
                self::generateDoughnutChart($data, $canvasId, $chartOptions);
                break;
            default:
                echo "Unsupported chart type";
        }
    }

    private static function generateBarChart($data, $canvasId, $chartOptions)
    {
        self::startChartScript($canvasId);
        echo "var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: " . json_encode(array_keys($data)) . ",
                datasets: [{
                    label: '" . $chartOptions['label'] . "',
                    data: " . json_encode(array_values($data)) . ",
                    backgroundColor: '" . self::randomColor() . "',
                    borderColor: '" . self::randomColor() . "',
                    borderWidth: 1
                }]
            },
            options: " . json_encode($chartOptions) . "
        });";
        self::endChartScript();
    }

    private static function generateLineChart($data, $canvasId, $chartOptions)
    {
        self::startChartScript($canvasId);
        echo "var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: " . json_encode(array_keys($data)) . ",
                datasets: [{
                    label: '" . $chartOptions['label'] . "',
                    data: " . json_encode(array_values($data)) . ",
                    backgroundColor: '" . self::randomColor() . "',
                    borderColor: '" . self::randomColor() . "',
                    borderWidth: 1
                }]
            },
            options: " . json_encode($chartOptions) . "
        });";
        self::endChartScript();
    }

    private static function generateDoughnutChart($data, $canvasId, $chartOptions)
    {
        self::startChartScript($canvasId);
        echo "var doughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: " . json_encode(array_keys($data)) . ",
                datasets: [{
                    data: " . json_encode(array_values($data)) . ",
                    backgroundColor: '" . self::generateRandomColors(count($data)) . "',
                    borderColor: '" . self::generateRandomColors(count($data)) . "',
                    borderWidth: 1
                }]
            },
            options: " . json_encode($chartOptions) . "
        });";
        self::endChartScript();
    }

    private static function startChartScript($canvasId)
    {
        echo "<script>
            var ctx = document.getElementById('" . $canvasId . "').getContext('2d');";
    }

    private static function endChartScript()
    {
        echo "</script>";
    }

    private static function randomColor()
    {
        return 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 0.7)';
    }

    private static function generateRandomColors($count)
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = self::randomColor();
        }
        return $colors;
    }
}

?>
