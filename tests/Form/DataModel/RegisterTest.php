<?php
namespace App\Tests\Form\DataModel;

use App\Entity\City;
use App\Form\DataModel\Register;
use App\Entity\Category;
use App\Tests\Controller\AuthenticationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterTest extends KernelTestCase
{
    use AuthenticationTrait; 

    public function testRegisterIsValid()
    {
        $this->validate(
            $this->createRegister(), 
            0
        );
    }


    public function testRegisterHasInvalidEmail()
    {
        $this->validate(
            $this->createRegister()->setEmail('email@invalide'), 
            1
        );
    }

    public function testRegisterHasExistingUserEmail()
    {
        $container = self::getContainer();
        $this->loadTestUserFixtures($container);
        $user = $this->getTestUser($container);

        $this->validate(
            $this->createRegister()->setEmail($user->getEmail()), 
            1
        );
    }

    public function testRegisterHasIncorrectPasswordConfirm()
    {
        $this->validate(
            $this->createRegister()
                    ->setPassword('password')
                    ->setPasswordConfirm('different_password'),
            2
        );
    }

    public function testRegisterHasBlankEmail()
    {
        $this->validate(
            $this->createRegister()->setEmail(''), 
            1
        );
    }

    public function testRegisterHasBlankPassword()
    {
        $this->validate(
            $this->createRegister()->setPassword('')
                                    ->setPasswordConfirm(''),
            2  // moins de 6 caractères également
        );
    }

    public function testRegisterHasTooShortPassword()
    {
        $this->validate(
            $this->createRegister()->setPassword('short')
                                    ->setPasswordConfirm('short'),
            1
        );
    }

    public function testRegisterHasBlankBusinessName()
    {
        $this->validate(
            $this->createRegister()->setBusinessName(''), 
            1
        );
    }

    public function testRegisterHasBlankContactName()
    {
        $this->validate(
            $this->createRegister()->setContactName(''), 
            1
        );
    }

    public function testRegisterHasBlankPhone()
    {
        $this->validate(
            $this->createRegister()->setPhone(''), 
            1
        );
    }

    public function testRegisterHasNoCategories()
    {
        $this->validate(
            $this->createRegister()->setCategories(new ArrayCollection()), 
            1
        );
    }

    public function testRegisterHasBadTypeCategory()
    {
        $this->validate(
            $this->createRegister()->setCategories(new ArrayCollection(['category'])), 
            1
        );
    }

    public function testRegisterHasNoCity()
    {
        $this->validate(
            $this->createRegister()->setCity(null), 
            1
        );
    }

    public function testRegisterHasNoDepartments()
    {
        $this->validate(
            $this->createRegister()->setDepartments([]), 
            1
        );
    }



    private function getValidator():ValidatorInterface
    {
        return self::getContainer()->get('validator');
    }

    private function createRegister()
    {
        return (new Register)
                    ->setEmail('email@email.com')
                    ->setPassword('password')
                    ->setPasswordConfirm('password')
                    ->setBusinessName('Entreprise du batiment')
                    ->setContactName('Jean Contact')
                    ->setCategories(new ArrayCollection([new Category]))
                    ->setCity(new City)
                    ->setPhone('0612131415')
                    ->setDepartments(['13', '64', '40'])
                    ;
    }

    private function validate(Register $register, int $expectedErrors)
    {
        $errors = $this->getValidator()->validate($register);
        $messages = [];
        foreach($errors as $error)
        {
            $messages[] = $error->getMessage();
        }
        $this->assertCount($expectedErrors, $errors, implode(', ', $messages));
    }

}