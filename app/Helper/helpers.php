<?php

use App\Models\Option;

function activeMenu($route)
{
    if (is_array($route))
        return in_array(request()->route()->getName(), $route) ? ' active' : '';
    else
        return request()->route()->getName() == $route ? ' active' : '';
}

function activeDropdown($route)
{
    if (is_array($route))
        return in_array(request()->route()->getName(), $route) ? ' menu-open' : '';
    else
        return request()->route()->getName() == $route ? ' menu-open' : '';
}

function per_number($string)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($num, $persian, $string);
    $englishNumbersOnly = str_replace($num, $arabic, $convertedPersianNums);

    return $englishNumbersOnly;
}
function persianTime($date)
{
    if ($date) {
        return per_number(verta($date)->format('Y/m/d H:i'));
    }
}
function ert($variable)
{
    if ($variable == 't-a') { //ticket-attachment
        return 'uploads/thickets/';
    }
    if ($variable == 'cd') {  //confirmDelete
        confirmDelete('مطمئنید؟', 'آیا از حذف این مورد اطمینان دارید؟');
        return true;
    }
    dd('ورودی اشتباهه');
}

function doUpload($file, $path)
{
    $fileName = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path($path), $fileName);
    return $fileName;
}

function price($amount)
{
    if ($amount) {
        return per_number(number_format($amount));
    }
}
function get_option($key){
$option=new Option;
return $option->get_option($key);
}

