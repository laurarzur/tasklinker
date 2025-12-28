<?php

namespace App\Factory;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Employe>
 */
final class EmployeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    #[\Override]    public static function class(): string
    {
        return Employe::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]    protected function defaults(): array|callable
    {
        return function () {
            $prenom = self::faker()->firstName();
            $nom = self::faker()->lastName();
            $slugger = new AsciiSlugger();

            return [
                'dateEntree' => self::faker()->dateTime(),
                'email' => strtolower(sprintf(
                    '%s.%s@example.com',
                    $slugger->slug($prenom),
                    $slugger->slug($nom)
                )),
                'nom' => $nom,
                'prenom' => $prenom,
                'statut' => self::faker()->randomElement(['CDD', 'CDI', 'Freelance']),
            ];
        };
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Employe $employe): void {})
        ;
    }
}
