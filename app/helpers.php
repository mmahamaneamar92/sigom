<?php

if(!function_exists('flash')){
    function flash($message, $type = 'success'){
        session()->flash('message', $message);
        session()->flash('type', $type);
    }
}
