<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

class ShareForm extends Model
{
    /** @var string */
    public $recipient_email;
    public $your_email;
    public $subject;
    public $content;
    public $address;
    public $detailUrl;
    public $domain;
    public $type;
    public $pid;
    public $from_name;
    public $to_name;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'share' => ['recipient_email', 'your_email', 'subject', 'content', 'address', 'detailUrl', 'domain', 'type', 'pid', 'from_name','to_name'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'recipient_email'    => Yii::t('share', 'Recipient email'),
            'your_email' => Yii::t('share', 'Your email'),
        ];
    }

    public function rules() {
        return [
            [['recipient_email', 'your_email'], 'required'],
            [['recipient_email', 'your_email'], 'email'],
            [['recipient_email', 'your_email'], 'string', 'max' => 255],
            [['address', 'content', 'detailUrl', 'domain', 'subject', 'type', 'pid', 'from_name','to_name'], 'string'],
        ];
    }

}