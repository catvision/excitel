# PHP Backend Developer Interview Task

PHP Backend Developer Interview Task for Excitel

## Table of Contents
- [About](#about)
- [Installation](#installation)
- [Usage](#usage)
- [Manual Installation](#manual-installation)

## About
This project uses a cron script to pull data from an external API (mockable). 
- **New records** are added.
- **Missing records** are marked as deleted (soft delete).
- **Existing records** are updated with any changes from the API.

## Installation

```bash
# Clone this repository
git clone https://github.com/catvision/excitel

# Go to the project directory
cd excitel

# Use Docker
cd docker
docker-compose up --build

# Installing Vite for React frontend
cd frontend
npm install

# Run the React part
# This process must be active all the time otherwise the React app will not work
# You can just open a new WSL window, PowerShell, or use pm2
cd frontend
npm run dev
```

## Usage

### Access the Application
You should be able to access the application at [http://localhost:8080/index.php](http://localhost:8080/index.php).

### API Data Handling
To refresh API data, set up a crontab to access the following URL:  
```
localhost/backend/index.php?cron
```

Alternatively, you can load this script in a browser or press the **Synchronize** button in the app.

### Modifying API Data
If you want to modify the data and test how it reflects in the database table, edit the `data.json` file located in the `/external_api/` folder.

### Crontab Example
If you'd like to set up a crontab to automatically pull API data, use the following crontab entry:
```bash
* * * * * curl -s http://localhost/backend/index.php?cron
```

This will run the script every minute. Adjust the schedule according to your needs.

## Manual Installation

If you don't want to or can't use Docker, follow these steps:

1. Install PHP 7.4 and expose the main folder to be visible via the browser.
2. Use **Import.sql** to create the database.
3. Create a database user. **IMPORTANT**: The user must have `CREATE`, `ALTER`, and `DROP` privileges.
4. Open `/backend/inc/config.php` to change settings for database connection and settings for API service
4. Run the following command in the **frontend** folder:
   ```bash
   npm install
   npm run dev
   ```
