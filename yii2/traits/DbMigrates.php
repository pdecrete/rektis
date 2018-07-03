<?php
namespace app\traits;

/**
 */
trait DbMigrates
{

    /**
     * Create table options for table creation to select a convinient collation.
     *
     * See http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
     *
     * @return string|null The tableOptions
     */
    public function utf8CollateOptions()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    /**
     * Execute a command to allow invalid dates in following changes.
     *
     */
    public function allowInvalidDates()
    {
        \Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
    }

    /**
     * Execute a command to enable foreign key checks 
     * 
     */
    public function enableForeignKeyChecks()
    {
        \Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }

    /**
     * Execute a command to disable foreign key checks 
     * 
     */
    public function disableForeignKeyChecks()
    {
        \Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    }
}
