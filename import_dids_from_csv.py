import sqlite3
import csv

# Path to your SQL/CSV file with DID, Owner, and Payout ($)
# For this example, let's assume you have a CSV file: dids_import.csv
# Columns: did_number, owner_campaign, payout_amount

conn = sqlite3.connect('database/database.sqlite')
c = conn.cursor()

with open('dids_import.csv', newline='') as csvfile:
    reader = csv.DictReader(csvfile)
    for row in reader:
        did = row['did_number'].strip()
        owner = row['owner_campaign'].strip()
        payout = float(row['payout_amount']) if row['payout_amount'] else 0
        # Insert or update DID with payout and owner, campaign_payout default 0
        c.execute("""
            INSERT INTO dids (did_number, payout_amount, owner_campaign, campaign_payout)
            VALUES (?, ?, ?, 0)
            ON CONFLICT(did_number) DO UPDATE SET payout_amount=excluded.payout_amount, owner_campaign=excluded.owner_campaign
        """, (did, payout, owner))

conn.commit()
conn.close()
print("DIDs imported/updated from CSV.")
