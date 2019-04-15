<?php 

class ServiceProvider
{
    public function register() {
        $this->app->singleton('ISocial', function ($app) {
            //return new Facebook();
            return new TwitterAdapter(new Twitter());
        });
    }
}