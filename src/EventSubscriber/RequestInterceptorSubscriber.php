<?php

namespace App\EventSubscriber;

use DateTime;
use SchoolIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RequestInterceptorSubscriber implements EventSubscriberInterface {

    private const DefaultRoute = 'index';

    private $date;
    private $dateHelper;
    private $urlGenerator;

    public function __construct(string $date, DateHelper $dateHelper, UrlGeneratorInterface $urlGenerator) {
        $this->date = new DateTime($date);
        $this->dateHelper = $dateHelper;
        $this->urlGenerator = $urlGenerator;
    }

    public function onRequest(RequestEvent $event) {
        if(!$event->isMasterRequest() || $this->dateHelper->getToday() >= $this->date) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');

        if($route !== static::DefaultRoute) {
            $event->setResponse(
                new RedirectResponse($this->urlGenerator->generate(static::DefaultRoute))
            );
        }
    }

    public static function getSubscribedEvents() {
        return [
            RequestEvent::class => 'onRequest'
        ];
    }
}