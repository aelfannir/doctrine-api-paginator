Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require aelfannir/doctrine-query-paginator
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require aelfannir/doctrine-query-paginator
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    AElfannir\DoctrineQueryPaginator\DoctrineQueryPaginatorBundle::class => ['all' => true],
];
```


# Docs
***

# Filter

Filters describe conditions on table property values to include in the results from a database query.

A filter can be a **property filter** or it can be a compound filter formed by joining mtuliple **property filters** using a logical `"AND"` or `"OR"` operation.

**Compound filters** can be nested.

## Property filters

### `property`

Type `string`,  The name or ID of the property to filter on.

### `operator`

Type `string`,  The operator ID used to generate comparision expression. Possible values include;

|Operator                    |Type                          |Description                                                                                                                                                                   |Example value                                   |
|----------------------------|------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------|
|`STRING.EQ`                 |`string`                      |Returns records where the property value matches the provided value exactly.                                                                                                  |`"hello world"`                                 |
|`STRING.NEQ`                |`string`                      |Returns records where the property value doesn't match the provided value exactly.                                                                                            |`"hello world"`                                 |
|`STRING.CONTAINS`           |`string`                      |Returns records where the property value contains the provided value.                                                                                                         |`"world"`                                       |
|`STRING.DOES_NOT_CONTAIN`   |`string`                      |Returns records where the property value doesn't contains the provided value.                                                                                                 |`"hello world"`                                 |
|`STRING.STARTS_WITH `       |`string`                      |Returns records where the property value starts with the provided value.                                                                                                      |`"world"`                                       |
|`STRING.ENDS_WITH  `        |`string`                      |Returns records where the property value ends with the provided value.                                                                                                        |`"hello"`                                       |
|`STRING.DOES_NOT_START_WITH`|`string`                      |Returns records where the property value doesn't start with the provided value.                                                                                               |`"hello"`                                       |
|`STRING.DOES_NOT_END_WITH`  |`string`                      |Returns records where the property value doesn't end with the provided value.                                                                                                 |`"world"`                                       |
|`STRING.IN`                 |`array`                       |Returns records where the property value is one of the provided values.                                                                                                       |`["hellow world, "foo"]`                        |
|`STRING.NOT_IN`             |`array`                       |Returns records where the property value isn't one of the provided values.                                                                                                    |`["hellow world, "foo"]`                        |
|`STRING.IS_NULL`            |                              |Returns records where the property value is null. Note that the value sent will be ignored                                                                                    |                                                |
|`STRING.IS_NOT_NULL`        |                              |Returns records where the property value is not null. Note that the value sent will be ignored                                                                                |                                                |
|`NUMBER.EQ`                 |`number`                      |Returns records where the property value matches the provided value exactly.                                                                                                  |`101`                                           |
|`NUMBER.NEQ`                |`number`                      |Returns records where the property value doesn't match the provided value exactly.                                                                                            |`101.5`                                         |
|`NUMBER.GT`                 |`number`                      |Returns records where the property value is greater than the provided value.                                                                                                  |`101`                                           |
|`NUMBER.GTE`                |`number`                      |Returns records where the property value is greater than or equals to the provided value.                                                                                     |`101`                                           |
|`NUMBER.LT`                 |`number`                      |Returns records where the property value is less than the provided value.                                                                                                     |`101`                                           |
|`NUMBER.LTE`                |`number`                      |Returns records where the property value is less than or equals t the provided value.                                                                                         |`101`                                           |
|`NUMBER.IN`                 |`number`                      |Returns records where the property value is one of the provided values.                                                                                                       |`[102, 101]`                                    |
|`NUMBER.NOT_IN`             |`number`                      |Returns records where the property value is one of the provided values. Note that the value sent will be ignored                                                              |`[12, 101]`                                     |
|`NUMBER.IS_NULL`            |                              |Returns records where the property value is null. Note that the value sent will be ignored, Note that the value sent will be ignored                                          |                                                |
|`NUMBER.IS_NOT_NULL`        |                              |Returns records where the property value is not null. Note that the value sent will be ignored                                                                                |                                                |
|`DATETIME.EQ`               |`string "YYYY-MM-DD hh:mm:ss"`|Returns records where the property value matches the provided date exactly. Note that any time information sent will be ignored when the property values's type is date.      |`"2021-12-24 10:30:00"`                         |
|`DATETIME.NEQ`              |`string "YYYY-MM-DD hh:mm:ss"`|Returns records where the property value doesn't match the provided date exactly. Note that any time information sent will be ignored when the property values's type is date.|`"2021-12-24 10:30:00"`                         |
|`DATETIME.GT`               |`string "YYYY-MM-DD hh:mm:ss"`|Returns records where the property value is after the provided date. Note that any time information sent will be ignored when the property values's type is date.             |`"2021-12-24 10:30:00"`                         |
|`DATETIME.GTE`              |`string "YYYY-MM-DD hh:mm:ss"`|Returns records where the property value is on or after the provided date. Note that any time information sent will be ignored when the property values's type is date.       |`"2021-12-24 10:30:00"`                         |
|`DATETIME.LT`               |`string "YYYY-MM-DD hh:mm:ss"`|Returns records where the property value is before the provided date. Note that any time information sent will be ignored when the property values's type is date.            |`"2021-12-24 10:30:00"`                         |
|`DATETIME.LTE`              |`string "YYYY-MM-DD hh:mm:ss"`|Returns records where the property value is on or before the provided date. Note that any time information sent will be ignored when the property values's type is date.      |`"2021-12-24 10:30:00"`                         |
|`DATETIME.IN`               |`array`                       |Returns records where the property value is one of the provided dates.                                                                                                        |`["2021-01-01 00:00:00", "2021-12-31 23:59:59"]`|
|`DATETIME.NOT_IN`           |`array`                       |Returns records where the property value is not in the provided dates.                                                                                                        |`["2021-01-01 00:00:00", "2021-12-31 23:59:59"]`|
|`DATETIME.IS_NULL`          |                              |Returns records where the property value is null. Note that the value sent will be ignored                                                                                    |                                                |
|`DATETIME.IS_NOT_NULL`      |                              |Returns records where the property value is not null. Note that the value sent will be ignored                                                                                |                                                |
|`BOOL.EQ`                   |`bool`                        |Returns records where the property value matches the provided value exactly.                                                                                                  |`true`                                          |
|`RELATION.EQ`               |`number`                      |Returns records where the relation id matches the provided value exactly.                                                                                                     |`1`                                             |
|`RELATION.NEQ`              |`number`                      |Returns records where the relation id doesn't match the provided value exactly.                                                                                               |`102`                                           |
|`RELATION.IS_NULL`          |                              |Returns records where the property value is null. Note that the value sent will be ignored                                                                                    |                                                |
|`RELATION.IS_NOT_NULL`      |                              |Returns records where the property value is not null. Note that the value sent will be ignored                                                                                |                                                |


### `value`

Can be one of the following types `array`, `string` or `number`

### Examples

```json
{
  "filter": {
    "operator": "AND",
    "filters": [
      {
        "property": "createdAt",
        "operator": "DATETIME.GTE",
        "value": "2021-01-01 00:00"
      },
			{
        "property": "createdAt",
        "operator": "DATETIME.LT",
        "value": "2022-01-01 00:00"
      }
    ]
  }
}
```

## Compound filters

A **compound filter** object combines several database **property filters** together. A **compound filter** can even be combined within a **compound filter.**

The **compound filter** object contains one of the following keys:

### `operator`

The condition used to combine `filters`, can be `"AND"` or `"OR"`

### `filters`

`array` of **property filters**

### Examples

```json
{
  "filter": {
    "operator": "OR",
    "filters": [
      {
        "property": "firstName",
        "operator": "STRING.EQ",
        "value": "Mohammed"
      },
      {
        "operator": "AND",
        "filters": [
          {
            "property": "createdAt",
            "operator": "DATETIME.GTE",
            "value": "2021-01-01 00:00"
          },
          {
            "property": "createdAt",
            "operator": "DATETIME.LT",
            "value": "2022-01-01 00:00"
          }
        ]
      }
    ]
  }
}
```

# Search

Type `string`, search in the target table root's properties

### Example

```json
{
	"search": "foo"
}
```

# Pagination

Type `object` should contain the metadata that required for the datatable pagination to work.

### `pagination.page`

Type `number`, The current page number.

### `pagination.pages`

Type `number`, Total number of pages available in the server.

### `pagination.perPage`

Type `number`, The current page number.

### `pagination.total`

Type `number`, Total all records number available in the server

### Example

- Request

```json
{
  "pagination": {
    "page": 1,
    "perPage": 10
  }
}
```

- Response

```json
{
  "pagination": {
    "page": 1,
    "perPage": 10,
    "total": 100,
    "pages": 10
  }
}
```

# Sorts

Sort objects describe the order of database query results. The Query a database endpoint accepts an `array` of sort objects in the sorts body parameter. In the array, the lower index object takes precedence. Each sort object contains the following keys

### `property`

Type `string`, The name of the property to sort against.

### `direction`

The direction to sort. Possible values include `"ASC"` and `"DESC"`.

### Example

```json
{
  "sorts": [
    {
      "property": "firstName",
      "direction": "ASC"
    },
    {
      "property": "lastName",
      "direction": "DESC"
    }
  ]
}
```

# Join

### `table`

Type `string`, join table name.

### `alias`

optional, type `string`, note that when alias is undifined table's value is used as ajoin table's alias

### `join`

optional, `array` of **join** conditions,

### Example

```json
{
  "join": [
    {
      "table": "category",
      "join": [
        {
          "table": "parent",
          "alias": "subCategory",
          "join": [
            {
              "table": "parent",
              "alias": "subCategory2"
            }
          ]
        }
      ]
    }
  ]
}
```