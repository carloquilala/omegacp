@if(isset($dataTypeContent->{$row->field}))
    <img src="{{ Omega::image($dataTypeContent->{$row->field}) }}"
         style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
@endif
<input type="file" name="{{ $row->field }}">