# ef5-Datenbank-Projekt
A database with rudimentary UI that could theoretically be used by the traffic warden association Glarnerland
- In order to access the webpage that allows a user to access the database in an intuitive sense the user has to log in with mySQL credentials
- By default only the root user can log in. root can however add more users (only with read-write privileges) via the webpage or the command line (any privileges possible)
- The database can be deleted or created (only with correct privileges), a default set of entries and custom entries can be inserted
- The same data can't be inserted twice and the webpage won't crash if the user tries to do connect to the database after it was deleted
- If a user is not logged in the webpage will redirect to the login form
- Currently, a user cannot modify or delete entries via the webpage except for deleting the database entirely
- Modify and delete functionalities coming soon (-ish)