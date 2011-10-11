HatimeriaNewsletterBundle
=========================

## Installation

### Step1: Add HatimeriaNewsletterBundle to you vendors

**Using vendors script**

Add these code to your Symfony 2 `deps` file:

```
[HatimeriaNewsletterBundle]
    git=git://github.com/hatimeria/HatimeriaNewsletterBundle.git
    target=/bundles/Hatimeria/NewsletterBundle
```

and run symfony's vendor script to download it automatically:

``` bash
$ php bin/vendors install
```

**Using submodules**

Use following commands:

``` bash
$ git submodule add git://github.com/hatimeria/HatimeriaNewsletterBundle.git vendor/bundles/Hatimeria/NewsletterBundle
$ git submodule update --init
```

### Step2: Add Hatimeria namespace to your autoloader configuration

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Hatimeria' => __DIR__.'/../vendor/bundles',
));
```

### Step3: Enable bundle in your AppKernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Hatimeria\NewsletterBundle\HatimeriaNewsletterBundle(),
    );
}
```

### Step4: Bundle configuration

Default bundle configuration:

``` yaml
# app/config/config.yml
hatimeria_newsletter:
    sender: ~
    recipient_provider: ~
    manager: hatimeria_newsletter.manager.default
    mailing_services: ~
```

Parameter `sender` is required and it is email address string.

### Step5: Create database schema:

For now HatimeriaNewsletterBundle provides only ORM configuration. If you want to add tables provided by bundle, just
rebuild your docitrne model using these command:

``` bash
$ php app/console doctrine:schema:update --force
```

### Step6: Add cyclic commands to your contab

If you want your application to send newsletter automatically, you must add HatimeriaNewsletterBundle commands.
To do these you must open your crontab:

``` bash
crontab -e
```

You will see configuration similar to these code or empty editor if no crontab configuration was provided before:

``` bash
* * * * *  /home/user/some/script/to/run
```

Add those lines and configure execution time as you wish:

``` bash
*/5 * * * *  php /home/path/to/project/app/console hatimeria:newsletter:send_queue --limit=10
0 0 * * *  php /home/path/to/project/app/console hatimeria:newsletter:prepare_mailing --type="daily"
* * * * *  php /home/path/to/project/app/console hatimeria:newsletter:prepare_mailing
```

First line says that you want send 10 available (not yet sent) messages from queue every 5 minutes. These configuration
us up to you as others.
Second line says send every midnight mailings supporting `daily` schedule type.
And third every minute check if we don't have messages to send from mailing supporting `default` schedule type

You configure schedule types so the --type command option is specified by you.

### Step7a: Enable simple mailing provided by HatimeriaNewsletterBundle:

To enable simple mailing you must configure recipeint_provider service implementing `MailingRecipientsProviderInterface`

For example:
``` php
<?php

namespace Acme\DemoBundle\Entity;

use Hatimeria\NewsletterBundle\Recipient\MailingRecipientProviderInterface;

class UserManager implements MailingRecipientProviderInterface
{
    public function findRecipients()
    {
        reutnr array('user1@test.com', 'user2@test.com');
    }
    
}
```

Class must be configured as a dependency injection service.

And `config.yml` configuration:

``` yaml
# app/config/config.yml
hatimeria_newsletter:
    sender: newsletter@project.com
    recipient_provider: acme.user_manager # service id
```

**Note:**
> Your recipient_provider class can return collection of email addresses strings or
> collection of objects implementing `MailingRecipientInterface`

### Step7b: Creating own mailing class

HatimeriaNewsletterBundle allows user specified services to send messages as well.

First of all we must create service class:

``` php
<?php

namespace Acme\DemoBundle\Mailing;

use Hatimeria\NewsletterBundle\Mailing\MailingInterface;

class NewsMailing implements MailingInterface
{
    protected $templating;

    protected $supportedScheduleTypes;

    protected $userRepository;

    public function __construct($userRepository, $templating)
    {
        $this->templating     = $templating;
        $this->userRepository = $userRepository;
        $this->supportedScheduleTypes = array(
            'daily',
            'weekly'
        );
    }
    /**
     * Gets collection of email address or objects implementing MailingRecipientInterface
     *
     * @param string $scheduleType
     * @return void
     */
    public function getRecipients($scheduleType)
    {
        switch ($scheduleType)
        {
            case 'daily':
                return $this->userRepository->findDailyReaders();
                break;
            case 'weekly':
                return $this->userRepository->findWeeklyReaders();
                break;
        }
    }
    
    /**
     * Gets body of mailing.
     *
     * Recipient is provided for more complex body generation
     *
     * @param mixed $recipient
     * @return void
     */
    public function getBody($recipient)
    {
        return $this->templating->render('::newsletter.html.twig', array('recipient' => $recipient))
    }
    
    /**
     * Gets subject of mailing.
     *
     * Recipient is provided for more complex subject generation
     *
     * @param mixed $recipient
     * @return void
     */
    public function getSubject($recipient)
    {
        return 'Newsletter from project.com';
    }
    
    /**
     * Tells if mailing service supports provided schedule type
     *
     * @param string $type
     * @return void
     */
    public function supportsSchedule($type)
    {
        return in_array($type, $this->supportedScheduleTypes);
    }
    
    /**
     * Finalize mailing process
     *
     * Method is called after all recipients messages was queued
     *
     * @return void
     */
    public function finalize()
    {
    
    }
}
```
You must add your new service to services configuration:

``` xml
<service id="acme.news_mailing" class="Acme\DemoBundle\Mailing\NewsMailing">
    <argument type="service" id="acme.user_manager" />
    <argument type="service" id="templating" />
</service>
```

And configure HatimeriaNewsletterBundle to use our newly created service:

``` yaml
# app/config/config.yml
hatimeria_newsletter:
    sender: newsletter@test.com
    mailing_services:
        - acme.news_mailing
```