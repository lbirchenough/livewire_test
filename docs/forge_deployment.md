# Forge Deployment (SQLite)

1. Set environment variables in Forge environment editor:
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=/home/forge/<site-name>/storage/database.sqlite
   ```

2. Create the SQLite file as the forge user:
   ```bash
   touch /home/forge/<site-name>/storage/database.sqlite
   chown forge:forge /home/forge/<site-name>/storage/database.sqlite
   chmod 664 /home/forge/<site-name>/storage/database.sqlite
   ```

3. Deploy from the Forge dashboard (runs migrations automatically).

4. SSH in and seed:
   ```bash
   php artisan migrate:fresh --seed --force
   ```
