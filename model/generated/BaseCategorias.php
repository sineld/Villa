<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Categorias', 'villadel_villa');

/**
 * BaseCategorias
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $nombre
 * @property integer $inactivo
 * @property Doctrine_Collection $Articulos
 * @property Doctrine_Collection $Tipos
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseCategorias extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('categorias');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('nombre', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('inactivo', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Articulos', array(
             'local' => 'id',
             'foreign' => 'categoria'));

        $this->hasMany('Tipos', array(
             'local' => 'id',
             'foreign' => 'id_cat'));
    }
}