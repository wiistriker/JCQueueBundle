JCQueueBundle
=============

Queue bundle with Zend_Queue support

### Configure the bundle
```yaml
# app/config/config.yml
jc_queue:
    queues:
        my_simple_queue:
            backend: db
            service: 'my_simple_queue_worker'
            
services:
  my_simple_queue_worker:
        class: Acme\DemoBundle\Queue\SimpleQueueWorker
        calls:
             - [ setMailer, [ @mailer ] ]
```

### Add new message to queue

```php
class DefaultController extends Controller
{
    public function someAction()
    {
        ...
        $queue = $this->get('jc_queue.manager')->getQueue('my_simple_queue');
        $queue->send('hello world');
        ...
    }
}
```

### Process queue

```php
<?php

namespace Acme\DemoBundle\Queue\;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimpleQueueWorker
{
    protected $mailer;

    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    public function process($queue, $count, InputInterface $input, OutputInterface $output)
    {
        foreach ($queue->receive($count) as $message) {
            try {
                $body = $message->body;
                
                //do something with message
                
                $queue->deleteMessage($message); //delete message from queue
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }
}
```

Process queue
```
php app/console jc_queue:process my_simple_queue
```