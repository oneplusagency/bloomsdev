<?php

namespace helper;

use db\JigMapper;

class Sort
{
    const DEFAULT = ['order' => 'id SORT_DESC'];

    function maxId()
    {
        $gallery = new JigMapper('gallery');
        $gallery->load(null, Sort::DEFAULT);
        if ($gallery->dry()) {
            return 0;
        } else {
            return $gallery['id'];
        }
    }

    function nextId()
    {
        $id = new JigMapper('id');
        $id->load(['@object=?', 'gallery']);
        if ($id->dry()) {
            $id['object'] = 'gallery';
            $id['id'] = 2;
        } else {
            $id['id']++;
        }
        $id->save();
        return --$id['id'];
    }
}