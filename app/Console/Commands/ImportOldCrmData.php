<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use App\Models\Did;
use App\Models\Lead;

class ImportOldCrmData extends Command
{
    protected $signature = 'crm:import-old-data';
    protected $description = 'Import campaigns, dids, and leads from old SQL dump (MySQL) into Laravel SQLite DB';

    public function handle()
    {
        $sqlPath = base_path('database/u806021370_crm.sql');
        if (!file_exists($sqlPath)) {
            $this->error('SQL file not found: ' . $sqlPath);
            return 1;
        }
        $sql = file_get_contents($sqlPath);

        // Import campaigns
        $this->importTable($sql, 'campaigns', ['id', 'name', 'payout_amount', 'created_at'], Campaign::class);
        // Import dids
        $this->importTable($sql, 'dids', ['id', 'did_number', 'owner_name', 'payout_amount', 'created_at'], Did::class);
        // Import leads
        $this->importTable($sql, 'leads', [
            'id', 'created_at', 'first_name', 'last_name', 'phone', 'did_number', 'campaign_name', 'address', 'city', 'state', 'zip', 'email', 'notes', 'agent_name', 'verifier_name', 'status'
        ], Lead::class);

        $this->info('Import complete!');
        return 0;
    }

    private function importTable($sql, $table, $columns, $modelClass)
    {
        // Improved regex: match multi-line, multi-row INSERTs for this table
        $pattern = '/INSERT INTO `'.$table.'` \\(([^)]+)\\) VALUES\s*((?:\([^;]+\))+);/ims';
        preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);
        $count = 0;
        foreach ($matches as $match) {
            $valuesBlock = $match[2];
            // Split on '),(' but handle first and last parens
            $rows = preg_split("/\\),\\s*\\(/", trim($valuesBlock, '()'));
            foreach ($rows as $row) {
                $row = trim($row);
                // Remove any escaped single quotes
                $row = str_replace("\\'", "'", $row);
                // Split by comma, but handle quoted strings
                $fields = $this->parseRow($row);
                if (count($fields) !== count($columns)) continue;
                $data = array_combine($columns, $fields);
                // Remove MySQL NULLs
                foreach ($data as $k => $v) {
                    if ($v === 'NULL' || $v === null) $data[$k] = null;
                    else $data[$k] = trim($v, "'");
                }
                // Insert or update
                $modelClass::updateOrCreate(['id' => $data['id']], $data);
                $count++;
            }
        }
        $this->info("Imported $count rows into $table");
    }

    // Parse a row of values, handling quoted strings and commas
    private function parseRow($row)
    {
        $fields = [];
        $inString = false;
        $current = '';
        $len = strlen($row);
        for ($i = 0; $i < $len; $i++) {
            $char = $row[$i];
            if ($char === "'" && ($i === 0 || $row[$i-1] !== '\\')) {
                $inString = !$inString;
                $current .= $char;
            } elseif ($char === ',' && !$inString) {
                $fields[] = $current;
                $current = '';
            } else {
                $current .= $char;
            }
        }
        $fields[] = $current;
        return $fields;
    }
}
