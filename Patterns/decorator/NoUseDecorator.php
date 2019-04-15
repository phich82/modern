<?php 

/** ====== Main System ===== */
interface INotify
{
    public function send($subject, $template, $data);
}

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

/** ===== Fake Package Mail ====== */
class FakeMailPackage
{
    public static function send($subject, $template, $data) {
        echo 'Send mail from the fake MailPackage.';
    }
}

/** ===== Fake Package SmsPackage ====== */
class FakeSmsPackage
{
    public static function send($subject, $template, $data) {
        echo 'Send sms from the fake SmsPackage.';
    }
}

/** ===== Fake Package SlackPackage ====== */
class SlackPackage
{
    public static function send($subject, $template, $data) {
        echo 'Send slack from the fake SlackPackage.';
    }
}

/** Implement for each interface */
class Mail implements IMail
{
    public function send($subject, $template, $data) {
        FakeMailPackage::send($subject, $template, $data);
    }
}

/** Implement for each interface */
class Sms implements ISms
{
    public function send($subject, $template, $data) {
        FakeSmsPackage::send($subject, $template, $data);
    }
}

/** Implement for each interface */
class Slack implements ISlack
{
    public function send($subject, $template, $data) {
        FakeSmsPackage::send($subject, $template, $data);
    }
}


/** execute for only sending email (biến thể của INotify - conrete class) */
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

/** execute for sending email & sms (biến thể của INotify - conrete class) */
class MailAndSms implements INotify
{
    protected $mailer;
    protected $sms;

    public function __construct(IMail $mailer, ISms $sms) {
        $this->mailer = $mailer;
        $this->sms = $sms;
    }

    public function send($subject, $template, $data) {
        $this->mailer->send($subject, $template, $data);
        $this->sms->send($subject, $template, $data);
    }
}

/** execute for sending email & sms & slack (biến thể của INotify - conrete class) */
class MailAndSmsAndSlack implements INotify
{
    protected $mailer;
    protected $sms;
    protected $slack;

    public function __construct(IMail $mailer, ISms $sms, ISlack $slack) {
        $this->mailer = $mailer;
        $this->sms    = $sms;
        $this->slack  = $slack;
    }

    public function send($subject, $template, $data) {
        $this->mailer->send($subject, $template, $data);
        $this->sms->send($subject, $template, $data);
        $this->slack->send($subject, $template, $data);
    }
}

/**
 * Recap:
 *      - Each job, we design four parts:
 *          + Main system interface (once)
 *          + Specified Interface for the specified job
 *          + Class implemenion for this specified interface
 *          + Conrete Class for this specified job
 *      - Everything is OK, but some problems:
 *          + When adding a new job, we also repeated the codes of other jobs => violated DRY principle
 *          + Arguments (dependencies) of the construct method will be increasing when adding up new services
 *          + Morever, we also write a new class when combining many services togetger.
 */
