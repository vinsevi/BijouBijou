<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        //on instancie 10 objets
        for($i=1;$i<11;$i++):
          $article= new Article();//on instancie un nouvel objet hérité de la classe\App\Entity\Article à chaque tour de boucle, pour autant d'articles instanciés, il y aura un insert supplémentaire en BDD

            $article->setNom("Article N°".$i)
                    ->setPrix (rand(100,400))
                    ->setDateCrea(new \DateTime())
                    ->setRef("ref".$i)
                        ->setPhoto("https://picsum.photos/600/".$i);

//ici on habille nos objets nus instanciés précédeent avec des setter de nos différentes propriétéshéritées de notre objet article entity.
        $manager->persist($article);// persist est une methode issue de classe ObjectManager qui pemet de garder en mémoire les objets articles crées précédemment et de préparer et binder le requetes(valeurs à insérer) avant insertion

        endfor;







        $manager->flush();
    } //flush est une méthode d'Object Manager qui permet d'exécuter les requetes préparées lors du persist() et permet ainsi les inserts en BDD

    //une fois réaliser il faut charger les Fixtures en BDD grâce à DOCTRINE avec la commande suivante : php bin/console doctrine:fixtures:load
}
