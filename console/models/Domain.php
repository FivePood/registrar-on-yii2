<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "domain".
 *
 * @property int $domainId
 * @property string|null $name
 * @property int|null $registeredId
 * @property string|null $handle
 * @property int|null $createdAt
 * @property int|null $updatedAt
 */
class Domain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'domain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['registeredId', 'createdAt', 'updatedAt'], 'integer'],
            [['name', 'handle'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'domainId' => 'Domain ID',
            'name' => 'Name',
            'registeredId' => 'Registered ID',
            'handle' => 'Handle',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
