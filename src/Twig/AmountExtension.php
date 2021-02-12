<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension{
    public function getFilters()
    {
        return [
            // Ce twig filter va prendre en 1 le nom du filtre qu'on utilise dans le twig ensuite un collabble $this, 'amount'
            new TwigFilter('amount', [$this, 'amount'])
        ];

        
         }
         public function amount($value, string $symbol ="€", string $decsep = ',' ,  string $thounsandsep = ' '){
          $finalValue = $value / 100;

          //$finalValue = number_format($finalValue, 2, ',', ' ');
          $finalValue = number_format($finalValue, 2, $decsep, $thounsandsep);

         // return $finalValue . ' €';
          return $finalValue . ' ' . $symbol;
    }
}