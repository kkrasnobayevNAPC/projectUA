<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * DataSource model
 *
 * @property integer $id
 * @property string $tenderId
 * @property string $description
 * @property float $amount
 * @property string $dateModified
 * @property string $shit
 *
 */
class Tender extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules(): array
    {

        return [
            [['tenderId', 'description', 'amount', 'dateModified'], 'required'],
            [['tenderId'], 'string', 'max' => 32, 'min' => 32],
            [['description'], 'string', 'max' => 10000],
            [['amount'], 'number', 'min' => 0]
        ];

    }

}
