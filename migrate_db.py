import mysql.connector
import sys

# Default credentials from memory/instructions for the test environment
config = {
    'user': 'test_user',
    'password': 'password',
    'host': '127.0.0.1',
    'database': 'test_db',
    'raise_on_warnings': True
}

print("Connecting to database...")
try:
    cnx = mysql.connector.connect(**config)
    cursor = cnx.cursor()

    # Add is_super_admin
    try:
        print("Adding is_super_admin column...")
        cursor.execute("ALTER TABLE admins ADD COLUMN is_super_admin BOOLEAN NOT NULL DEFAULT FALSE AFTER role")
        print("Success.")
    except mysql.connector.Error as err:
        print(f"Skipping: {err}")

    # Add permissions
    try:
        print("Adding permissions column...")
        cursor.execute("ALTER TABLE admins ADD COLUMN permissions JSON DEFAULT NULL AFTER is_super_admin")
        print("Success.")
    except mysql.connector.Error as err:
        print(f"Skipping: {err}")

    # Update ID 1
    try:
        print("Updating Admin ID 1...")
        cursor.execute("UPDATE admins SET is_super_admin = 1 WHERE id = 1")
        cnx.commit()
        print("Success.")
    except mysql.connector.Error as err:
        print(f"Error: {err}")

    cursor.close()
    cnx.close()
    print("Migration script finished.")

except mysql.connector.Error as err:
    print(f"Connection failed: {err}")
    sys.exit(1)
except ImportError:
    print("mysql-connector-python not installed.")
