<?php

namespace AlgoWeb\PODataLaravel\Models;

use AlgoWeb\PODataLaravel\Models\MetadataTrait;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Connection as Connection;
use Mockery\Mockery;

class TestMonomorphicManySource extends Model
{
    use MetadataTrait {
        metadata as traitmetadata; // Need to alias the trait version of the method so we can call it and
        // not bury ourselves under a stack overflow and segfault
        getRelationshipsFromMethods as getRel;
    }
    protected $metaArray;
    protected $connect;

    public function __construct(array $meta = null, Connection $connect = null)
    {
        if (isset($meta)) {
            $this->metaArray = $meta;
        }
        if (isset($connect)) {
            $this->connect = $connect;
        } else {
            $connect = \Mockery::mock(Connection::class)->makePartial();
            $this->connect = $connect;
        }
        parent::__construct();
    }

    public function manySource()
    {
        return $this->belongsToMany(TestMonomorphicManyTarget::class, "target_source", "many_source", "many_id");
    }

    public function getTable()
    {
        return 'testMonomorphicManySource';
    }

    public function getConnectionName()
    {
        return 'testconnection';
    }

    public function metadata()
    {
        if (isset($this->metaArray)) {
            return $this->metaArray;
        }
        return $this->traitmetadata();
    }
}