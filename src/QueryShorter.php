<?php

namespace Skycoder\QueryShorter;



trait QueryShorter
{

    

    /*
     |--------------------------------------------------------------------------
     | ACTIVE STATUS
     |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }




    /*
     |--------------------------------------------------------------------------
     | LIKE SEARCH
     |--------------------------------------------------------------------------
    */
    public function scopeLikeSearch($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
           $qr->where($filed_name, 'LIKE', '%' . request()->$filed_name . '%');
        });
    }










    /*
     |--------------------------------------------------------------------------
     | LIKE SEARCH MULTIPLE FIELD
     |--------------------------------------------------------------------------
    */
    public function scopeLikeSearchArr($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {
            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, 'LIKE', '%' . request()->$filed_name . '%');
            });
        }
    }










    /*
     |--------------------------------------------------------------------------
     | SEARCH DATA BY FIELD NAME
     |--------------------------------------------------------------------------
    */
    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
           $qr->where($filed_name, request()->$filed_name);
        });
    }











    /*
     |--------------------------------------------------------------------------
     | SEARCH MULTIPLE DATA BY FIELD NAME
     |--------------------------------------------------------------------------
    */
    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {

            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
             });
        }
        
    }













    /*
     |--------------------------------------------------------------------------
     | DATE FILTER [FROM DATE - TO DATE]  BY DEFAULT IT USING DATE COLUMN OR YOU CAN PASS YOUR COLUMN NAME (DATE FILED)
     |--------------------------------------------------------------------------
    */
    public function scopeDateFilter($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function($qr) use($filed_name) {
           $qr->where($filed_name, '>=', (request('from') ?? request('from_date')));
        })
        ->when(request()->filled('to') | request()->filled('to_date'), function($qr) use($filed_name) {
           $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
        });
    }









    /*
     |--------------------------------------------------------------------------
     | SEARCH BY FROM DATE, BY DEFAULT IT USING FROM DATE INPUT FIELD OR YOU CAN PASS YOUR INPUT FIELD NAME (FROM DATE)
     |--------------------------------------------------------------------------
    */
    public function scopeSearchDateFrom($query, $filed_name, $from = null)
    {
        if($from == null) {
            $from = 'from_date';
        }

        $query->when(request()->filled($from), function($qr) use($filed_name, $from) {
           $qr->where($filed_name, '>=', request($from));
        });
    }











    /*
     |--------------------------------------------------------------------------
     | SEARCH BY TO DATE, BY DEFAULT IT USING TO DATE INPUT FIELD OR YOU CAN PASS YOUR INPUT FIELD NAME (TO DATE)
     |--------------------------------------------------------------------------
    */
    public function scopeSearchDateTo($query, $filed_name, $to = null)
    {
        if($to == null) {
            $to = 'to';
        }

        $query->when(request()->filled($to), function($qr) use($filed_name, $to) {
           $qr->where($filed_name, '<=', request($to));
        });
    }












    /*
     |--------------------------------------------------------------------------
     | LIKE SEARCH IN RELATIONAL TABLE
     |--------------------------------------------------------------------------
    */
    public function scopeLikeSearchRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($relation, $filed_name) {
           $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, 'LIKE', '%' . request($filed_name) . '%');
            });
        });
    }












    /*
     |--------------------------------------------------------------------------
     | SEARCH BY FILED FROM RELATIONAL TABLE
     |--------------------------------------------------------------------------
    */
    public function scopeSearchFromRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($relation, $filed_name) {
           $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, request($filed_name));
            });
        });
    }













    /*
     |--------------------------------------------------------------------------
     | SEARCH MULTIPLE FIELD FROM RELATIONAL TABLE
     |--------------------------------------------------------------------------
    */
    public function scopeSearchItemsFromRelation($query, $relation, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {
            $query->when(request()->filled($filed_name), function($qr) use($relation, $filed_name) {
                $qr->whereHas($relation, function ($q) use ($filed_name) {
                    $q->where($filed_name, request($filed_name));
                });
            });
        }
    }













    /*
     |--------------------------------------------------------------------------
     | SORT BY FIELD
     |--------------------------------------------------------------------------
    */
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











    /*
     |--------------------------------------------------------------------------
     | SELECT NAME [SELECT ONLY NAME FROM RELATIONAL TABLE AND APPEND AS AN ATTRIBUTE]
     |--------------------------------------------------------------------------
    */
    public function scopeSelectName($query, $relation_name, $name = 'name')
    {
        $table = $relation_name . ' as ' . $relation_name .'_' . $name;

        $query->withCount([$table => function($q) use($name) { $q->select(DB::raw($name)); }]);
    }
}
