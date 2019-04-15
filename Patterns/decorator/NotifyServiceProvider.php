<?php 

class NotifyServiceProvider
{
    public function register() {
        $this->app->singleton('IMail', function ($app) {
            return new Mail();
        });

        $this->app->singleton('ISms', function ($app) {
            return new Sms();
        });

        $this->app->singleton('ISlack', function ($app) {
            return new Slack();
        });

        $this->app->singleton('ISkype', function ($app) {
            return new Skype();
        });

        $this->app->singleton('INotify', function ($app) {
            //return new MailOnly();           // only send email
            //return new MailAndSms();         // send email & sms
            //return new MailAndSmsAndSlack(); // send email & sms & slack
            
            return new SmsJob(new MailOnly());     // send email & sms
            //return new SlackJob(new MailOnly());   // send email & slack
            //return new SkypeJob(new MailOnly());   // send email & skype
            //return new SlackJob(new SkypeJob(new MailOnly()));             // send email & skype & slack
            //return new SlackJob(new SkypeJob(new SmsJob(new MailOnly()))); // send email & sms & skype & slack
        });
    }
}