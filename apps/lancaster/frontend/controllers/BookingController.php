<?php

namespace frontend\controllers;
use Yii;
use vsoft\building\models\LcBuilding;
use vsoft\building\models\LcBooking;

class BookingController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/layout';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionViewFloorByBuilding($id)
    {
        if ($id > 0) {
            $building = LcBuilding::find()->where(['id' => $id])->one();
            $floor = $building->floor;
            if ($building) {
//                echo "<option value='333'>$building->floor</option>";
                for ($x = 1; $x <= $floor; $x++) {
                    echo "<option value='$x'>$x</option>";
                }

            }
        }
//        else{
//            echo "<option>Cannot find floor number in building</option>";
//        }
    }

    public function actionBookingHotel()
    {
        $booking = new LcBooking();
        if(!empty($_POST)) {
            $post = Yii::$app->request->post();
            $booking->apart_type = $post["apart_type"];
            $booking->floorplan = $post["floorplan"];
        }
        if ($booking->load($post)) {
            if ($booking->save()) {
                if (!empty($booking->email)) {
                    // call send mail method after click submit button
                    $resultEmail = $booking->sendBookingMail($booking);
                }
                Yii::$app->getSession()->setFlash('reSuccess', 'Create booking successfully.');
            }
        }

        return $this->redirect(['/booking']);


    }

}
