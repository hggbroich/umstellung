<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;

class Builder {
    private $factory;

    public function __construct(FactoryInterface $factory) {
        $this->factory = $factory;
    }

    public function mainMenu() {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav mr-auto');

        $menu->addChild('Start', [
            'route' => 'index'
        ])
            ->setAttribute('icon', 'fa fa-home');

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