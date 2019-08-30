<?php

namespace App\Repositories;

class Repository
{
    public $loopKey = 0;
    
    protected function whereFilter($query, $whereParams)
    {
        foreach ((array)$whereParams as $param) {
            if(!empty($param['paramValue'])) {
                $query = $query->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
            }
        }

        return $query;
    }

    protected function orWhereFilter($query, $orWhereParams)
    {
        $this->loop = 0;
        $query = $query->where(function ($qry) use($query, $orWhereParams){
            foreach((array)$orWhereParams as $orParam) {
                if(!empty($orParam['paramValue'])) {
                    if($this->loop == 0) {
                        $this->loop ++;
                        $qry->where($orParam['paramName'], $orParam['paramOperator'], $orParam['paramValue']);
                    } else {
                        $qry->orWhere($orParam['paramName'], $orParam['paramOperator'], $orParam['paramValue']);
                    }
                }
            }
        });

        return $query;
    }

    protected function relationalFilter($query, $relationalParams)
    {
        foreach ((array)$relationalParams as $relationalParam) {
            if(!empty($relationalParam['paramValue'])) {
                $query = $query->whereHas($relationalParam['relation'], function($qry) use($relationalParam) {
                    $qry->where($relationalParam['paramName'], $relationalParam['paramOperator'], $relationalParam['paramValue']);
                });
            };
        }

        return $query;
    }

    protected function relationalOrFilter($query, $relationalOrParams)
    {
        foreach ((array)$relationalOrParams as $relationalOrParam) {
            $query = $query->whereHas($relationalOrParam['relation'], function($qry) use($relationalOrParam) {
                $this->loop = 0;
                foreach((array)$relationalOrParam['params'] as $key => $param) {
                    if(!empty($param['paramValue'])) {
                        if($this->loop == 0) {
                            $this->loop ++;
                            $qry->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                        } else {
                            $qry->orWhere($param['paramName'], $param['paramOperator'], $param['paramValue']);
                        }
                    }
                }
            });
        }

        return $query;
    }

    protected function getFilter($query, $orderBy)
    {
        if(!empty($orderBy['num'])) {
            if($orderBy['num'] == 1) {
                $query = $query->firstOrFail();
            } else {
                $query = $query->orderBy($orderBy['by'], $orderBy['order'])->paginate($orderBy['num']);
            }
        } else {
            $query= $query->orderBy($orderBy['by'], $orderBy['order'])->get();
        }

        return $query;
    }

    protected function aggregatesSwitch($query, $aggregates=['key' => null, 'value' => null]) {
        if(!isset($aggregates['key'])) {
            return false;
        }
        switch (strtolower($aggregates['key'])) {
            case 'count':
                $query = $query->count();
                break;
            case 'sum':
                $query = $query->sum($aggregates['value']);
                break;
            default:
                $query = null;
                break;
        }
         return $query;
    }
}
