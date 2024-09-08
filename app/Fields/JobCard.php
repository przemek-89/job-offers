<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class JobCard extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $jobcard = new FieldsBuilder('jobcard', ['title' => 'Dodatkowe pola', 'position' => 'normal']);

        $jobcard
            ->setLocation('post_type', '==', 'job');

        $jobcard
            ->addText('position', ['label' => 'Stanowisko'])
            ->addTextarea('description', ['label' => 'Opis stanowiska', 'rows' => 6])
        ;
        return $jobcard->build();
    }
}