<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE MATERIALIZED VIEW ward_view AS
            SELECT ward.id AS wid,
                   ward.name AS wname,
                   council.id AS cid,
                   council.name AS cname,
                   region.id AS rid,
                   region.name AS rname,
                   country.id AS coid,
                   country.name AS coname
            FROM admin_areas ward
            JOIN admin_areas council
              ON council.id = ward.parent_area_id
             AND ward.area_type_id = 4
            JOIN admin_areas region
              ON council.parent_area_id = region.id
             AND region.area_type_id = 2
            JOIN admin_areas country
              ON region.parent_area_id = country.id
             AND country.area_type_id = 1
        SQL);

        DB::statement('CREATE UNIQUE INDEX ward_view_wid_idx ON ward_view (wid)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS ward_view');
    }
};
