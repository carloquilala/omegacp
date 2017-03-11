<?php

namespace artworx\omegacp\FormFields\After;

class DescriptionHandler extends AbstractHandler
{
    protected $codename = 'description';

    public function visible($row, $dataType, $dateTypeContent, $options)
    {
        if (!isset($options->description)) {
            return false;
        }

        return !empty($options->description);
    }

    public function createContent($row, $dataType, $dateTypeContent, $options)
    {
        return '<i class="help-block"><span class="omega-info-circled"></span>'
            .$options->description
            .'</i>';
    }
}
