import sqlite3

# Connect to the SQLite database
conn = sqlite3.connect('database/database.sqlite')
c = conn.cursor()

# Get all unique DID numbers from leads
c.execute("SELECT DISTINCT did_number FROM leads WHERE did_number IS NOT NULL AND did_number != ''")
dids = set(row[0] for row in c.fetchall())

# Remove DIDs that look like addresses or invalid numbers (basic filter)
filtered_dids = [did for did in dids if did.isdigit() and 7 <= len(did) <= 15]

# Insert DIDs into dids table if not already present
for did in filtered_dids:
    c.execute("INSERT OR IGNORE INTO dids (did_number, payout_amount) VALUES (?, 0)", (did,))

conn.commit()
conn.close()
print(f"Inserted {len(filtered_dids)} DIDs into dids table.")
