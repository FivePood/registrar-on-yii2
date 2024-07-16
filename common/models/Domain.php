<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "domain".
 *
 * @property int $domainId
 * @property string|null $name
 * @property int|null $registeredId
 * @property string|null $handle
 * @property string|null $comment
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
            [['name', 'handle', 'comment'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'domainId' => 'ID',
            'name' => 'Имя домена',
            'registeredId' => 'Идентификатор домена',
            'handle' => 'дескриптор операции',
            'comment' => 'Комментарий операции',
            'createdAt' => 'Дата создания ',
            'updatedAt' => 'Дата обновления',
        ];
    }
}
