
# Description [![Build Status](https://secure.travis-ci.org/jeresig/jquery.hotkeys.png)](http://travis-ci.org/jeresig/jquery.hotkeys)

**Query Shorter** is a laravel package where it shorts the 50% query of your Eloquent query.
Sometimes our queries get too long like nested queries, conditional queries so this package creates shorter query for you.

This is a small package to easy and simplify your code.

## Installation Process

```bash
composer require skycoder/query-shorter
```


## Uses

### Model

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Skycoder\QueryShorter\QueryShorter;

class Employee extends Model
{
    use  QueryShorter;
}
```

### Controller 
Before Using Query Shorter
```php
  $employees = Employee::query()
                ->when($request->filled('name'), function($q) use($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                })
                ->when($request->filled('department_id'), function($q) use($request) {
                    $q->where('name', $request->department_id);
                })
                ->when($request->filled('designation_id'), function($q) use($request) {
                    $q->where('designation_id', '!=', $request->designation_id);
                })
                ->when($request->filled('age'), function($q) use($request) {
                    $q->where('age', '<', $request->age);
                })
                ->when($request->filled('from_date'), function($q) use($request) {
                    $q->where('joining_date', '>=', $request->from_date);
                })
                ->when($request->filled('to_date'), function($q) use($request) {
                    $q->where('joining_date', '<=', $request->to_date);
                })
                ->when($request->filled('from_retirement_date'), function($q) use($request) {
                    $q->where('retirement_date', '>=', $request->from_retirement_date);
                })
                ->when($request->filled('to_retirement_date'), function($q) use($request) {
                    $q->where('retirement_date', '<=', $request->to_retirement_date);
                })
                ->latest()
                ->get();
```

After Using Query Shorter
```php
 $employees = Employee::query()
                        ->likeSearch('name')
                        ->searchByField('department_id') // check if the request has `department_id` value then we query
                        ->searchByField('designation_id', "!=")
                        ->searchByField('age', "<")
                        ->searchDateFrom('joining_date') // `joining_date` is database field and `from_date` from request
                        ->searchDateTo('joining_date') // `joining_date` is database field and `to_date` from request
                        ->searchDateFrom('retirement_date', 'from_retirement_date') // `retirement_date` is database field and `from_retirement_date` from request
                        ->searchDateTo('retirement_date', 'to_retirement_date') // `retirement_date` is database field and `to_retirement_date` from request
                        ->latest()
                        ->get();
```


## More Packages

- <a href="https://github.com/skycoder026/user-log" target="_blank">User Log</a>
- <a href="https://github.com/skycoder026/laravel-filesaver" target="_blank">Laravel Filesaver</a>


