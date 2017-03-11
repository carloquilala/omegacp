<?php

namespace artworx\omegacp\FormFields;

class TextAreaHandler extends AbstractHandler
{
    protected $codename = 'text_area';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('omega::formfields.text_area', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
