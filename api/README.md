# yBank Laravel API    
    
## Folter Structure:   
- `app/Models` (Eloquent Models)    
- `app/Repositories` (Repositories, by default for Eloquent)    
- `app/Services` 
- `app/Http/Requests` (Request Validation)   
- `routes/api.php` (API's endpoints)   


## Import note on dependencies on the controllers and services:    
    
The files `TransactionController`, `AccountController` and the `TransferService` file both uses Laravel's Service Container to auto manage depencies, by default the repositories were created for Eloquent, but it can be easily changed on the file `app/Providers/AppServiceProvider.php`    

 Example:    
  

    $this->app->bind('path/to/interface', 'path/to/concrete/class')

## Usage    
    
To start working on the project:    
    
  

     $ composer install
     $ cp .env.local.dist .env.local

   
  if  the application asks for a encription key:    
   
  

     $ php artisan key:generate  

  An empty database is no fun, so you might want to do this:    
    
first, update your `.env` file with your database credentials, then run the command below:        
    
  

     $ php artisan migrate:fresh --seed  

IMPORTANT: the command above is a combination of Laravel's migration (artisan migrate) and the seeder (artisan db:seed), this command will drop all the tables (specified in the migration) in the databse, create it and then it will insert some sample data (seeds)    
    
more details on folders:    
  

     database/migrations/ 
     database/seeds/

  
## Running the project with built-in PHP server
    $ php artisan serve
usually starts the project at `http://127.0.0.1:8000`

## Running tests    
    
To run the project tests    
    
     $ composer test   

To run specific tests you can use `--filter` option:    
    
    $ vendor/bin/phpunit --filter=ClassName::MethodName
