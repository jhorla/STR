GUIDE TO USE DATABASE:

    • Pre-requisites:
        > Installed XAMPP
        > Working internet connection

    • How to Create Database:

        1. Set-up XAMPP:
            1. Open XAMPP.
            2. Click "Start" buttons for modules "Apache" and "MySQL".

        2. Initialize database:
            1. Go to http://127.0.0.1/phpmyadmin/.
            2. On the right column, click on "New".
            3. Under "Create database", type "patientdb" as the Database name, then click "Create".
            4. Select the database (patientdb) on the right column.
            5. On the page for the database, click on the "SQL" column.
            6. Copy the syntax indicated in the file below: 
                IF YOU WOULD LIKE INITIAL PATIENT TEST DATA IN YOUR DB:
                    > Go to folder "LOGS\V1.57 DB.sql"
                IF YOU WOULD LIKE NO PATIENT DATA IN YOUR DB:
                    > Go to folder "LOGS\DB Empty.sql"
                    Note: if you would like to quickly insert data, this file is a duplicate of "LOGS\V1.57 DB.sql", but deleted lines 106 to 207 which concern input of patient data, so study the syntax of those specific lines in the specified file
            7. Paste all text from chosen file under the text box provided in the "SQL" section in http://127.0.0.1/phpmyadmin/index.php?route=/database/sql&db=patientdb.
            8. Run code by pressing "Ctrl + Enter".

    • How to Set-up Site:

        1. Extract the downloaded file.
        2. Cut the folder to xampp\htdocs.

    • How to Run Database:
        * Make sure that you have completed the site and database set-up.

        1. Set-up XAMPP:
            1. Open XAMPP.
            2. Click "Start" buttons for modules "Apache" and "MySQL".

        2. Go to preferred browser, type in link "127.0.0.1/MedicalDatabase/index".
