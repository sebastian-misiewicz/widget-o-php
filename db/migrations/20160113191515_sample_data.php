<?php

use Phinx\Migration\AbstractMigration;

class SampleData extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $admin = [
            'username'    => 'admin',
            'password'  => '098f6bcd4621d373cade4e832627b4f6'
        ];
        $user = $this->table('user');
        $user->insert($admin);
        $user->saveData();
        
        $index = [
            'idpage'    => 'index.html',
            'template'  => 'index.html',
            'json'  => '{"title":{"text":"Page title"}}'
        ];
        $page = $this->table('page');
        $page->insert($index);
        $page->saveData();
        
    }
}
