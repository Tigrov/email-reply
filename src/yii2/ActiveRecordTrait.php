<?php
/**
 * @link https://github.com/tigrov/email-reply
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\emailReply\yii2;

/**
 * Trait for ActiveRecord model of Yii2 framework.
 * It realizes base methods to use ActiveRecord models with email-reply.
 * You need to realize in ActiveRecord models only one method `emailReply($message)`
 */

trait ActiveRecordTrait
{
    /**
     * @inheritdoc
     */
    public static function paramNames()
    {
        return static::primaryKey();
    }

    /**
     * @inheritdoc
     */
    public function paramValues()
    {
        return $this->getPrimaryKey(true);
    }

    /**
     * @inheritdoc
     */
    public static function buildFromParams($paramValues)
    {
        $keys = [];
        foreach (static::paramNames() as $name) {
            $keys[$name] = $paramValues[$name];
        }

        if ($keys) {
            $model = static::findOne($keys);

            return $model;
        }

        return null;
    }
}