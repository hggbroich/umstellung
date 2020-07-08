<?php

namespace App\Menu;

use DateTime;
use Knp\Menu\FactoryInterface;
use SchoolIT\CommonBundle\Helper\DateHelper;

class Builder {

    private $date;
    private $dateHelper;

    private $factory;

    public function __construct(string $date, DateHelper $dateHelper, FactoryInterface $factory) {
        $this->date = new DateTime($date);
        $this->dateHelper = $dateHelper;
        $this->factory = $factory;
    }

    public function mainMenu() {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav mr-auto');

        $menu->addChild('Start', [
            'route' => 'index'
        ])
            ->setAttribute('icon', 'fa fa-home');

        if($this->dateHelper->getToday() >= $this->date) {
            $menu->addChild('Neue Eltern', [
                'route' => 'tutorial_new_parents'
            ]);

            $menu->addChild('Bestehende Eltern', [
                'route' => 'tutorial_existing_parents'
            ]);

            $menu->addChild('Neue Schülerinnen/Schüler', [
                'route' => 'tutorial_new_students'
            ]);

            $menu->addChild('Bestehende Schülerinnen/Schüler', [
                'route' => 'tutorial_existing_students'
            ]);
        }

        return $menu;
    }

    public function servicesMenu() {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        $menu = $root->addChild('services', [
            'label' => ''
        ])
            ->setAttribute('icon', 'fa fa-th')
            ->setExtra('menu', 'services')
            ->setExtra('pull-right', true)
            ->setAttribute('title', 'Online-Dienste');

        $menu->addChild('Homepage', [
            'uri' => 'https://www.hgg-broich.de'
        ])
            ->setAttribute('title', 'Zur HGG Homepage');

        $menu->addChild('Office 365', [
            'uri' => 'https://portal.office.com'
        ])
            ->setAttribute('title', 'Zu Office 365');

        return $root;
    }
}