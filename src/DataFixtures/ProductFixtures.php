<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private $name = ['iPhone', 'Samsung', 'Huawei'];
    private $colors = ['blanc', 'noir', 'rouge', 'bleu', 'gris', 'jaune'];

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 20; $i++) {
            $product = new Product();
            $productName = $this->name[rand(0, count($this->name)-1)];
            $productColor = $this->colors[rand(0, count($this->colors)-1)];
            if ($productName == 'iPhone'){
                $product->setName($productName . ' ' . rand(3, 11));
                $product->setBrand('Apple');
            }elseif ($productName == 'Samsung'){
                $product->setName($productName . ' S' . rand(1, 10));
                $product->setBrand('Samsung');
            }else{
                $product->setName($productName . ' Y' . rand(1, 9));
                $product->setBrand('Huawei');
            }
            $product->setColor($productColor);
            $product->setPrice(rand(50000, 150000)/100);
            $product->setDescription('Un superbe ' . $productName. ' de couleur ' . $productColor . ' avec plus de ' . rand(20, 50) . ' fonctionnalités.');

            $manager->persist($product);
        }

        $manager->flush();
    }
}
