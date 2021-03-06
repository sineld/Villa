<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Articulos', 'villadel_villa');

/**
 * BaseArticulos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $nombre
 * @property string $codigo
 * @property string $descripcion
 * @property float $alto
 * @property float $ancho
 * @property float $largo
 * @property float $diametro
 * @property float $peso
 * @property integer $empaque
 * @property timestamp $fechaingreso
 * @property integer $agotado
 * @property integer $inactivo
 * @property integer $categoria
 * @property integer $tipo
 * @property Categorias $Categorias
 * @property Tipos $Tipos
 * @property Doctrine_Collection $ArticulosIndex
 * @property Doctrine_Collection $FotosArt
 * @property Doctrine_Collection $PrecioArt
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseArticulos extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('articulos');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('nombre', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('codigo', 'string', 25, array(
             'type' => 'string',
             'length' => 25,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('descripcion', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('alto', 'float', 18, array(
             'type' => 'float',
             'length' => 18,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('ancho', 'float', 18, array(
             'type' => 'float',
             'length' => 18,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('largo', 'float', 18, array(
             'type' => 'float',
             'length' => 18,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('diametro', 'float', 18, array(
             'type' => 'float',
             'length' => 18,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('peso', 'float', 18, array(
             'type' => 'float',
             'length' => 18,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('empaque', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('fechaingreso', 'timestamp', null, array(
             'type' => 'timestamp',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('agotado', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
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
        $this->hasColumn('categoria', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('tipo', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Categorias', array(
             'local' => 'categoria',
             'foreign' => 'id'));

        $this->hasOne('Tipos', array(
             'local' => 'tipo',
             'foreign' => 'id'));

        $this->hasMany('ArticulosIndex', array(
             'local' => 'id',
             'foreign' => 'id'));

        $this->hasMany('FotosArt', array(
             'local' => 'id',
             'foreign' => 'id_art'));

        $this->hasMany('PrecioArt', array(
             'local' => 'id',
             'foreign' => 'id_art'));
    }
}