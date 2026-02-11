# API Collection Querying

This project’s InfyOm-based controllers support rich list endpoints out of the box. Use the following query parameters on any `GET /api/{resource}` route generated after 09 Feb 2026.

## Pagination
- `page` – 1-based page index (defaults to 1 if omitted).
- `per_page` – items per page (defaults to 15, must be > 0).

Responses include a `meta` object:
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 2,
    "per_page": 25,
    "total": 137,
    "last_page": 6
  }
}
```
Resource controllers nest items under `items` but keep the same `meta` block.

## Free-Text Search
- `search` – string to match against any column in the model’s table.
- `search_columns` – optional whitelist for searchable fields. Accepts an array (`search_columns[]=name`) or comma list (`search_columns=name,description`). Columns that do not exist are ignored.
- `search_mode` – controls match style. Options:
  - `contains` (default): `%term%`
  - `prefix`: `term%`
  - `suffix`: `%term`
  - `exact`: `= term`

Example:
```
GET /api/permissions?search=admin&search_columns=name,description&search_mode=prefix
```

## Column Filters
Send exact-match filters via the `filters` hash. Only valid table columns are applied.
```
GET /api/permissions?filters[guard_name]=web&filters[name]=view users
```

## Putting It Together
Combine all knobs freely:
```
GET /api/permissions?search=user&filters[guard_name]=api&page=3&per_page=20
```
This request returns page 3 (items 41–60) of API-guard permissions whose columns contain "user", along with pagination metadata.
