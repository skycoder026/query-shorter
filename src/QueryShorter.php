<?php

namespace Skycoder\QueryShorter;



trait QueryShorter
{

    // filter data by field name
    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, request()->$filed_name);
        });
    }



    // search from date
    public function scopeSearchDateFrom($query, $filed_name, $from = null)
    {
        if ($from == null) {
            $from = 'from';
        }

        $query->when(request()->filled($from), function ($qr) use ($filed_name, $from) {
            $qr->where($filed_name, '>=', request()->$from);
        });
    }



    // search to date
    public function scopeSearchDateTo($query, $filed_name, $to = null)
    {
        if ($to == null) {
            $to = 'to';
        }

        $query->when(request()->filled($to), function ($qr) use ($filed_name, $to) {
            $qr->where($filed_name, '<=', request()->$to);
        });
    }




    // search data from relationship
    public function scopeSearchFromRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($relation, $filed_name) {
            $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, request()->$filed_name);
            });
        });
    }





    // sort data by its key
    public function scopeSortby($query)
    {
        $query->when(request()->filled('sort_by_key'), function ($query) {

            $order_by = 'asc';

            if (strpos(request()->sort_by_key, 'desc') !== false) {
                $order_by = 'desc';
            }

            $filed_name = str_replace('_desc', '', request()->sort_by_key);

            return $query->orderBy($filed_name, $order_by);
        });
    }
}
