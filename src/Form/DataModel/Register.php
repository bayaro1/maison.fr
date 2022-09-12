<?php
namespace App\Form\DataModel;

use App\Entity\City;
use App\Entity\Picture;
use App\Entity\Category;
use App\Validator\UniqueUserEmail;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Register
{
    #[UniqueUserEmail()]
    #[Assert\Email(message: '{{value}} n\'est pas une addresse email correcte')]
    #[Assert\NotBlank(message: 'l\'adresse email est obligatoire')]
    private ?string $email;

    
    #[Assert\EqualTo(propertyPath: 'passwordConfirm', message: '')]
    #[Assert\NotBlank(message: 'le mot de passe est obligatoire')]
    #[Assert\Length(min: 6, minMessage: 'le mot de passe doit comporter au moins 6 caractères')]
    private ?string $password;

    #[Assert\EqualTo(propertyPath: 'password', message: 'Les deux mots de passe ne sont pas identiques')]
    private ?string $passwordConfirm;

    #[Assert\NotBlank(message: 'Le nom de l\'entreprise est obligatoire')]
    private ?string $businessName;

    
    #[Assert\NotBlank(message: 'Le nom du contact est obligatoire')]
    private ?string $contactName;

    
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire')]
    private ?string $phone;

    private ?array $imageFiles;

    /** 
     * @var null|Picture[]
     */
    private ?array $pictures;

    #[Assert\Collection(
        fields: [
            0 => new Assert\Type(Category::class)
        ], 
        allowExtraFields: true,
        missingFieldsMessage : 'Vous devez choisir au moins une catégorie'
    )]
    private ?ArrayCollection $categories;

    #[Assert\NotBlank(message: 'Vous devez choisir votre ville')]
    private ?City $city;

    #[Assert\NotBlank(message: 'Vous devez choisir au moins un département')]
    private ?array $departments;


    /**
     * Get the value of departments
     */ 
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set the value of departments
     *
     * @return  self
     */ 
    public function setDepartments($departments)
    {
        $this->departments = $departments;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    

    /**
     * Get the value of pictures
     *
     * @return  null|Picture[]
     */ 
    public function getPictures()
    {
        return $this->pictures;
    }

    
    /**
     * Get the value of imageFiles
     */ 
    public function getImageFiles()
    {
        return $this->imageFiles;
    }


    /**
     * Set the value of imageFiles
     *
     * @return  self
     */ 
    public function setImageFiles($imageFiles)
    {
        foreach($imageFiles as $imageFile)
        {
            $picture = new Picture;
            $picture->setImageFile($imageFile);
            $this->pictures[] = $picture;
        }
        $this->imageFiles = $imageFiles;
        return $this;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of contactName
     */ 
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set the value of contactName
     *
     * @return  self
     */ 
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get the value of businessName
     */ 
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * Set the value of businessName
     *
     * @return  self
     */ 
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Get the value of passwordConfirm
     */ 
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * Set the value of passwordConfirm
     *
     * @return  self
     */ 
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Get the value of categories
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     *
     * @return  self
     */ 
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }
}