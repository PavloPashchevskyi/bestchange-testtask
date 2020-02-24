This is the test task, which is REST API Application for grabbing and parsing value rates.

To install this application on your server, in your sites directory execute

1. git clone https://github.com/PavloPashchevskyi/bestchange-testtask.git

2. cd <your_sites_directory>

3. composer install

4. create ".env.local" file from ".env" file in the same directory and change DATABASE_URL parameter in ".env.local" file to your database URL

5. php bin/console doctrine:migrations:migrate

Routes:

    http://<your_host_name>/store
        
  Route above stores in database the most expensive receiving rate
  
     http://<your_host_name>/courses
  
  Route above returns the latest available list of currency rates. May accept GET parameters to filter list by sending and/or receiving currency IDs.
  
   Example:
   
       http://<your_host_name>/courses?sending_currency_id=93&receiving_currency_id=9
         
   And, to get the latest available rate by sending currency ID and receiving currency ID follow this route:
   
     http://<your_host_name>/course/{sendingCurrencyId}/{receivingCurrencyId}