<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int $color
 * @property string $created
 * @property string $updated
 * @property int $state
 * @property float $prs
 */
class Apple extends \yii\db\ActiveRecord
{
    const HANG_STATE = 0;
    const FALL_STATE = 1;
    const ROT_STATE = 2;

    public array $colors = [
        1 => 'green',
        2 => 'red',
        3 => 'yellow',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color', 'updated', 'prs'], 'required'],
            [['color', 'state'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['prs'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Цвет',
            'created' => 'Создано',
            'updated' => 'Изменено',
            'state' => 'Статус',
            'prs' => 'Осталось',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getStateList()
    {
        return [
            self::HANG_STATE => 'Висит',
            self::FALL_STATE => 'Упало',
            self::ROT_STATE => 'Сгнило',
        ];
    }

    public function getStateLabel()
    {
        $d = $this->getStateList();
        return $d[$this->state] ?? '';
    }

    public function getColorName()
    {
        return $this->colors[$this->color] ?? '';
    }

    public function getRandomColor()
    {
        return array_rand($this->colors);
    }

    public static function refreshState()
    {
        $state_set = self::ROT_STATE;
        $state_where = self::FALL_STATE;
        Yii::$app->db->
        createCommand('UPDATE apple SET state=:state_set WHERE state=:state_where AND TIMESTAMPDIFF(HOUR, created, updated)) > 4')
            ->bindParam(':state_set', $state_set)
            ->bindParam(':state_where', $state_where)
            ->execute();
    }
}
