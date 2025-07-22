# MyLittleFramework

A simple project that I had to do during my time as an intern at Factory.
The framework has bugs and it is laregly unfinished (because of the last few features, the app is not working correctly).
However, it is a cool little project to showcase various PHP features.

## The magician, a helpful command line tool
With the help of the <b>magician</b> and its mystical powers, we can do a bunch of cool stuff!<br /><br />

To migrate all migrations to the database, type `php magician.php migrate` in the console. It will truly migrate EVERYTHING. Currently it does not track migrations.<br />
To migrate a specific migrations, type `php magician.php migrate <migrationName>` in the console.<br />
To reset all migrations, type `php magician.php resetMigrations`.<br />
To roll back a specific migration, type `php magician.php rollBack <migrationName>`.<br />
Type `php magician.php help` to see all magician commands.
