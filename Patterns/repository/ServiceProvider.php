<?php 

class ServiceProvider
{
    public function register() {
        //$this->app->bind('IBookRepository', 'BookRepository');

        $this->app->bind(IBookRepository::class, function($app){
            $bookRepo = new BookRepository(new Book());
            return new BookCacheRepository($bookRepo, $this->app['cache.store']);
        });
    }
}