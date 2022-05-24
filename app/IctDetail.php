<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class IctDetail extends Model
{
    protected $fillable = [
        'ireqd_id',
        'ireq_id',
        'ireq_type',
        'invent_code',
        'ireq_desc',
        'ireq_qty',
        'ireq_status',
        'ireq_reason',
        'ireq_assigned_to1',
        'ireq_assigned_to1_reason',
        'ireq_assigned_to2',
        'ireq_assigned_date',
        'ireq_remark',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
        'ireq_value',
        'ireq_note',
        'ireq_note_personnel'
    ];
    protected $table = 'ireq_dtl';
    protected $primaryKey ='ireqd_id';
    public $timestamps = false;
    public $incrementing = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

}
