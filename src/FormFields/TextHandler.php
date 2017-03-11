<?php

namespace AI\Omega\FormFields;

class TextHandler extends AbstractHandler
{
    protected $codename = 'text';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('omega::formfields.text', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
