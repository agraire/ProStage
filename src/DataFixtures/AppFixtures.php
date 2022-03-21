<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\Stage;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //création de 2 utilisateurs
        $antoine = new User();
        $antoine -> setPrenom("Antoine");
        $antoine -> setNom("Graire");
        $antoine -> setEmail("antoine@gmail.com");
        $antoine -> setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $antoine -> setPassword("$2y$10$5WyaegSfiSZUNjwxWUBEp.foaMG6SCrx6KS5SI1hLd43OxD8LLK..");
        $manager -> persist($antoine);

        $luc = new User();
        $luc -> setPrenom("Luc");
        $luc -> setNom("Leydert");
        $luc -> setEmail("luc@gmail.com");
        $luc -> setRoles(['ROLE_USER']);
        $luc -> setPassword('$2y$10$vGoD7FFz9O8mbrprtccGvOxIb8Z0yXJhd4NFg8FfSAUzaAHUprxAK');
        $manager -> persist($luc);



        $faker = \Faker\Factory::create('fr_FR');


        for ($i = 0; $i < 20; $i++) {
            $nom = $faker->company;
            $entreprise = new Entreprise();
            $entreprise->setNom($nom);
            $entreprise->setActivite($faker->realText());
            $entreprise->setSite("https://www.$nom.com"); // $faker->url ne marchait pas, il y avait l'erreur "join(): Argument #2 ($array) must be of type ?array, string given"
            $entreprise->setAdresse($faker->address);

            $entreprises[] = $entreprise;
            $manager->persist($entreprise);
        }


        $modulesFormation = array(
            "DUT Info" => "Diplôme Universitaire de Technologique Informatique",
            "LP Multimédia" => "Licence Professionelle Multimédia",
            "DUT IC" => "Diplôme Universitaire de Technologique Information et Communication"
        );

        foreach ($modulesFormation as $nomC => $nomL) {
            $formation = new Formation();
            $formation->setNom($nomC);
            $formation->setNomComplet($nomL);

            for ($numStage = 0; $numStage < 10; $numStage++) {
                $stage = new Stage();
                $stage->setTitre($faker->realText(40));
                $stage->setContact($faker->email);
                $stage->setMissions($faker->realText());

                $stage->addFormation($formation);

                $numEntreprise = $faker->numberBetween($min = 0, $max = 19);

                $stage->setEntreprise($entreprises[$numEntreprise]);
                $entreprises[$numEntreprise]->addStage($stage);

                $manager->persist($stage);
                $manager->persist($entreprises[$numEntreprise]);
            }
            $manager->persist($formation);
        }


        $manager->flush();
    }
}
