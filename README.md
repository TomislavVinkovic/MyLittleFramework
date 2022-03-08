# Factory Praksa Zadatak

## The magician
With the help of the magician and its mystical powers, we can do a bunch of cool stuff!

To migrate all migrations to the database, type `php magician.php migrate` in the console. It will truly migrate EVERYTHING. Currently it does not track migrations.
To migrate a specific migrations, type `php magician.php migrate <migrationName>` in the console.
To reset all migrations, type `php magician.php resetMigrations`.
To roll back a specific migration, type `php magician.php rollBack <migrationName>`.
Type `php magician.php help` to see all magician commands.