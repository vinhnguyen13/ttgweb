<?php
$scenario_1 = empty($data["scenario_1"]) == false ? $data["scenario_1"] : [];
$data_1 = [];
if(!empty($scenario_1)){
    foreach($scenario_1 as $key => $value){
        $data_1[] = $value[0];
    }
}

$scenario_2 = empty($data["scenario_2"]) == false ? $data["scenario_2"] : [];
$data_2 = [];
if(!empty($scenario_2)) {
    foreach ($scenario_2 as $key => $value) {
        $data_2[] = $value[0];
    }
}
$categories = array_merge($scenario_1, $scenario_2);
$months = array_keys($categories);
?>
<div class="row main_content">
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>

<div class="row cash-flow-data">
    <h2 class="text-center"><?=strtoupper('Cash flow data')?> </h2>
    <div class="table-responsive">
        <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <?php
            foreach($data as $k => $d){
                if(!empty($k)) {
                    echo "<th>" . strtoupper($k) . "</th>";
                }
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php

        for($i = 0; $i < count($months); $i++){
            ?>
            <tr>
                <th><?=$months[$i] ?></th>
                <?php
                if(count($scenario_1) > 0) {
                    if (empty($data_1[$i]) == false) {
                        ?>
                        <td><?= number_format($data_1[$i], 0, ".", ",") ?></td>
                    <?php } else { ?>
                        <td></td>
                    <?php }
                }?>

                <?php
                if(count($scenario_2) > 0) {
                    if (empty($data_2[$i]) == false) {
                        ?>
                        <td><?= number_format($data_2[$i], 0, ".", ",") ?></td>
                    <?php } else { ?>
                        <td></td>
                    <?php }
                }?>
            </tr>
            <?php
        } ?>
        </tbody>
    </table>
        <button class="btn btn-primary" onclick="goBack()">Go Back</button>
    </div>
</div>

<script>
    $(function () {
        chart.themes();
        $('#container').highcharts({
            title: {
                text: 'Timeline Money Chart',
                x: -20 //center
            },
            subtitle: {
                text: 'trungthuygroup.vn',
                x: -20
            },
            xAxis: {
                categories: <?= \yii\helpers\Json::encode($months)?>
            },
            yAxis: {
                title: {
                    text: 'Money $'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' VND'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            scrollbar: {
                enabled: true
            },
            series: [{
                name: 'Scenario 1',
                color: '#5698D3',
                data: <?=json_encode($data_1, JSON_NUMERIC_CHECK )?>
            }, {
                name: 'Scenario 2',
                color: '#EE863F',
                data: <?=json_encode($data_2, JSON_NUMERIC_CHECK )?>
            }]
        });
    });

    function goBack() {
        window.history.back();
    }

</script>
