import re

input_file = "database/u806021370_crm.sql"
output_file = "database/leads_sqlite_import.sql"

# MySQL dump column order
mysql_columns = [
    "id", "created_at", "first_name", "last_name", "phone", "did_number", "campaign_name",
    "address", "city", "state", "zip", "email", "notes", "agent_name", "verifier_name", "status"
]

# SQLite column order
sqlite_columns = [
    "id", "first_name", "last_name", "phone", "address", "city", "state", "zip", "email", "notes",
    "status", "agent_name", "verifier_name", "did_number", "campaign_name", "created_at", "updated_at"
]

def split_values(value_str):
    # Split values, handling commas inside quotes
    values = []
    current = ''
    in_quotes = False
    escape = False
    for c in value_str:
        if c == "'" and not escape:
            in_quotes = not in_quotes
        if c == "\\" and not escape:
            escape = True
            continue
        if c == ',' and not in_quotes:
            values.append(current.strip())
            current = ''
        else:
            current += c
        escape = False
    values.append(current.strip())
    return values

def quote_sqlite(val):
    if val.upper() == 'NULL':
        return 'NULL'
    if val.startswith("'") and val.endswith("'"):
        # Escape single quotes inside the value for SQLite
        inner = val[1:-1].replace("'", "''")
        return "'{}'".format(inner)
    return "'{}'".format(val.replace("'", "''"))

def extract_tuples(full_stmt):
    # Find the part after VALUES and before the ending semicolon
    m = re.search(r'VALUES\s*(.*);', full_stmt, re.DOTALL)
    if not m:
        return []
    values_block = m.group(1).strip()
    # Remove leading and trailing parentheses if present
    if values_block.startswith('(') and values_block.endswith(')'):
        values_block = values_block[1:-1]
    # Split tuples by '),(' pattern
    tuples = re.split(r'\),\s*\(', values_block)
    return tuples

with open(input_file, "r", encoding="utf-8") as infile, open(output_file, "w", encoding="utf-8") as outfile:
    inside_insert = False
    insert_lines = []
    for line in infile:
        # Robust: detect INSERT INTO anywhere in the line, ignore leading whitespace
        if not inside_insert and 'INSERT INTO `leads`' in line:
            inside_insert = True
            insert_lines = [line.rstrip('\n')]
            if line.strip().endswith(';'):
                inside_insert = False
                full_stmt = ' '.join(insert_lines)
                tuples = extract_tuples(full_stmt)
                for tup in tuples:
                    values = split_values(tup)
                    row = dict(zip(mysql_columns, values))
                    sqlite_row = []
                    for col in sqlite_columns:
                        if col == "updated_at":
                            sqlite_row.append(quote_sqlite(row.get("created_at", "NULL")))
                        else:
                            sqlite_row.append(quote_sqlite(row.get(col, "NULL")))
                    outfile.write(
                        "INSERT INTO leads ({}) VALUES ({});\n".format(
                            ", ".join(sqlite_columns),
                            ", ".join(sqlite_row)
                        )
                    )
        elif inside_insert:
            insert_lines.append(line.rstrip('\n'))
            if line.strip().endswith(';'):
                inside_insert = False
                full_stmt = ' '.join(insert_lines)
                tuples = extract_tuples(full_stmt)
                for tup in tuples:
                    values = split_values(tup)
                    row = dict(zip(mysql_columns, values))
                    sqlite_row = []
                    for col in sqlite_columns:
                        if col == "updated_at":
                            sqlite_row.append(quote_sqlite(row.get("created_at", "NULL")))
                        else:
                            sqlite_row.append(quote_sqlite(row.get(col, "NULL")))
                    outfile.write(
                        "INSERT INTO leads ({}) VALUES ({});\n".format(
                            ", ".join(sqlite_columns),
                            ", ".join(sqlite_row)
                        )
                    )

print("Transformation complete. Output written to", output_file)
