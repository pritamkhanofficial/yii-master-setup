<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $role_id
 * @property string $name
 * @property string $display_name
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_active 0 for no 1 for yes
 *
 * @property Users[] $users
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    public function beforeSave($insert) {
        $id=Yii::$app->user->id;
        $this->display_name = ucfirst($this->name);
        if($this->isNewRecord){
            $this->created_at = Yii::$app->helpers->dbDateFormat();
            $this->created_by = $id;
            $this->updated_by = $id;
        }else{
            $this->updated_at = Yii::$app->helpers->dbDateFormat();
            $this->updated_by = $id;
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name','display_name','created_at', 'updated_at','created_by', 'updated_by', 'is_active','is_delete'], 'safe'],
            [['created_by', 'updated_by', 'is_active'], 'integer'],
            [['name', 'display_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'name' => 'Name',
            'display_name' => 'Display Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_active' => 'Status',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['role_id' => 'role_id']);
    }
}
