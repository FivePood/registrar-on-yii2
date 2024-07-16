<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $domainId
 * @property string|null $name
 * @property int|null $registeredId
 * @property string|null $handle
 * @property int|null $createdAt
 * @property int|null $updatedAt
 */
class Domain extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'domain';
    }

    public function rules(): array
    {
        return [
            [['registeredId', 'createdAt', 'updatedAt'], 'integer'],
            [['name', 'handle'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
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
