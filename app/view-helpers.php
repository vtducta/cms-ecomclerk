<?php
function btn($title = 'Save', $opt = [])
{
    $options = array_merge([
        'class' => 'btn-primary btn-sm',
        'icon' => 'fa fa-plus',
        'style' => '',
        'id' => '',
        'data' => []
    ], $opt);
    $a = '<button type="submit" 
                    id="' . $options['id'] . '"
                    class="customBtn btn ' . $options['class'] . '" 
                    style="' . $options['style'] . '" ';

    if (@$options['name'] != '') {
        $a .= ' name="' . $options['name'] . '"';
    }

    if (@$options['value'] != '') {
        $a .= ' value="' . $options['value'] . '"';
    }

    if (!empty($options['data'])) {
        foreach ($options['data'] as $k => $v) {
            $a .= ' data-' . $k . '="' . $v . '" ';
        }
    }

    $a .= ' ><i class="' . $options['icon'] . '"></i> ';
    if ($title != '') {
        $a .= '<span class="b-label" >' . $title . '</span>';
    }
    $a .= '   </button>';

    return $a;
}

function editBtn($title = 'Save', $opt = [])
{
    $options = array_merge([
        'class' => 'btn-primary btn-sm',
        'icon' => 'fa fa-pencil',
        'style' => ''
    ], $opt);
    return btn($title, $options);
}

function linkBtn($title = 'Save', $url = '', $opt = [])
{
    $options = array_merge([
        'class' => 'btn-primary btn-sm',
        'icon' => 'fa fa-plus',
        'style' => '',
        'target' => '_self',
        'id' => '',
        'data' => [],
        'title' => $title
    ], $opt);
    $a = '<a href="' . $url . '" target="' . $options['target'] . '" title="' . $options['title'] . '"
                    id="' . $options['id'] . '" 
                    class="customBtn btn ' . $options['class'] . '" 
                    style="' . $options['style'] . '" ';
    if (!empty($options['data'])) {
        foreach ($options['data'] as $k => $v) {
            $a .= ' data-' . $k . '="' . $v . '" ';
        }
    }

    $a .= '
       ><i class="' . $options['icon'] . '"></i> ';
    if ($title != '') {
        $a .= '<span class="b-label" >' . $title . '</span>';
    }
    $a .= '</a>';

    return $a;
}


function putFormField()
{
    return '<input type="hidden" name="_method" value="put">';
}