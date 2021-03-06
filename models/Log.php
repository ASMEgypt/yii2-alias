<?php
/**
 */

namespace execut\alias\models;


use execut\crudFields\Behavior;
use execut\crudFields\BehaviorStub;
use execut\crudFields\fields\Field;
use execut\crudFields\fields\NumberField;
use execut\crudFields\ModelsHelperTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Log extends ActiveRecord
{
    const MODEL_NAME = '{n,plural,=0{Logs} =1{Log} other{Logs}}';
    use BehaviorStub, ModelsHelperTrait;
    public static function find()
    {
        return new \execut\alias\models\queries\Log(self::class);
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->visible = true;
    }

    public static function tableName()
    {
        return 'alias_logs';
    }
    
    public static function add($model) {
        $oldAlias = $model->getOldAttribute('alias');
        $log = new self;
        $log->scenario = Field::SCENARIO_FORM;
        $log->setAttributes([
            'owner_table' => $model::tableName(),
            'owner_id' => $model->id,
            'old_alias' => $oldAlias,
        ]);

        return $log->save();
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fields' => [
                    'class' => Behavior::class,
                    'fields' => $this->getStandardFields(['name'], [
                        [
                            'attribute' => 'owner_table',
                            'required' => true,
                        ],
                        [
                            'class' => NumberField::class,
                            'attribute' => 'owner_id',
                            'required' => true,
                        ],
                        'old_alias' => [
                            'required' => true,
                            'attribute' => 'old_alias',
                        ],
                    ]),
                ],
                [
                    'class' => TimestampBehavior::class,
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'updated',
                    'value' => new Expression('NOW()'),
                ],
                # custom behaviors
            ]
        );
    }

    public function getOwner() {
        return $this->hasOne($this->owner_model, [
            'id' => 'owner_id',
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
        ]); // TODO: Change the autogenerated stub
    }
}