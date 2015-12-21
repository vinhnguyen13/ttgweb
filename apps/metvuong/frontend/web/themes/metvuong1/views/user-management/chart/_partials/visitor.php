<?php
use yii\helpers\Url;
$data = [
    [
        'name' => '21 Lê Thánh Tôn',
        'data' => [
            ['y' => 3,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 12,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 17,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 9,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 11,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 7,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 4,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 3,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 12,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 23,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 34,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 30,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],[
        'name' => '57 Tôn Đản',
        'data' => [
            ['y' => 1,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 2,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 3,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 7,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 14,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 8,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 9,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 10,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 15,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 21,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 25,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],[
        'name' => '23 Pastuer',
        'data' => [
            ['y' => 4,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 15,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 12,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 23,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 19,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 14,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 34,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 31,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 26,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 21,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 11,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],[
        'name' => '11 Nguyễn Văn Trỗi',
        'data' => [
            ['y' => 5,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 18,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 31,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 21,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 26,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 29,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 32,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 12,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 28,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 24,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],
];
?>
<div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    $(function () {
        $('#chartAds').highcharts({
            title: {
                text: 'Lượt người theo dõi tin đăng của bạn',
                x: -20 //center
            },
            subtitle: {
                text: 'Nguồn: MetVuong.com',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Người theo dõi'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' người',
                useHTML:true,
                /*formatter: function() {
                 var tooltip;
                 if (this.key == 'last') {
                 tooltip = '<b>Final result is </b> ' + this.y;
                 }
                 else {
                 tooltip =  '<span style="color:' + this.series.color + '">' + this.series.name + '</span>: <b>' + this.y + '</b><br/>';
                 }
                 return tooltip;
                 }*/
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                console.log(this);
                                $('#frmListVisit .wrap-modal').html('');
                                var timer = 0;
                                clearTimeout(timer);
                                var _this = this;
                                timer = setTimeout(function () {
                                    $.ajax({
                                        type: "get",
                                        dataType: 'html',
                                        url: _this.url,
                                        success: function (data) {
                                            $('#frmListVisit .wrap-modal').html($(data));
                                            $('#frmListVisit').find('.total').html(_this.y);
                                            $('#frmListVisit').find('.news').html(_this.series.name);
                                        }
                                    });
                                }, 500);
                                $('#frmListVisit').modal();
                            }
                        }
                    }
                }
            },
            /*chart: {
             events: {
             click: function(event) {
             alert ('x: '+ event.xAxis[0].value +', y: '+
             event.yAxis[0].value);
             }
             }
             },*/
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: <?=json_encode($data);?>
        });
    });
</script>