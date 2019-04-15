<?php 

/** ============================ EXTERNAL PACKAGES ============================ */
/** ===== Fake Package Mail (external package) ====== */
class FakeMailPackage
{
    public static function send($subject, $template, $data) {
        echo "Send from the fake MailPackage.\n";
    }
}

/** ===== Fake Package SmsPackage (external package) ====== */
class FakeSmsPackage
{
    public static function send($subject, $template, $data) {
        echo "Send from the fake SmsPackage.\n";
    }
}

/** ===== Fake Package SlackPackage (external package) ====== */
class FakeSlackPackage
{
    public static function send($subject, $template, $data) {
        echo "Send from the fake SlackPackage.\n";
    }
}

/** ===== Fake Package SkypePackage (external package) ====== */
class FakeSkypePackage
{
    public static function send($subject, $template, $data) {
        echo "Send from the fake SkypePackage.\n";
    }
}
/** ============================ EXTERNAL PACKAGES ============================ */

/** ====== 1. Main System Interface ===== */
interface INotify
{
    public function send($subject, $template, $data);
}
/** ====== End - Main System Interface ===== */

/** ====== 2. Interfaces For Each Specified Service ===== */
interface IMail
{
    public function send($subject, $template, $data);
}

interface ISms
{
    public function send($subject, $template, $data);
}

interface ISlack
{
    public function send($subject, $template, $data);
}

interface ISkype
{
    public function send($subject, $template, $data);
}
/** ====== End - Interfaces For Each Specified Service ===== */

/** ====== 3. Implementions For Each Specified Service ===== */
/** Implement for each interface when using the external package */
class Mail implements IMail
{
    public function send($subject, $template, $data) {
        FakeMailPackage::send($subject, $template, $data);
    }
}

/** Implement for each interface when using the external package */
class Sms implements ISms
{
    public function send($subject, $template, $data) {
        FakeSmsPackage::send($subject, $template, $data);
    }
}

/** Implement for each interface when using the external package */
class Slack implements ISlack
{
    public function send($subject, $template, $data) {
        FakeSlackPackage::send($subject, $template, $data);
    }
}

/** Implement for each interface when using the external package */
class Skype implements ISkype
{
    public function send($subject, $template, $data) {
        FakeSkypePackage::send($subject, $template, $data);
    }
}
/** ====== End - Implementions For Each Specified Service ===== */


/** ====== 4. Execute For Each Specified Job (conrete classes) ====== */
/** execute For Only Sending Email (biến thể của INotify) */
class MailOnly implements INotify
{
    protected $mailer;

    public function __construct(IMail $mailer) {
        $this->mailer = $mailer;
    }

    public function send($subject, $template, $data) {
        $this->mailer->send($subject, $template, $data);
    }
}

/** execute for sending from sms (biến thể của INotify) */
class SmsJob implements INotify
{
    protected $notifier;
    protected $sms;

    public function __construct(INotify $notifier, ISms $sms) {
        $this->notifier = $notifier;
        $this->sms = $sms;
    }

    public function send($subject, $template, $data) {
        $this->notifier->send($subject, $template, $data);
        $this->sms->send($subject, $template, $data);
    }
}

/** execute for sending from slack (biến thể của INotify) */
class SlackJob implements INotify
{
    protected $notifier;
    protected $slack;

    public function __construct(INotify $notifier, ISlack $slack) {
        $this->notifier = $notifier;
        $this->slack  = $slack;
    }

    public function send($subject, $template, $data) {
        $this->notifier->send($subject, $template, $data);
        $this->slack->send($subject, $template, $data);
    }
}

/** execute for sending from skype (biến thể của INotify) */
class SkypeJob implements INotify
{
    protected $notifier;
    protected $skyper;

    public function __construct(INotify $notifier, ISkype $skyper) {
        $this->notifier = $notifier;
        $this->skyper = $skyper;
    }

    public function send($subject, $template, $data) {
        $this->notifier->send($subject, $template, $data);
        $this->skyper->send($subject, $template, $data);
    }
}
/** ====== End - Execute For Each Specified Job (conrete classes) ====== */

/**
 * Recap:
 *      - When using Decorator pattern:
 *          + Add new services easily without copying codes from the other service (only write a new class for this service)
 *          + To use many services at once, we only assemble the single services together (without writing any the conrete class) 
 */

echo "Using Email:\n";
$email = new MailOnly(new Mail());
$email->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;

echo "Using Sms:\n";
$sms = new Sms();
$sms->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;

echo "Using Slack:\n";
$sms = new Slack();
$sms->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;

echo "Using Skype:\n";
$sms = new Skype();
$sms->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
echo "Using Email & Sms:\n";
$sms_email = new SmsJob(new MailOnly(new Mail()), new Sms());
$sms_email->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;

echo "Using Email & Slack:\n";
$sms_email = new SlackJob(new MailOnly(new Mail()), new Slack());
$sms_email->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;

echo "Using Email & Skype:\n";
$sms_email = new SkypeJob(new MailOnly(new Mail()), new Skype());
$sms_email->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;
//////////////////////////////////////////////////////////////////////////////

echo "Using Email & Sms & Slack:\n";
$sms_email_slack = new SlackJob(new SmsJob(new MailOnly(new Mail()), new Sms()), new Slack());
$sms_email_slack->send('Message from service', 'Hello World', ['email' => 'email']);
echo PHP_EOL;

echo "Using Email & Sms & Slack & Skype:\n";
$sms_email_slack_skype = new SkypeJob(new SlackJob(new SmsJob(new MailOnly(new Mail()), new Sms()), new Slack()), new Skype());
$sms_email_slack_skype->send('Message from service', 'Hello World', ['email' => 'email']);
