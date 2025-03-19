<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Contrôler que la date n'est pas postérieure à aujourd'hui lors de l'ajout/édition d'une formatio
 *
 * @author zmuku
 */
class DateValidationsTest extends KernelTestCase{

    public function getFormation(): Formation{
        return (new Formation())
        ->setTitle('Nouvelle formation')
        ->setPublishedAt(new DateTime("2026/03/21"));
    }

    # resultat attendu: erreur, date posterieur a aujourd'hui
    public function testValidationDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2026/03/21"));
        $this->assertErrors($formation,1,"erreur attendu: la date est postérieur a ajourd'hui");
    }

    public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues,$error,$message);
    }
}
