<?php
namespace App\Tests\Form\DataModel;

use App\Entity\Category;
use App\Form\DataModel\Search;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SearchTest extends KernelTestCase
{
    protected ValidatorInterface $validator;

    public function setUp():void 
    {
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    protected function createSearch(): Search
    {
        return (new Search)
                ->setCategory(new Category)
                ->setCity('example')
                ;
    }
    protected function assertHasErrors(int $expectedErrors, Search $search)
    {
         /** @var ConstraintViolation[] */
         $errors = $this->validator->validate($search);
         $messages = [];
         foreach($errors as $error)
         {
             $messages[] = $error->getMessage();
         }
         $this->assertCount($expectedErrors, $errors, implode(', ', $messages));
    }
    public function testSearchWithBlankCity()
    {
        $search = $this->createSearch()
                        ->setCity('')
                        ;
        $this->assertHasErrors(1, $search);
    }
    public function testSearchWithNotCorrectCategory()
    {
        $search = $this->createSearch()
                        ->setCategory(null)
                        ;
        $this->assertHasErrors(1, $search);
    }
    public function testSearchWithCorrectsAttributes()
    {
        $this->assertHasErrors(0, $this->createSearch());
    }
}