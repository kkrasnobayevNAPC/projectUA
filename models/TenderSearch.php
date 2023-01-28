<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * DataSource model
 *
 * @property integer $id
 * @property string $tenderId
 * @property string $description
 * @property float $amount
 * @property string $dateModified
 *
 */
class TenderSearch extends Tender
{

    public static function tableName()
    {
        return '{{%tender}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [[['tenderId', 'description', 'amount', 'dateModified'], 'safe']];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {

        $query = Tender::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'dateModified' => SORT_DESC,
                ],

            ],
        ]);

        if (!(($this->load($params) && $this->validate()))) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'tenderId', $this->tenderId])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'dateModified', $this->dateModified]);

        /**
         * allow to use comparison operands for amount value
         */
        $operands = ['=', '<', '>', '!=', '>=', '<='];

        $selectedOperand = '=';

        $amount = $this->amount;

        foreach ($operands as $operand) {

            if (strpos($this->amount, $operand) === 0) {

                $selectedOperand = $operand;

                $amount = substr($this->amount, strlen($operand));

                break;

            }

        }

        $query->andFilterWhere([$selectedOperand, 'amount', $amount]);

        return $dataProvider;

    }

}
